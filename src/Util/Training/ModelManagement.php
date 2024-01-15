<?php

declare(strict_types=1);

namespace App\Util\Training;

use App\Entity\Model;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;

class ModelManagement
{
    public function __construct(
        private readonly EntityManagerInterface $em,
    ) {
    }

    public function startTraining(int $modelId): void
    {
        $trainingSince = new \DateTimeImmutable();

        $this->modelQueryBuilder($modelId)
            ->set('m.training', ':training')
            ->set('m.trainingSince', ':trainingSince')
            ->setParameter('training', true)
            ->setParameter('trainingSince', $trainingSince)
            ->getQuery()
            ->execute()
        ;
    }

    public function finishTraining(int $modelId, int $interval, bool $success): void
    {
        $nextTraining = (new \DateTimeImmutable())->modify(sprintf('+%d seconds', $interval));

        $this->modelQueryBuilder($modelId)
            ->set('m.training', ':training')
            ->set('m.nextTraining', ':nextTraining')
            ->set('m.readyForPredict', ':readyForPredict')
            ->setParameter('training', false)
            ->setParameter('nextTraining', $nextTraining)
            ->setParameter('readyForPredict', $success)
            ->getQuery()
            ->execute()
        ;
    }

    private function modelQueryBuilder(int $modelId): QueryBuilder
    {
        return $this->em->createQueryBuilder()
            ->update(Model::class, 'm')
            ->where('m.id = :id')
            ->setParameter('id', $modelId)
        ;
    }
}
