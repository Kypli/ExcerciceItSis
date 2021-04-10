<?php

namespace App\Controller;

use App\Entity\Ligne;
use App\Entity\Commande;
use App\Form\CommandeType;
use App\Repository\CommandeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * @Route("/commande")
 */
class CommandeController extends AbstractController
{
    /**
     * @Route("/", name="commande_index", methods={"GET"})
     */
    public function index(CommandeRepository $commandeRepository): Response
    {
        return $this->render('commande/index.html.twig', [
            'commandes' => $commandeRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="commande_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $commande = new Commande();

        $form = $this->createForm(CommandeType::class, $commande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $test = $this->gestionForm($request->request->get('commande')['test'], $commande);

            if ($test === true){
                $this->redirectToRoute('main');
            }
        }

        return $this->render('commande/new.html.twig', [
            'commande' => $commande,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="commande_show", methods={"GET"})
     */
    public function show(Commande $commande): Response
    {
        return $this->render('commande/show.html.twig', [
            'commande' => $commande,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="commande_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Commande $commande): Response
    {

        $idCommande = $commande->getId();
        $lignes = $this->getDoctrine()->getManager()->getRepository('App:Ligne')
            ->lignesByCommande($idCommande);

        $form = $this->createForm(CommandeType::class, $commande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $test = $this->gestionForm($request->request->get('commande')['test'], $commande);

            if ($test === true){
                $this->redirectToRoute('main');
            }
        }

        return $this->render('commande/new.html.twig', [
            'commande' => $commande,
            'lignes' => $lignes,
            'idCommande' => $idCommande,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="commande_delete", methods={"POST"})
     */
    public function delete(Request $request, Commande $commande): Response
    {
        if ($this->isCsrfTokenValid('delete'.$commande->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($commande);
            $entityManager->flush();
        }

        return $this->redirectToRoute('commande_index');
    }

	/**
	* @Route("/ajax/commande/produits", name="ajax_produits_options_by_type")
	* Requête ajax pour récupérer les produits selon le type
	*/
	public function produitsOptionsByTypeAction(Request $request)
	{

		// Requête AJAX uniquement
		if (!$request->isXmlHttpRequest()){
			throw new HttpException('500', 'Requête ajax uniquement');
		}

		$produits = $this->getDoctrine()->getManager()->getRepository('App:Produit')
			->produitParType($request->query->get('idtype'));

		$produits = $this->ArrayProduits($produits);

		return new JsonResponse($produits);
	}

    /**
    * @Route("/ajax/lignes/commande", name="ajax_lignes_by_commande")
    * Requête ajax pour récupérer les lignes selon la commande
    */
    public function lignesByCommandeAction(Request $request)
    {

        // Requête AJAX uniquement
        if (!$request->isXmlHttpRequest()){
            throw new HttpException('500', 'Requête ajax uniquement');
        }
        $lignes = $this->getDoctrine()->getManager()->getRepository('App:Ligne')
            ->lignesByCommande($request->query->get('idcommande'));

        return new JsonResponse($lignes);
    }

	/**
	* retourne les produits en array avec l'id en clé et le name en valeur
	*/
	public function ArrayProduits($list)
	{
		$datas = [];
		foreach ($list as $key => $value){
			$datas[$value['id']] = ucfirst($value['name']).' ('.$value['price']."€ l'unité)";
		}

		return $datas;
	}

    /**
    * retourne les produits en array avec l'id en clé et le name en valeur
    */
    public function gestionForm($request, $commande)
    {
        // Initialise
        $error = false;
        $em = $this->getDoctrine()->getManager();

        // Date du jour
        $date = new \Datetime('now');
        $commande->setDate($date);

        // User1
        $user = $em->getRepository('App:User')
            ->findByName('user1')[0];
        $commande->setUser($user);

        // Lignes

        // Au moins une ligne
        if (!empty($request)){

            $lignes = explode('|', $request);

            foreach ($lignes as $key => $ligne){
                $ligne = trim($ligne);

                if (!empty($ligne)){

                    $ligne = explode(',', $ligne);

                    $id = explode(':', $ligne['0']);
                    $id = trim($id[1]);

                    $idProduit = explode(':', $ligne['1']);
                    $idProduit = trim($idProduit[1]);

                    $quantite = explode(':', $ligne['2']);
                    $quantite = trim($quantite[1]);

                    $commentaire = explode(':', $ligne['3']);
                    $commentaire = trim($commentaire[1]);

                    $exist = explode(':', $ligne['4']);
                    $exist = trim($exist[1]);


                    if ($exist == 'oui'){
                        $ligne = $this->getDoctrine()->getManager()->getRepository('App:Ligne')
                            ->findById($id)[0];
                    } else {
                        $ligne = new Ligne();
                    }

                    $produit = $this->getDoctrine()->getManager()->getRepository('App:Produit')
                        ->findById($idProduit)[0];

                    $ligne->setQuantite($quantite);
                    $ligne->setComment($commentaire);
                    $ligne->setProduit($produit);
                    $ligne->setCommande($commande);
                    $em->persist($ligne);
                    $commande->addLigne($ligne);
                    $em->persist($commande);
                }
            }
            $em->flush();

           return true;
        }

        return false;
    }
}
