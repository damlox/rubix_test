<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ModelRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ModelRepository::class)]
class Model
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 64)]
    private string $algorithm;

    #[ORM\Column(type: 'string', length: 64)]
    private string $sampleType;

    #[ORM\Column(type: 'string', length: 255)]
    private string $predictionLabel;

    #[ORM\Column(type: 'string', length: 32, unique: true)]
    private string $key;

    #[ORM\Column(type: 'string', length: 255)]
    private string $name;

    #[ORM\Column(type: 'boolean')]
    private bool $readyForPredict = false;

    #[ORM\Column(type: 'boolean')]
    private bool $enabled = false;

    #[ORM\Column(type: 'boolean')]
    private bool $training = false;

    #[ORM\Column(type: 'boolean', options: ['default' => false])]
    private bool $autoAdjusting = false;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $trainingSince;

    /**
     * @var array<int, mixed>
     */
    #[ORM\Column(type: 'simple_array')]
    private array $selectFields = [];

    /**
     * @var array<int, mixed>
     */
    #[ORM\Column(type: 'json')]
    private array $conditions = [];

    #[ORM\Column(type: 'integer')]
    private int $trainingInterval;

    #[ORM\Column(type: 'datetime')]
    private \DateTimeInterface $nextTraining;

    #[ORM\Column(type: 'datetime', options: ['default' => 'CURRENT_TIMESTAMP'])]
    private \DateTimeInterface $nextTuning;

    /**
     * @var array<string, mixed>
     */
    #[ORM\Column(type: 'json')]
    private array $hyperParameters = [];

    /**
     * @var array<int, mixed>
     */
    #[ORM\Column(type: 'json')]
    private array $transformers = [];

    public function __construct()
    {
        $this->nextTraining = new \DateTimeImmutable();
        $this->nextTuning = new \DateTimeImmutable();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getAlgorithm(): string
    {
        return $this->algorithm;
    }

    public function setAlgorithm(string $algorithm): self
    {
        $this->algorithm = $algorithm;

        return $this;
    }

    public function getSampleType(): string
    {
        return $this->sampleType;
    }

    public function setSampleType(string $sampleType): self
    {
        $this->sampleType = $sampleType;

        return $this;
    }

    /**
     * @return array<string, string>
     */
    public function getPredictionLabel(): array
    {
        return [
            $this->predictionLabel => 'string',
        ];
    }

    public function setPredictionLabel(string $predictionLabel): self
    {
        $this->predictionLabel = $predictionLabel;

        return $this;
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function setKey(string $key): self
    {
        $this->key = $key;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function isReadyForPredict(): ?bool
    {
        return $this->readyForPredict;
    }

    public function setReadyForPredict(bool $readyForPredict): self
    {
        $this->readyForPredict = $readyForPredict;

        return $this;
    }

    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): self
    {
        $this->enabled = $enabled;

        return $this;
    }

    public function isTraining(): bool
    {
        return $this->training;
    }

    public function setTraining(bool $training): self
    {
        $this->training = $training;

        return $this;
    }

    public function isAutoAdjusting(): bool
    {
        return $this->autoAdjusting;
    }

    public function setAutoAdjusting(bool $autoAdjusting): self
    {
        $this->autoAdjusting = $autoAdjusting;

        return $this;
    }

    public function getTrainingSince(): ?\DateTimeInterface
    {
        return $this->trainingSince;
    }

    public function setTrainingSince(\DateTimeInterface $trainingSince): self
    {
        $this->trainingSince = $trainingSince;

        return $this;
    }

    /**
     * @return array<int, mixed>
     */
    public function getSelectFields(): array
    {
        return $this->selectFields;
    }

    /**
     * @param array<int, mixed> $selectFields
     */
    public function setSelectFields(array $selectFields): self
    {
        $this->selectFields = $selectFields;

        return $this;
    }

    /**
     * @return array<int, mixed>
     */
    public function getConditions(): array
    {
        return $this->conditions;
    }

    /**
     * @param array<int, mixed> $conditions
     */
    public function setConditions(array $conditions): self
    {
        $this->conditions = $conditions;

        return $this;
    }

    public function getTrainingInterval(): int
    {
        return $this->trainingInterval;
    }

    public function setTrainingInterval(int $trainingInterval): self
    {
        $this->trainingInterval = $trainingInterval;

        return $this;
    }

    public function getNextTraining(): \DateTimeInterface
    {
        return $this->nextTraining;
    }

    public function setNextTraining(\DateTimeInterface $nextTraining): self
    {
        $this->nextTraining = $nextTraining;

        return $this;
    }

    public function getNextTuning(): \DateTimeInterface
    {
        return $this->nextTuning;
    }

    public function setNextTuning(\DateTimeInterface $nextTuning): self
    {
        $this->nextTuning = $nextTuning;

        return $this;
    }

    /**
     * @return array<string, mixed>
     */
    public function getHyperParameters(): array
    {
        return $this->hyperParameters;
    }

    /**
     * @param array<string, mixed> $hyperParameters
     */
    public function setHyperParameters(array $hyperParameters): self
    {
        $this->hyperParameters = $hyperParameters;

        return $this;
    }

    /**
     * @return array<int, mixed>
     */
    public function getTransformers(): array
    {
        return $this->transformers;
    }

    /**
     * @param array<int, mixed> $transformers
     */
    public function setTransformers(array $transformers): self
    {
        $this->transformers = $transformers;

        return $this;
    }

    public function canPredict(): bool
    {
        return !$this->isTraining() && $this->isEnabled() && $this->isReadyForPredict();
    }
}
