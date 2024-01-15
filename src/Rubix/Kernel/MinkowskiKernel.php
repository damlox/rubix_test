<?php

declare(strict_types=1);

namespace App\Rubix\Kernel;

use Rubix\ML\Kernels\Distance\Minkowski;

class MinkowskiKernel implements KernelInterface
{
    public static function getType(): string
    {
        return 'minkowski_kernel';
    }

    public static function getClass(): string
    {
        return Minkowski::class;
    }

    public static function getScheme(): array
    {
        return [
            'lambda' => 'int',
        ];
    }
}
