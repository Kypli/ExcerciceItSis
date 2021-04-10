<?php

namespace App\DataFixtures;

use App\Entity\Ligne;
use App\DataFixtures\ProduitFixtures;
use App\DataFixtures\CommandeFixtures;

use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class LigneFixtures extends Fixture implements DependentFixtureInterface
{
	public function load(ObjectManager $manager)
	{
		$produits = [
			'pomme',
			'banane',
			'orange',
			'fraise',
			'poire',
			'courge',
			'concombre',
			'radis',
			'carotte',
			'choux-fleur',
			'poireaux',
			'haricots',
			'celeri',
			'macédoine',
			'maïs',
			'thon',
			'fayots',
			'truite',
			'saumon',
			'colin',
			'hareng',
			'thon',
			'cabillaud',
			'steack',
			'poulet',
			'boeuf',
			'lapin',
			'canard',
			'agneau',
			'pain',
			'gateau',
			'croissant',
			'sandwich',
			'baguette',
			'financier',
		];

		$ii = 1;
		$commande = 1;

		for ($i = 80; $i >= 1; $i--){

			if ($i == 65){
				$commande++;

			} elseif ($i == 60){
				$commande++;

			} elseif ($i == 52){
				$commande++;

			} elseif ($i == 48){
				$commande++;

			} elseif ($i == 31){
				$commande++;

			} elseif ($i == 26){
				$commande++;

			} elseif ($i == 14){
				$commande++;

			} elseif ($i == 6){
				$commande++;

			} elseif ($i == 1){
				$commande++;
			}


			$produit = rand(0, 34);
			$quantite = rand(1, 10);
			$entite = new Ligne();
			$entite->setQuantite($quantite);
			$entite->setComment('Commentaire '.$ii);
			$ii++;
			$entite->setProduit($this->getReference($produits[$produit]));
			$entite->setCommande($this->getReference('commande'.$commande));
			$this->addReference('ligne'.$i, $entite);

			$manager->persist($entite);

		}

		$manager->flush();
	}

	public function getDependencies()
	{
		return [
			ProduitFixtures::class,
			CommandeFixtures::class,
		];
	}
}
