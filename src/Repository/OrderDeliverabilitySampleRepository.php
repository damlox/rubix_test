<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\OrderDeliverabilitySample;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends AbstractSampleRepository<OrderDeliverabilitySample>
 */
class OrderDeliverabilitySampleRepository extends AbstractSampleRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrderDeliverabilitySample::class);
    }
}
