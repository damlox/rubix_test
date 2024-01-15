<?php

declare(strict_types=1);

namespace App\Rubix;

use App\Rubix\Transformer\TransformerInterface;
use Rubix\ML\Transformers\Transformer;

class TransformerSelector
{
    /**
     * @var array<string, class-string<TransformerInterface>>
     */
    private array $transformerTypes = [];

    /**
     * @param iterable<string, TransformerInterface> $transformers
     */
    public function __construct(
        iterable $transformers,
        private readonly ParameterDecoder $parameterDecoder,
    ) {
        foreach ($transformers as $transformer) {
            $this->transformerTypes[$transformer::getType()] = $transformer::class;
        }
    }

    /**
     * @param array<int, mixed> $rawTransformers
     *
     * @return array<int, Transformer>
     */
    public function createTransformers(array $rawTransformers): array
    {
        $transformers = [];
        foreach ($rawTransformers as $rawTransformerData) {
            foreach ($rawTransformerData as $name => $rawTransformerParams) {
                [$className, $decodedParams] = $this->getDecodedParameters($name, $rawTransformerParams);
                $transformers[] = new $className(...$decodedParams);
            }
        }

        return $transformers;
    }

    /**
     * @param array<string, mixed> $rawTransformerParams
     *
     * @return array{class-string<Transformer>, array<string, mixed>}
     */
    private function getDecodedParameters(string $name, array $rawTransformerParams): array
    {
        if (!isset($this->transformerTypes[$name])) {
            throw new \RuntimeException(sprintf('Unknown transformer "%s".', $name));
        }

        $className = $this->transformerTypes[$name]::getClass();
        $scheme = $this->transformerTypes[$name]::getScheme();
        $decodedParams = $this->parameterDecoder->decode($rawTransformerParams, $scheme);

        return [$className, $decodedParams];
    }
}
