<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User
{

    /**
     * @ORM\OneToMany(targetEntity="Commande", mappedBy="user")
     */
    private $commandes;

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
     * @ORM\Column(type="string", length=255)
     */
    private $acces;


    /**
     * Construct
     */
    public function __construct() {
        $this->commandes = new ArrayCollection();
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

    public function getAcces(): ?string
    {
        return $this->acces;
    }

    public function setAcces(string $acces): self
    {
        $this->acces = $acces;

        return $this;
    }


    /**
     * OneToMany
     */

    /**
     * Add commandes
     *
     * @param \App\Entity\Commande $commande
     * @return User
     */

    public function addCommande(\App\Entity\Commande $commande)
    {
        $this->commandes[] = $commande;

        return $this;
    }

    /**
     * Remove commandes
     *
     * @param \App\Entity\Commande $commande
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeCommande(\App\Entity\Commande $commande)
    {
        return $this->commandes->removeElement($commande);
    }

    /**
     * Get commandes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCommandes()
    {
        return $this->commandes;
    }

    public function __toString() {
        return $this->name;
    }
}
