<?php

declare(strict_types=1);

namespace App\Rubix\Algorithm;

use App\Rubix\WrapperInterface;
use Rubix\ML\Learner;

/**
 * @extends WrapperInterface<Learner>
 */
interface AlgorithmInterface extends WrapperInterface
{
}
