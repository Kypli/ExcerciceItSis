<?php

namespace App\Repository;

use App\Entity\Ligne;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Ligne|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ligne|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ligne[]    findAll()
 * @method Ligne[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LigneRepository extends ServiceEntityRepository
{
	public function __construct(ManagerRegistry $registry)
	{
		parent::__construct($registry, Ligne::class);
	}

	/**
	 * Datas Current month by user
	 */
	public function lignesCurrentMonth($idUser){

		return $this->createQueryBuilder('l')
			->leftjoin('l.produit', 'p')
			->leftjoin('p.type', 't')
			->leftjoin('l.commande', 'c')
			->leftjoin('c.user', 'u')
			->where("c.user = :idUser")
			->andWhere('SUBSTRING(c.date,6,2) = :currentMonth')
			->setParameter('idUser', $idUser)
			->setParameter('currentMonth', date('m'))
			->select([
				"c.id as Commande",
				"t.libelle as Type",
				"p.name as Produit",
				"p.price as Prix",
				"l.quantite",
				"l.comment as Comment",
				"c.date",
			])
			->orderBy('c.date', 'DESC')
			->addOrderBy('c.id', 'DESC')
			->addOrderBy('t.libelle', 'DESC')
			->addOrderBy('p.name', 'DESC')
			->getQuery()
			->getResult()
		;
	}

	/**
	 * Datas Current month by user
	 */
	public function lignesByCommande($idCommande){

		$query = $this->createQueryBuilder('l')
			->leftjoin('l.produit', 'p')
			->leftjoin('p.type', 't')
			->where("l.commande = :idCommande")
			->setParameter('idCommande', $idCommande)
			->select([
				"t.libelle as type",
				"t.id as idtype",
				"p.name as produit",
				"p.id as idproduit",
				"p.price",
				"l.quantite",
				"l.comment as commentaire",
				"l.id",
			])
			->addOrderBy('l.id', 'DESC')
			->getQuery()
			->getResult()
		;
		$query = $this->ArrayPrice($query);

		return $query;
	}

    /**
    * Retourne la valeur total du prix
    */
    public function ArrayPrice($list)
    {
        foreach ($list as $key => $value){
            $list[$key]['totalPrice'] = $value['quantite'] * $value['price'];
        }

        return $list;
    }
}
