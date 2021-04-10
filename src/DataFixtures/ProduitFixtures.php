<?php

namespace App\DataFixtures;

use App\Entity\Produit;
use App\DataFixtures\TypeFixtures;

use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class ProduitFixtures extends Fixture implements DependentFixtureInterface
{
	public function load(ObjectManager $manager)
	{
		$datas = [
			'fruit' => [
				'pomme',
				'banane',
				'orange',
				'fraise',
				'poire',
			],
			'legume' => [
				'courge',
				'concombre',
				'radis',
				'carotte',
				'choux-fleur',
				'poireaux',
				'celeri',
			],
			'conserve' => [
				'haricots',
				'macédoine',
				'maïs',
				'thon',
				'fayots',
			],
			'poisson' => [
				'truite',
				'saumon',
				'colin',
				'hareng',
				'cabillaud',
			],
			'viande' => [
				'steack',
				'poulet',
				'lapin',
				'boeuf',
				'canard',
				'agneau',
			],
			'boulangerie' => [
				'pain',
				'gateau',
				'croissant',
				'sandwich',
				'baguette',
				'financier',
			],
		];

		foreach ($datas as $key => $denrees){
			foreach ($denrees as $key2 => $denree){

				$entite = new Produit();
				$entite->setName($denree);
				$price = rand(1, 20);
				$entite->setPrice($price);
				$entite->setType($this->getReference($key));

				$this->addReference($denree, $entite);

				$manager->persist($entite);
			}
		}

		$manager->flush();
	}

	public function getDependencies()
	{
		return [
			TypeFixtures::class,
		];
	}
}
