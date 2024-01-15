<?php

declare(strict_types=1);

namespace App\Rubix\Kernel;

use App\Rubix\WrapperInterface;
use Rubix\ML\Kernels\Distance\Distance;

/**
 * @extends WrapperInterface<Distance>
 */
interface KernelInterface extends WrapperInterface
{
}
