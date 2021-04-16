<?php

namespace App\Repository;

use App\Entity\Type;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Type|null find($id, $lockMode = null, $lockVersion = null)
 * @method Type|null findOneBy(array $criteria, array $orderBy = null)
 * @method Type[]    findAll()
 * @method Type[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TypeRepository extends ServiceEntityRepository
{
	public function __construct(ManagerRegistry $registry)
	{
		parent::__construct($registry, Type::class);
	}

	/**
	 * Datas all Command By type with quantite
	 */
	public function graphsByType($idUser){

		return $this->createQueryBuilder('t')
			->leftjoin('t.produits', 'p')
			->leftjoin('p.lignes', 'l')
			->leftjoin('l.commande', 'c')
			->select(
				"t.libelle as type",
				"SUM(l.quantite) as quantite",
				"SUM(p.price * l.quantite) as price",
			)
			->groupBy('t.libelle')
			->where('c.id is not null')
			->andWhere("c.user = :idUser")
			->andWhere('SUBSTRING(c.date,6,2) = :currentMonth')
			->setParameter('idUser', $idUser)
			->setParameter('currentMonth', date('m'))
			->orderBy('t.libelle', 'ASC')
			->getQuery()
			->getResult()
		;
	}

	/**
	*	Je n'avais pas bien compris la finalité des graphs, au début, je pensais qu'il falalit ressortir un tableau de ce type
	*		datas = [
	*			type1 => [
	*				Commande n° X => [
	*					Quantite
	*					Prix
	*				],
	*				Commande n° X+1 => [
	*					Quantite
	*					Prix
	*				],
	*				...
	*			]
	*		]
	*
	*	Avec une requête pour chaque type en paramêtre et pour l'ensemble des commandes de tout les utilisateurs
	*	Mais pas du tout, je vous laisse en commentaires le résultat de ce graph non demandé :)
	 **/
	public function graphsObsolete($type){

		return $this->createQueryBuilder('t')
			->leftjoin('t.produits', 'p')
			->leftjoin('p.lignes', 'l')
			->leftjoin('l.commande', 'c')
			->select(
				"c.id as commande",
				"SUM(l.quantite) as quantite",
				"SUM(p.price * l.quantite) as price",
			)
			->groupBy('c.id')
			->where('t.libelle = :libelle')
			->andWhere('c.id is not null')
			->setParameter('libelle', $type)
			->orderBy('c.date', 'DESC')
			->addOrderBy('c.id', 'DESC')
			->addOrderBy('t.libelle', 'DESC')
			->addOrderBy('p.name', 'DESC')
			->getQuery()
			->getResult()
		;
	}
}
