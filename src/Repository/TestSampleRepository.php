<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\TestSample;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends AbstractSampleRepository<TestSample>
 */
class TestSampleRepository extends AbstractSampleRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TestSample::class);
    }
}
