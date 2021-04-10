<?php

namespace App\Entity;

use App\Repository\CommandeRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass=CommandeRepository::class)
 */
class Commande
{
	/**
	 * @ORM\ManyToOne(targetEntity="User", inversedBy="commandes")
	 */
	private $user;

	/**
	 * @ORM\OneToMany(targetEntity="Ligne", mappedBy="commande")
	 */
	private $lignes;


	/**
	 * @ORM\Id
	 * @ORM\GeneratedValue
	 * @ORM\Column(type="integer")
	 */
	private $id;


	/**
	 * @var date
	 *
	 * @ORM\Column(name="date", type="date")
	 */
	private $date;


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

	/**
	 * Set date.
	 *
	 * @param date $date
	 *
	 * @return Commande
	 */
	public function setDate($date)
	{
		$this->date = $date;

		return $this;
	}

	/**
	 * Get date.
	 *
	 * @return date
	 */
	public function getDate()
	{
		return $this->date;
	}


	/**
	 * ManyToOne
	 */

	/**
	 * Set user
	 *
	 * @param string $user
	 * @return Commande
	 */
	public function setUser($user)
	{
		$this->user = $user;

		return $this;
	}

	/**
	 * Get user
	 *
	 * @return string
	 */
	public function getUser()
	{
		return $this->user;
	}

	/**
	 * OneToMany
	 */

	/**
	 * Add lignes
	 *
	 * @param \App\Entity\Ligne $ligne
	 * @return Commande
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
}
