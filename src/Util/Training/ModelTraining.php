<?php

declare(strict_types=1);

namespace App\Util\Training;

use App\Entity\Model;
use App\Repository\ModelRepository;
use App\Rubix\AlgorithmSelector;
use App\Rubix\TransformerSelector;
use Rubix\ML\Datasets\Labeled;
use Rubix\ML\Learner;
use Rubix\ML\Persistable;
use Rubix\ML\PersistentModel;
use Rubix\ML\Persisters\Filesystem;
use Rubix\ML\Serializers\RBX;
use Rubix\ML\Transformers\Transformer;

class ModelTraining
{
    private bool $initialized = false;
    private ?Model $model = null;

    private int $modelId;
    private int $modelInterval;

    public function __construct(
        private readonly ModelRepository $modelRepository,
        private readonly string $trainingPath,
        private readonly SampleManagement $sampleManagement,
        private readonly ModelManagement $modelManagement,
        private readonly AlgorithmSelector $algorithmSelector,
        private readonly TransformerSelector $transformerSelector,
    ) {
    }

    public function initialize(): ?string
    {
        $this->model = $this->modelRepository->getOneModelForTraining();
        $this->initialized = true;

        if (null !== $this->model) {
            $this->modelId = $this->model->getId();
            $this->modelInterval = $this->model->getTrainingInterval();
        }

        return $this->model?->getName();
    }

    /**
     * @throws \Exception
     */
    public function execute(): void
    {
        if (false === $this->initialized || null === $this->model) {
            throw new \RuntimeException('Initialize first.');
        }

        $this->modelManagement->startTraining($this->modelId);

        try {
            $dataset = $this->applyTransformers($this->model);
            $estimatorClass = $this->algorithmSelector->createEstimator($this->model->getAlgorithm(), $this->model->getHyperParameters());

            $estimator = $this->trainEstimator($estimatorClass, $dataset, $this->model);
            $estimator->save();

            $this->modelManagement->finishTraining($this->modelId, $this->modelInterval, true);
        } catch (\Exception $exception) {
            $this->modelManagement->finishTraining($this->modelId, $this->modelInterval, false);
            throw $exception;
        }
    }

    private function applyTransformers(Model $model): Labeled
    {
        $transformers = $this->transformerSelector->createTransformers($model->getTransformers());
        $data = $this->sampleManagement->getSampleRepository($model->getSampleType())->getSamples($model);
        $dataset = Labeled::fromIterator($data);

        foreach ($transformers as $index => $transformer) {
            $dataset->apply($transformer);
            $this->serializeTransformer($transformer, $model, $index);
        }

        return $dataset;
    }

    private function serializeTransformer(Transformer $transformer, Model $model, int $index): void
    {
        $transformerFilePath = sprintf('%s/%s_trans_%s.rbx', $this->trainingPath, $model->getKey(), $index);
        if (!($transformer instanceof Persistable)) {
            throw new \RuntimeException('Transformer cannot be serialized.');
        }

        $rbx = new RBX();
        $rbx->serialize($transformer)->saveTo(new Filesystem($transformerFilePath));
    }

    private function trainEstimator(Learner $estimatorClass, Labeled $dataset, Model $model): PersistentModel
    {
        $estimatorFilePath = sprintf('%s/%s.rbx', $this->trainingPath, $model->getKey());
        $estimator = new PersistentModel($estimatorClass, new Filesystem($estimatorFilePath));
        $estimator->train($dataset);

        return $estimator;
    }
}
