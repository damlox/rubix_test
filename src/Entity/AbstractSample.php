<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\PropertyInfo\PropertyInfoExtractor;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;

#[ORM\MappedSuperclass]
abstract class AbstractSample implements SampleInterface
{
    use TimestampableEntity;

    public static string $type;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 32)]
    private string $externalId;

    #[ORM\Column(type: 'string')]
    private string $finalState;

    /**
     * @var array<int, string>
     */
    protected static array $baseExcludedFields = [
        'created_at',
        'updated_at',
        'sample_id',
        'id',
        'type',
        'final_state',
    ];

    public static function getFields(): array
    {
        $nameConverter = new CamelCaseToSnakeCaseNameConverter();
        $reflectionExtractor = new ReflectionExtractor();
        $propertyInfo = new PropertyInfoExtractor(
            [$reflectionExtractor],
            [$reflectionExtractor],
        );

        $fields = [];
        foreach ($propertyInfo->getProperties(static::class) ?? [] as $property) {
            $propertyTypes = $propertyInfo->getTypes(static::class, $property);
            if (null !== $propertyTypes && \count($propertyTypes)) {
                $fields[$nameConverter->normalize($property)] = $propertyTypes[0]->getBuiltinType();
            }
        }

        return array_diff_key($fields, array_flip(self::getExcludedFields()));
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getExternalId(): string
    {
        return $this->externalId;
    }

    public function setExternalId(string $externalId): static
    {
        $this->externalId = $externalId;

        return $this;
    }

    public function getFinalState(): string
    {
        return $this->finalState;
    }

    public function setFinalState(string $finalState): static
    {
        $this->finalState = $finalState;

        return $this;
    }

    /**
     * @return array<int, string>
     */
    private static function getExcludedFields(): array
    {
        return array_merge(static::class::$excludedFields ?? [], static::$baseExcludedFields);
    }
}
