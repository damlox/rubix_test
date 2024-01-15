<?php

declare(strict_types=1);

namespace App\Util\Training;

use App\Entity\AbstractSample;
use App\Entity\OrderDeliverabilitySample;
use App\Entity\TestSample;
use App\Repository\AbstractSampleRepository;
use Doctrine\ORM\EntityManagerInterface;

class SampleManagement
{
    public function __construct(
        private EntityManagerInterface $em,
    ) {
    }

    /**
     * @return class-string<AbstractSample>
     */
    public function getSampleClassByType(string $type): string
    {
        return match ($type) {
            OrderDeliverabilitySample::getType() => OrderDeliverabilitySample::class,
            TestSample::getType() => TestSample::class,
            default => throw new \RuntimeException(sprintf('Unknown sample type "%s".', $type))
        };
    }

    /**
     * @template T of AbstractSample
     *
     * @param class-string<T> $className
     *
     * @return AbstractSampleRepository<T>
     */
    public function getSampleRepositoryByClassName(string $className): AbstractSampleRepository
    {
        $result = $this->em->getRepository($className);

        \assert($result instanceof AbstractSampleRepository);

        return $result;
    }

    /**
     * @return AbstractSampleRepository<AbstractSample>
     */
    public function getSampleRepository(string $type): AbstractSampleRepository
    {
        $className = $this->getSampleClassByType($type);

        return $this->getSampleRepositoryByClassName($className);
    }
}
