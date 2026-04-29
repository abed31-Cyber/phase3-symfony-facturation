<?php

namespace App\Entity;

use App\Repository\InvoiceItemRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InvoiceItemRepository::class)]
class InvoiceItem
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column]
    private ?float $quantity = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 0)]
    private ?string $unitPrice = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 20, scale: 0)]
    private ?string $taxTva = null;

    #[ORM\ManyToOne(inversedBy: 'invoiceItems')]
    private ?Invoice $invoice = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getQuantity(): ?float
    {
        return $this->quantity;
    }

    public function setQuantity(float $quantity): static
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getUnitPrice(): ?string
    {
        return $this->unitPrice;
    }

    public function setUnitPrice(string $unitPrice): static
    {
        $this->unitPrice = $unitPrice;

        return $this;
    }

    public function getTaxTva(): ?string
    {
        return $this->taxTva;
    }

    public function setTaxTva(string $taxTva): static
    {
        $this->taxTva = $taxTva;

        return $this;
    }

    public function getInvoice(): ?Invoice
    {
        return $this->invoice;
    }

    public function setInvoice(?Invoice $invoice): static
    {
        $this->invoice = $invoice;

        return $this;
    }

    public function getSubTotalHt(): float
    {
        $unit = $this->unitPrice !== null ? (float) $this->unitPrice : 0.0;
        $qty = $this->quantity !== null ? (float) $this->quantity : 0.0;

        return $qty * $unit;
    }

    public function getTaxRate(): float
    {
        return $this->taxTva !== null ? (float) $this->taxTva : 0.0;
    }
}
