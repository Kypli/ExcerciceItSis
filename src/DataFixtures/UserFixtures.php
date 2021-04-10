<?php

namespace App\DataFixtures;

use App\Entity\User;

use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class UserFixtures extends Fixture
{
	public function load(ObjectManager $manager)
	{
		$datas = [
			1 => 'user',
			2 => 'admin',
			3 => 'superAdmin',
		];

		foreach ($datas as $key => $data){
			$entite = new User();
			$entite->setAcces($data);
			$entite->setName('user'.$key);
			$this->addReference($data, $entite);

			$manager->persist($entite);
		}

		$manager->flush();
	}
}
