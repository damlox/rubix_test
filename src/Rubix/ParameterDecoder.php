<?php

declare(strict_types=1);

namespace App\Rubix;

use App\Rubix\Algorithm\AlgorithmInterface;
use App\Rubix\Kernel\KernelInterface;

class ParameterDecoder
{
    /**
     * @var array<string, class-string<KernelInterface>>
     */
    private array $kernelType = [];

    /**
     * @var array<string, class-string<AlgorithmInterface>>
     */
    private array $algorithmType = [];

    /**
     * @param iterable<string, KernelInterface>    $kernels
     * @param iterable<string, AlgorithmInterface> $algorithms
     */
    public function __construct(
        iterable $kernels,
        iterable $algorithms,
    ) {
        foreach ($kernels as $kernel) {
            $this->kernelType[$kernel::getType()] = $kernel::class;
        }

        foreach ($algorithms as $algorithm) {
            $this->algorithmType[$algorithm::getType()] = $algorithm::class;
        }
    }

    /**
     * @param array<string, mixed>  $parameters
     * @param array<string, string> $scheme
     *
     * @return array<string, mixed>
     */
    public function decode(array $parameters, array $scheme): array
    {
        $result = [];
        foreach ($scheme as $key => $type) {
            if (!isset($parameters[$key])) {
                continue;
            }

            $result[$key] = $this->decodeParameter($parameters[$key], $type);
        }

        return $result;
    }

    private function decodeParameter(mixed $parameter, string $type): mixed
    {
        if (\in_array($type, ['kernel', 'algorithm'], true)) {
            $paramType = $parameter['type'];
            $list = match ($type) {
                'kernel' => $this->kernelType,
                'algorithm' => $this->algorithmType
            };

            if (!isset($list[$paramType])) {
                throw new \RuntimeException(sprintf('Unknown %s "%s".', $type, $paramType));
            }

            $class = $list[$paramType];

            $parameterClass = $class::getClass();
            $parametersNested = $parameter['parameters'];

            $decoded = $this->decode($parametersNested, $class::getScheme());

            return new $parameterClass(...$decoded);
        }

        return $parameter;
    }
}
