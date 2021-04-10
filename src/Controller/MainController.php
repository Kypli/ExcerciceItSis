<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
	/**
	 * @Route("/", name="main")
	 */
	public function index(): Response
	{
		$em = $this->getDoctrine()->getManager();
		$user = $em->getRepository('App:User')
			->findByName('user1')[0];

		// Récupère les datas du mois en cours
		$lignesCurrentMonth = $em->getRepository('App:Ligne')
			->lignesCurrentMonth($user->getId());

		// Trie les datas en 2 tableaux
		$dataCommandeQuantiteByType = $this->graphByType($lignesCurrentMonth);

		// Rajoute le nombre de commandes pour le tableau
		$lignesCurrentMonth = $this->numberCommande($lignesCurrentMonth);

		return $this->render('main/index.html.twig', [
			'utilisateur' => $user->getName(),
			'lignesCurrentMonth' => $lignesCurrentMonth,
			'graphTypeQuantite' => $dataCommandeQuantiteByType['quantite'],
			'graphTypePrice' => $dataCommandeQuantiteByType['prix'],
			'controller_name' => 'MainController',
		]);
	}

	public function graphByType($list): array
	{
		$datas = [
			'quantite' => [],
			'prix' => [],
		];

		foreach ($list as $value){

			// Centralisation des commandes
			if (!array_key_exists($value['Commande'], $datas['quantite'])){
				$datas['quantite'][$value['Commande']] = [];
				$datas['prix'][$value['Commande']] = [];
			}

			// Quantité
			if (array_key_exists($value['Type'], $datas['quantite'][$value['Commande']])){
				$datas['quantite'][$value['Commande']][$value['Type']] += $value['quantite'];
				$datas['prix'][$value['Commande']][$value['Type']] += $value['quantite']*$value['Prix'];

			} else {
				$datas['quantite'][$value['Commande']][$value['Type']] = $value['quantite'];
				$datas['prix'][$value['Commande']][$value['Type']] = $value['quantite']*$value['Prix'];
			}
		}

		return $datas;
	}


	public function numberCommande($list): array
	{
		$commande = [];
		foreach ($list as $key => $value){

			if (array_key_exists($value['Commande'], $commande)){
				$commande[$value['Commande']]++;
			} else {
				$commande[$value['Commande']] = 1;
			}
		}

		foreach ($list as $key => $value){
			if (array_key_exists($value['Commande'], $commande)){
				$list[$key]['number'] = $commande[$value['Commande']];
				unset($commande[$value['Commande']]);
			}
		}

		return $list;
	}
}
