<?php

namespace App\Entity;

use App\Repository\InvoiceRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InvoiceRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Invoice
{
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

 public function __construct()
         {
             $this->createdAt = new \DateTimeImmutable();
             $this->status = 'DRAFT'; // Par défaut en brouillon
             // Échéance à +30 jours par défaut
             $this->due_date = (new \DateTimeImmutable())->modify('+30 days');
         }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumber(): ?string
    {
        return $this->number;
    }

    #[ORM\PrePersist] // Se déclenche juste avant le premier INSERT en BDD
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
        return $this->total_ht;
    }

    public function setTotalHt(float $total_ht): static
    {
        $this->total_ht = $total_ht;

        return $this;
    }

    public function getTotalTtc(): ?float
    {
        return $this->total_ttc;
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
}
