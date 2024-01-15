<?php

declare(strict_types=1);

namespace App\Rubix\Algorithm;

use Rubix\ML\Classifiers\RandomForest;

class RandomForestAlgorithm implements AlgorithmInterface
{
    public static function getType(): string
    {
        return 'random_forest_algorithm';
    }

    public static function getClass(): string
    {
        return RandomForest::class;
    }

    public static function getScheme(): array
    {
        return [
            'base' => 'algorithm',
            'estimators' => 'int',
            'ratio' => 'float',
            'balanced' => 'bool',
        ];
    }
}
