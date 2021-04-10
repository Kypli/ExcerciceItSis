<?php

namespace App\DataFixtures;

use App\Entity\Type;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TypeFixtures extends Fixture
{
	public function load(ObjectManager $manager)
	{
		$datas = [
			'fruit',
			'legume',
			'conserve',
			'poisson',
			'viande',
			'boulangerie',
		];

		foreach ($datas as $data){
			$entite = new Type();
			$entite->setLibelle($data);
			$this->addReference($data, $entite);

			$manager->persist($entite);
		}

		$manager->flush();
	}
}
