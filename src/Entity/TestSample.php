<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\TestSampleRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TestSampleRepository::class)]
class TestSample extends AbstractSample
{
    public static function getType(): string
    {
        return 'test_sample';
    }

    #[ORM\Column(type: 'integer')]
    private int $orderId;

    #[ORM\Column(type: 'string', length: 8)]
    private string $agent;

    #[ORM\Column(type: 'string', length: 8)]
    private string $campaign;

    #[ORM\Column(type: 'string', length: 8)]
    private string $adNetwork;

    #[ORM\Column(type: 'string', length: 8)]
    private string $state;

    public function getOrderId(): ?int
    {
        return $this->orderId;
    }

    public function setOrderId(int $orderId): self
    {
        $this->orderId = $orderId;

        return $this;
    }

    public function getAgent(): string
    {
        return $this->agent;
    }

    public function setAgent(string $agent): self
    {
        $this->agent = $agent;

        return $this;
    }

    public function getCampaign(): string
    {
        return $this->campaign;
    }

    public function setCampaign(string $campaign): self
    {
        $this->campaign = $campaign;

        return $this;
    }

    public function getAdNetwork(): string
    {
        return $this->adNetwork;
    }

    public function setAdNetwork(string $adNetwork): self
    {
        $this->adNetwork = $adNetwork;

        return $this;
    }

    public function getState(): string
    {
        return $this->state;
    }

    public function setState(string $state): self
    {
        $this->state = $state;

        return $this;
    }

    /**
     * @throws \Exception
     */
    public function fill(array $data): self
    {
        if (\count(self::getFields()) !== \count($data)) {
            throw new \Exception(sprintf('Invalid fields count expected %s, got %s', \count(self::getFields()), \count($data)));
        }

        return $this;
    }

    public static function getIdentifyingField(): string
    {
        return 'orderId';
    }

    public static function isPreSampleAvailable(): bool
    {
        return false;
    }

    public static function getPreSampleFinalStates(): array
    {
        return [];
    }

    public static function getStateField(): string
    {
        return '';
    }

    public static function getDaysToRemovePreSamples(): int|null
    {
        return null;
    }
}
