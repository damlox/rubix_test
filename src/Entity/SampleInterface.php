<?php

declare(strict_types=1);

namespace App\Entity;

interface SampleInterface
{
    public static function getType(): string;

    /**
     * @return array<string, string>
     */
    public static function getFields(): array;

    public static function getIdentifyingField(): string;

    public static function isPreSampleAvailable(): bool;

    public static function getDaysToRemovePreSamples(): null|int;

    /**
     * @return array<int, string>
     */
    public static function getPreSampleFinalStates(): array;

    public static function getStateField(): string;

    /**
     * @param array<string, int|string> $data
     */
    public function fill(array $data): self;
}
