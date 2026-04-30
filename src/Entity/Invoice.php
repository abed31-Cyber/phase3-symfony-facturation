<?php

namespace App\Entity;

use App\Repository\InvoiceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InvoiceRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Invoice
{
    const DRAFT = 'DRAFT';
    const PENDING = 'PENDING';
    const PAID = 'PAID';
    const CANCELLED = 'CANCELLED';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    public ?string $number = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(length: 20)]
    private ?string $status = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'invoices')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $owner = null;
    #[ORM\Column]
    private ?\DateTimeImmutable $due_date = null;

    #[ORM\Column]
    private ?float $total_ht = null;

    #[ORM\Column]
    private ?float $total_ttc = null;

    #[ORM\ManyToOne(inversedBy: 'invoices')]
    private ?Client $client = null;

    /**
     * @var Collection<int, InvoiceItem>
     */
    #[ORM\OneToMany(targetEntity: InvoiceItem::class, mappedBy: 'invoice')]
    private Collection $invoiceItems; 

 public function __construct()
                              {
                                  $this->createdAt = new \DateTimeImmutable();
                                  $this->status = 'DRAFT'; // Par défaut en brouillon
                                  // Échéance à +30 jours par défaut
                                  $this->due_date = (new \DateTimeImmutable())->modify('+30 days');
                                  $this->invoiceItems = new ArrayCollection();
                              }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getNumber(): ?string
    {
        return $this->number;
    }

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function computeTotals(): void
    {
        $this->total_ht = $this->getTotalHt();
        $this->total_ttc = $this->getTotalTtc();
    }

    #[ORM\PrePersist]
    public function setNumber(): void
    {
        if ($this->number === null) {
            // Logique simple : FAC-Année-Timestamp (ou un random)
            // Pour faire un vrai compteur (001, 002), il faudrait une logique en Repository
            $this->number = 'FAC-' . date('Y') . '-' . strtoupper(bin2hex(random_bytes(3)));
        }
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): static
    {
        $this->owner = $owner;

        return $this;
    }


    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getDueDate(): ?\DateTimeImmutable
    {
        return $this->due_date;
    }

    public function setDueDate(\DateTimeImmutable $due_date): static
    {
        $this->due_date = $due_date;

        return $this;
    }
    

    public function getTotalHt(): ?float
    {
        $total = 0.0;
        foreach ($this->invoiceItems as $item) {
            $total += $item->getSubTotalHt();
        }

        return $total;
    }

    public function setTotalHt(float $total_ht): static
    {
        $this->total_ht = $total_ht;

        return $this;
    }

    public function getTotalTtc(): ?float
    {
        $total = 0.0;
        foreach ($this->invoiceItems as $item) {
            $sub = $item->getSubTotalHt();
            $tax = $sub * ($item->getTaxRate() / 100.0);
            $total += $sub + $tax;
        }

        return $total;
    }

    public function setTotalTtc(float $total_ttc): static
    {
        $this->total_ttc = $total_ttc;

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): static
    {
        $this->client = $client;

        return $this;
    }

    /**
     * @return Collection<int, InvoiceItem>
     */
    public function getInvoiceItems(): Collection
    {
        return $this->invoiceItems;
    }

    // Backward-compatibility: some code/templates may access $invoice->items or call getItems()
    public function getItems(): Collection
    {
        return $this->invoiceItems;
    }

    public function __get(string $name)
    {
        if ($name === 'items') {
            return $this->invoiceItems;
        }

        return null;
    }

    public function __isset(string $name): bool
    {
        if ($name === 'items') {
            return isset($this->invoiceItems) && !$this->invoiceItems->isEmpty();
        }

        return false;
    }

    public function addInvoiceItem(InvoiceItem $invoiceItem): static
    {
        if (!$this->invoiceItems->contains($invoiceItem)) {
            $this->invoiceItems->add($invoiceItem);
            $invoiceItem->setInvoice($this);
        }

        return $this;
    }

    public function removeInvoiceItem(InvoiceItem $invoiceItem): static
    {
        if ($this->invoiceItems->removeElement($invoiceItem)) {
            // set the owning side to null (unless already changed)
            if ($invoiceItem->getInvoice() === $this) {
                $invoiceItem->setInvoice(null);
            }
        }

        return $this;
    }
}
