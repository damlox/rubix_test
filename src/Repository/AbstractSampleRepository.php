<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Model;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Exception;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @template T of \App\Entity\AbstractSample
 *
 * @extends ServiceEntityRepository<T>
 *
 * @method T|null find($id, $lockMode = null, $lockVersion = null)
 * @method T|null findOneBy(array $criteria, array $orderBy = null)
 * @method T[]    findAll()
 * @method T[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
abstract class AbstractSampleRepository extends ServiceEntityRepository
{
    /**
     * @var class-string<T>
     */
    private string $sampleClass;

    /**
     * @param class-string<T> $sampleClass
     */
    public function __construct(ManagerRegistry $registry, string $sampleClass)
    {
        parent::__construct($registry, $sampleClass);

        $this->sampleClass = $sampleClass;
    }

    /**
     * @throws Exception
     */
    public function getSamples(Model $model): \Generator
    {
        $fields = implode(',', [...$model->getSelectFields(), ...array_keys($model->getPredictionLabel())]);
        $tableName = $this->getClassMetadata()->getTableName();

        $query = sprintf(
            'SELECT %s FROM %s',
            $fields,
            $tableName,
        );

        $result = $this->_em->getConnection()->executeQuery($query);
        $allFields = [...$this->sampleClass::getFields(), ...$model->getPredictionLabel()];
        $selectFields = [...$model->getSelectFields(), ...array_keys($model->getPredictionLabel())];

        foreach ($result->iterateAssociative() as $row) {
            foreach ($selectFields as $field) {
                $type = $allFields[$field];

                $row[$field] = match ($type) {
                    'float' => (float) $row[$field],
                    'int' => (int) $row[$field],
                    'bool' => (bool) $row[$field],
                    'string' => (string) $row[$field],
                    default => ('NaN' === $row[$field] && 'string' !== $type) ? \NAN : $row[$field]
                };
            }

            yield $row;
        }
    }
}
