<?php

namespace App\Entity;

use App\Repository\CardRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CardRepository::class)
 */
class Card
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;


    /**
     * @ORM\Column(type="string", length=255)
     */
    private $cardName;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $attribute;

    /**
     * @ORM\Column(type="string", length=4, nullable=true)
     */
    private $attackValue;

    /**
     * @ORM\Column(type="string", length=4, nullable=true)
     */
    private $defenseValue;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $collectionID;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $imgPath;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $cardDescription;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCardName(): ?string
    {
        return $this->cardName;
    }

    public function setCardName(string $cardName): self
    {
        $this->cardName = $cardName;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getAttribute(): ?string
    {
        return $this->attribute;
    }

    public function setAttribute(?string $attribute): self
    {
        $this->attribute = $attribute;

        return $this;
    }

    public function getAttackValue(): ?string
    {
        return $this->attackValue;
    }

    public function setAttackValue(?string $attackValue): self
    {
        $this->attackValue = $attackValue;

        return $this;
    }

    public function getDefenseValue(): ?string
    {
        return $this->defenseValue;
    }

    public function setDefenseValue(?string $defenseValue): self
    {
        $this->defenseValue = $defenseValue;

        return $this;
    }

    public function getCollectionID(): ?string
    {
        return $this->collectionID;
    }

    public function setCollectionID(string $collectionID): self
    {
        $this->collectionID = $collectionID;

        return $this;
    }

    public function getImgPath(): ?string
    {
        return $this->imgPath;
    }

    public function setImgPath(?string $imgPath): self
    {
        $this->imgPath = $imgPath;

        return $this;
    }

    public function getCardDescription(): ?string
    {
        return $this->cardDescription;
    }

    public function setCardDescription(?string $cardDescription): self
    {
        $this->cardDescription = $cardDescription;

        return $this;
    }
}
