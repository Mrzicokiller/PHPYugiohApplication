<?php

namespace App\Entity;

use App\Repository\CardAttributeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CardAttributeRepository::class)
 */
class CardAttribute
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $attributeName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $attributeImagePath;

    /**
     * @ORM\OneToMany(targetEntity=Card::class, mappedBy="Attribute")
     */
    private $cards;

    public function __construct()
    {
        $this->cards = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAttributeName(): ?string
    {
        return $this->attributeName;
    }

    public function setAttributeName(string $attributeName): self
    {
        $this->attributeName = $attributeName;

        return $this;
    }

    public function getAttributeImagePath(): ?string
    {
        return $this->attributeImagePath;
    }

    public function setAttributeImagePath(?string $attributeImagePath): self
    {
        $this->attributeImagePath = $attributeImagePath;

        return $this;
    }

    //manual add preventing Uncaught Error: Object of class Proxies\__CG__\App\Entity\CardAttribute could not be converted to string
    public function __toString()
    {
        return (string)$this->id;
    }

    /**
     * @return Collection|Card[]
     */
    public function getCards(): Collection
    {
        return $this->cards;
    }

    public function addCard(Card $card): self
    {
        if (!$this->cards->contains($card)) {
            $this->cards[] = $card;
            $card->setAttribute($this);
        }

        return $this;
    }

    public function removeCard(Card $card): self
    {
        if ($this->cards->removeElement($card)) {
            // set the owning side to null (unless already changed)
            if ($card->getAttribute() === $this) {
                $card->setAttribute(null);
            }
        }

        return $this;
    }
}
