<?php

declare(strict_types=1);

namespace App\Rubix\Kernel;

use Rubix\ML\Kernels\Distance\Euclidean;

class EuclideanKernel implements KernelInterface
{
    public static function getType(): string
    {
        return 'euclidean_kernel';
    }

    public static function getClass(): string
    {
        return Euclidean::class;
    }

    public static function getScheme(): array
    {
        return [];
    }
}
