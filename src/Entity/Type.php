<?php

namespace App\Entity;

use App\Repository\TypeRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass=TypeRepository::class)
 */
class Type
{

    /**
     * @ORM\OneToMany(targetEntity="Produit", mappedBy="type")
     */
    private $produits;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $libelle;


    /**
     * Construct
     */
    public function __construct() {
        $this->produits = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }


    /**
     * OneToMany
     */

    /**
     * Add produits
     *
     * @param \App\Entity\Produit $produit
     * @return Type
     */

    public function addProduit(\App\Entity\Produit $produit)
    {
        $this->produits[] = $produit;

        return $this;
    }

    /**
     * Remove produits
     *
     * @param \App\Entity\Produit $produit
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeProduit(\App\Entity\Produit $produit)
    {
        return $this->produits->removeElement($produit);
    }

    /**
     * Get produits
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProduits()
    {
        return $this->produits;
    }

    public function __toString() {
        return $this->libelle;
    }
}
