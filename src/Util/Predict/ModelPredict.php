<?php

declare(strict_types=1);

namespace App\Util\Predict;

use App\Entity\AbstractSample;
use App\Repository\ModelRepository;
use App\Util\Predict\Exception\FieldNotFoundException;
use App\Util\Predict\Exception\ModelNotFoundException;
use App\Util\Predict\Exception\ModelNotReadyException;
use App\Util\Training\SampleManagement;
use Doctrine\ORM\NonUniqueResultException;
use Rubix\ML\Datasets\Unlabeled;
use Rubix\ML\PersistentModel;
use Rubix\ML\Persisters\Filesystem;
use Rubix\ML\Serializers\RBX;
use Rubix\ML\Transformers\Transformer;

class ModelPredict
{
    public function __construct(
        private readonly ModelRepository $modelRepository,
        private readonly SampleManagement $sampleManagement,
        private readonly string $trainingPath,
    ) {
    }

    /**
     * @param array<string|int, mixed> $inputRaw
     *
     * @return array<string|int, mixed>
     *
     * @throws NonUniqueResultException
     * @throws ModelNotReadyException
     * @throws FieldNotFoundException
     * @throws ModelNotFoundException
     */
    public function predict(string $modelKey, array $inputRaw): array
    {
        $model = $this->modelRepository->getOneByKey($modelKey);
        if (null === $model) {
            throw new ModelNotFoundException(sprintf('Model "%s" not found.', $modelKey));
        }

        if (!$model->canPredict()) {
            throw new ModelNotReadyException(sprintf('Model "%s" is not ready for predict.', $model->getKey()));
        }

        /** @var AbstractSample $sampleClass */
        $sampleClass = $this->sampleManagement->getSampleClassByType($model->getSampleType());

        $selectFields = $model->getSelectFields();

        $input = [];

        foreach ($inputRaw as $item) {
            $id = $item[$sampleClass::getIdentifyingField()];

            $tmp = [];
            foreach ($selectFields as $selectField) {
                if (!isset($item[$selectField])) {
                    throw new FieldNotFoundException(sprintf('Field "%s" not found in dataset.', $selectField));
                }

                $tmp[$selectField] = $item[$selectField];
            }

            $input[$id] = $tmp;
        }

        $inputKeys = array_keys($input);
        $input = array_values($input);

        $dataset = Unlabeled::build($input);

        $transformerLength = \count($model->getTransformers());
        for ($i = 0; $i < $transformerLength; $i++) {
            $transformerFilePath = sprintf('%s/%s_trans_%s.rbx', $this->trainingPath, $model->getKey(), $i);

            $persister = new Filesystem($transformerFilePath);
            $transformer = $persister->load()->deserializeWith(new RBX());

            if (!($transformer instanceof Transformer)) {
                throw new \RuntimeException(sprintf('File "%s" is not transformer type.', $transformerFilePath));
            }

            $dataset->apply($transformer);
        }

        $estimatorFilePath = sprintf('%s/%s.rbx', $this->trainingPath, $model->getKey());
        $estimator = PersistentModel::load(new Filesystem($estimatorFilePath));

        $probaResult = $estimator->proba($dataset);

        $result = [];
        foreach ($probaResult as $index => $item) {
            $result[$inputKeys[$index]] = $item;
        }

        return $result;
    }
}
