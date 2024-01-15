<?php

declare(strict_types=1);

namespace App\Rubix;

/**
 * @template T
 */
interface WrapperInterface
{
    public static function getType(): string;

    /**
     * @return class-string<T>
     */
    public static function getClass(): string;

    /**
     * @return array<string, string>
     */
    public static function getScheme(): array;
}
