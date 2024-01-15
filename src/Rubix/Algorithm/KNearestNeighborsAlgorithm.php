<?php

declare(strict_types=1);

namespace App\Rubix\Algorithm;

use Rubix\ML\Classifiers\KNearestNeighbors;

class KNearestNeighborsAlgorithm implements AlgorithmInterface
{
    public static function getType(): string
    {
        return 'knearest_neighbors_algorithm';
    }

    public static function getClass(): string
    {
        return KNearestNeighbors::class;
    }

    public static function getScheme(): array
    {
        return [
            'weighted' => 'bool',
            'k' => 'int',
            'kernel' => 'kernel',
        ];
    }
}
