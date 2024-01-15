<?php

declare(strict_types=1);

namespace App\Rubix\Algorithm;

use Rubix\ML\Classifiers\ClassificationTree;

class ClassificationTreeAlgorithm implements AlgorithmInterface
{
    public static function getType(): string
    {
        return 'classification_tree_algorithm';
    }

    public static function getClass(): string
    {
        return ClassificationTree::class;
    }

    public static function getScheme(): array
    {
        return [
            'maxHeight' => 'int',
            'maxLeafSize' => 'int',
            'minPurityIncrease' => 'float',
            'maxFeatures' => 'int',
            'maxBins' => 'int',
        ];
    }
}
