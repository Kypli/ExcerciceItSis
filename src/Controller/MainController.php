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
		// Initialise
		$em = $this->getDoctrine()->getManager();

		// User
		$user = $em->getRepository('App:User')
			->findByName('user1')[0];

		// Récupère les lignes du mois en cours
		$lignesCurrentMonth = $em->getRepository('App:Ligne')
			->lignesCurrentMonth($user->getId());

		return $this->render('main/index.html.twig', [
			'utilisateur' => $user->getName(),
			'lignesCurrentMonth' => $this->lignesPerCommande($lignesCurrentMonth),
			'graphsByType' => $em->getRepository('App:Type')->graphsByType($user->getId()),
			'controller_name' => 'MainController',
		]);
	}

	/**
	 * 
	 */
	public function lignesPerCommande($list): array
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
