<?php

namespace App\Repository;

use App\Entity\Produit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Produit|null find($id, $lockMode = null, $lockVersion = null)
 * @method Produit|null findOneBy(array $criteria, array $orderBy = null)
 * @method Produit[]    findAll()
 * @method Produit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProduitRepository extends ServiceEntityRepository
{
	public function __construct(ManagerRegistry $registry)
	{
		parent::__construct($registry, Produit::class);
	}

	/**
	 * Produits selon le type
	 */
	public function produitParType($idType){

		return $this->createQueryBuilder('p')
			->where("p.type = :idType")
			->setParameter('idType', $idType)
			->orderBy('p.name', 'DESC')
			->select([
				"p.id",
				"p.name",
				"p.price",
			])
			->getQuery()
			->getResult()
		;
	}

	/**
	 * Produits selon le type
	 */
	public function typebyProduit($idProduit){

		return $this->createQueryBuilder('p')
			->leftjoin('p.type', 't')
			->where("p.id = :idProduit")
			->setParameter('idProduit', $idProduit)
			->select([
				"t.id",
			])
			->getQuery()
			->getResult()
		;
	}
}
