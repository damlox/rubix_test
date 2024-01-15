<?php

declare(strict_types=1);

namespace App\Rubix\Transformer;

use Rubix\ML\Transformers\OneHotEncoder;

class OneHotEncoderTransformer implements TransformerInterface
{
    public static function getType(): string
    {
        return 'one_hot_encoder_transformer';
    }

    public static function getClass(): string
    {
        return OneHotEncoder::class;
    }

    public static function getScheme(): array
    {
        return [];
    }
}
