<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\AbstractSample;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AbstractSample>
 *
 * @method AbstractSample|null find($id, $lockMode = null, $lockVersion = null)
 * @method AbstractSample|null findOneBy(array $criteria, array $orderBy = null)
 * @method AbstractSample[]    findAll()
 * @method AbstractSample[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SampleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AbstractSample::class);
    }
}
