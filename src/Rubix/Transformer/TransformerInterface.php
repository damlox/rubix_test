<?php

declare(strict_types=1);

namespace App\Rubix\Transformer;

use App\Rubix\WrapperInterface;
use Rubix\ML\Transformers\Transformer;

/**
 * @extends WrapperInterface<Transformer>
 */
interface TransformerInterface extends WrapperInterface
{
}
