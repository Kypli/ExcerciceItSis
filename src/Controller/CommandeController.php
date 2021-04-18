<?php

namespace App\Controller;

use App\Entity\Ligne;
use App\Entity\Commande;
use App\Form\CommandeType;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * @Route("/commande")
 */
class CommandeController extends AbstractController
{
	/**
	 * @Route("/new", name="commande_new", methods={"GET","POST"})
	 */
	public function new(Request $request): Response
	{
		$commande = new Commande();

		// Initialise la première ligne
		$ligne = new Ligne();
		$ligne->setQuantite(1);
		$commande->getLignes()->add($ligne);

		// Formulaire
		$form = $this->createForm(CommandeType::class, $commande);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {

			// User
			$user = $this->getDoctrine()->getManager()->getRepository('App:User')
				->findByName('user1')[0];
			$commande->setUser($user);

			// Lignes
			$lignes = $form->getData()->getLignes();
			foreach ($lignes as $ligne) {
				$ligne->setCommande($commande);
			}

			$em = $this->getDoctrine()->getManager();
			$em->persist($commande);
			$em->flush();

			return $this->redirectToRoute('main');
		}

		return $this->render('commande/new.html.twig', [
			'commande' => $commande,
			'form' => $form->createView(),
		]);
	}

	/**
	 * @Route("/{id}/edit", name="commande_edit", methods={"GET","POST"})
	 */
	public function edit($id, Request $request): Response
	{
		// Initialise
		$em = $this->getDoctrine()->getManager();

		// Erreur d'id
		if (null === $commande = $em->getRepository(Commande::class)->find($id)) {
			throw $this->createNotFoundException('Pas de commande pour cet id "'.$id.'"');
		}

		// Récupère les lignes de la commande
		$originalLignes = new ArrayCollection();
		foreach ($commande->getLignes() as $ligne) {
			$originalLignes->add($ligne);
		}

		// Formulaire
		$editForm = $this->createForm(CommandeType::class, $commande);
		$editForm->handleRequest($request);

		if ($editForm->isSubmitted() && $editForm->isValid()) {

			// Retire les lignes qui ne sont plus dans la commande
			foreach ($originalLignes as $ligne) {
				if (false === $commande->getLignes()->contains($ligne)) {
					$em->remove($ligne);
				}
			}

			// Enregistre les lignes
			$lignes = $editForm->getData()->getLignes();
			foreach ($lignes as $ligne) {
				$ligne->setCommande($commande);
				$em->persist($ligne);
			}

			$em->persist($commande);
			$em->flush();

			return $this->redirectToRoute('main');
		}

		return $this->render('commande/edit.html.twig', [
			'id' => $id,
			'commande' => $commande,
			'form' => $editForm->createView(),
		]);
	}

	/**
	 * @Route("/{id}", name="commande_delete")
	 */
	public function delete(Request $request, Commande $commande): Response
	{
		if ($this->isCsrfTokenValid('delete'.$commande->getId(), $request->request->get('_token'))) {
			$em = $this->getDoctrine()->getManager();

			$lignes = $commande->getLignes();
			foreach ($lignes as $ligne) {
				$commande->removeLigne($ligne);
				$em->remove($ligne);
			}
			$em->remove($commande);
			$em->flush();
		}

		return $this->redirectToRoute('main');
	}

	/**
	 * @Route("/ajax/commande/produits", name="ajax_produits_options_by_type")
	 * Requête ajax pour récupérer les produits selon le type
	 */
	public function produitsOptionsByTypeAction(Request $request)
	{
		// Requête AJAX uniquement
		if (!$request->isXmlHttpRequest()){
			//throw new HttpException('500', 'Requête ajax uniquement');
		}

		$produits = $this->getDoctrine()->getManager()->getRepository('App:Produit')
			->produitParType($request->query->get('idtype'));

		$produits = $this->renameProduits($produits);

		return new JsonResponse($produits);
	}

	/**
	 * Retourne les produits en array avec l'id en clé et le name en valeur et en y intégrant le prix
	 */
	public function renameProduits($list)
	{
		$datas = [];
		foreach ($list as $key => $value){
			$datas[$value['id']] = ucfirst($value['name']).' ('.$value['price']."€ l'unité)";
		}

		return $datas;
	}

	/**
	 * @Route("/ajax/typeByProduct", name="ajax_type_by_product")
	 * Renvoie le type selon le produit
	 */
	public function typeByProduitAction(Request $request)
	{
		// Requête AJAX uniquement
		if (!$request->isXmlHttpRequest()){
			throw new HttpException('500', 'Requête ajax uniquement');
		}

		$type = $this->getDoctrine()->getManager()->getRepository('App:Produit')
			->typeByProduit($request->query->get('idProduit'));

		return new JsonResponse($type[0]['id']);
	}
}
