<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Model;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Model>
 */
class ModelRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Model::class);
    }

    public function getOneModelForTraining(): ?Model
    {
        $now = new \DateTimeImmutable();

        try {
            return $this->createQueryBuilder('m')
                ->where('m.nextTraining < :now')
                ->andWhere('m.enabled = :enabled')
                ->andWhere('m.training = :training')
                ->setParameters([
                    'now' => $now,
                    'enabled' => true,
                    'training' => false,
                ])
                ->orderBy('m.nextTraining', 'ASC')
                ->setMaxResults(1)
                ->getQuery()
                ->getOneOrNullResult()
            ;
        } catch (NonUniqueResultException) {
            return null;
        }
    }

    /**
     * @throws NonUniqueResultException
     */
    public function getOneByKey(string $modelKey): ?Model
    {
        return $this->createQueryBuilder('m')
            ->where('m.key = :key')
            ->setParameter('key', $modelKey)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
