<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\OrderDeliverabilitySampleRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderDeliverabilitySampleRepository::class)]
class OrderDeliverabilitySample extends AbstractSample
{
    #[ORM\Column(type: 'string', length: 32)]
    private string $orderNumber;

    #[ORM\Column(type: 'string', length: 32)]
    private string $shopId;

    #[ORM\Column(type: 'string', length: 64)]
    private string $source;

    #[ORM\Column(type: 'string', length: 16)]
    private string $currentState;

    #[ORM\Column(type: 'float')]
    private float $grossAmount;

    public static function getType(): string
    {
        return 'order_deliverability_sample';
    }

    public static function isPreSampleAvailable(): bool
    {
        return true;
    }

    public static function getPreSampleFinalStates(): array
    {
        return ['5', '7'];
    }

    public static function getStateField(): string
    {
        return 'current_state';
    }

    /**
     * @param array<string, mixed> $data
     *
     * @throws \Exception
     */
    public function fill(array $data): self
    {
        if ((\count(self::getFields()) - 1) !== \count($data)) {
            throw new \Exception(sprintf('Invalid fields count expected %s, got %s', \count(self::getFields()), \count($data)));
        }

        $this
            ->setExternalId($data['order_number'])
            ->setOrderNumber($data['order_number'])
            ->setShopId($data['shop_id'])
            ->setSource($data['source'])
            ->setCurrentState($data['current_state'])
            ->setGrossAmount('NAN' === $data['gross_amount'] ? \NAN : $data['gross_amount'])
        ;

        return $this;
    }

    public function getOrderNumber(): string
    {
        return $this->orderNumber;
    }

    public function setOrderNumber(string $orderNumber): self
    {
        $this->orderNumber = $orderNumber;

        return $this;
    }

    public function getShopId(): string
    {
        return $this->shopId;
    }

    public function setShopId(string $shopId): self
    {
        $this->shopId = $shopId;

        return $this;
    }

    public function getSource(): string
    {
        return $this->source;
    }

    public function setSource(string $source): self
    {
        $this->source = $source;

        return $this;
    }

    public function getCurrentState(): string
    {
        return $this->currentState;
    }

    public function setCurrentState(string $currentState): self
    {
        $this->currentState = $currentState;

        return $this;
    }

    public function getGrossAmount(): float
    {
        return $this->grossAmount;
    }

    public function setGrossAmount(float $grossAmount): self
    {
        $this->grossAmount = $grossAmount;

        return $this;
    }

    public static function getIdentifyingField(): string
    {
        return 'order_number';
    }

    public static function getDaysToRemovePreSamples(): int
    {
        return 11;
    }
}
