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

		return $this->render('main/index.html.twig', [
			'utilisateur' => $user->getName(),
			'numberLignesByCommandes' => $em->getRepository('App:Ligne')->numberLignesByCommandes($user->getId()),
			'lignesCurrentMonth' => $em->getRepository('App:Ligne')->lignesCurrentMonth($user->getId()),
			'graphsByTypeJson' => json_encode($em->getRepository('App:Type')->graphsByType($user->getId())),
			'controller_name' => 'MainController',
		]);
	}
}
