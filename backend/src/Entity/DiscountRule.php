<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class DiscountRule
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 32)]
    private string $type = 'PERCENT';

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    private string $value = '0.00';


    public function getId(): ?int { return $this->id; }

    public function getType(): string { return $this->type; }
    public function setType(string $type): self { $this->type = $type; return $this; }

    public function getValue(): string { return $this->value; }
    public function setValue(string $value): self { $this->value = $value; return $this; }
}
