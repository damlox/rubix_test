<?php

declare(strict_types=1);

namespace App\Rubix;

use App\Rubix\Algorithm\AlgorithmInterface;
use Rubix\ML\Learner;

class AlgorithmSelector
{
    /**
     * @var array<string, class-string<AlgorithmInterface>>
     */
    private array $algorithmType = [];

    /**
     * @param iterable<string, AlgorithmInterface> $algorithms
     */
    public function __construct(
        iterable $algorithms,
        private readonly ParameterDecoder $parameterDecoder,
    ) {
        foreach ($algorithms as $algorithm) {
            $this->algorithmType[$algorithm::getType()] = $algorithm::class;
        }
    }

    /**
     * @param array<string, mixed> $rawParameters
     */
    public function createEstimator(string $type, array $rawParameters): Learner
    {
        if (!isset($this->algorithmType[$type])) {
            throw new \RuntimeException(sprintf('Unknown algorithm type "%s".', $type));
        }

        $classType = $this->algorithmType[$type];
        $class = $classType::getClass();
        $scheme = $classType::getScheme();

        $parameters = $this->parameterDecoder->decode($rawParameters, $scheme);

        return new $class(...$parameters);
    }
}
