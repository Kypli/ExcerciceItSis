<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ProduitRepository;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass=ProduitRepository::class)
 */
class Produit
{

    /**
     * @ORM\ManyToOne(targetEntity="Type", inversedBy="produits")
     */
    private $type;

    /**
     * @ORM\OneToMany(targetEntity="Ligne", mappedBy="produit")
     */
    private $lignes;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="integer")
     */
    private $price;


    /**
     * Construct
     */
    public function __construct() {
        $this->lignes = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    /**
     * ManyToOne
     */

    /**
     * Set type
     *
     * @param string $type
     * @return Produit
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }


    /**
     * OneToMany
     */

    /**
     * Add lignes
     *
     * @param \App\Entity\Ligne $ligne
     * @return Produit
     */

    public function addLigne(\App\Entity\Ligne $ligne)
    {
        $this->lignes[] = $ligne;

        return $this;
    }

    /**
     * Remove lignes
     *
     * @param \App\Entity\Ligne $ligne
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeLigne(\App\Entity\Ligne $ligne)
    {
        return $this->lignes->removeElement($ligne);
    }

    /**
     * Get lignes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLignes()
    {
        return $this->lignes;
    }

    public function __toString() {
        return $this->name;
    }
}
