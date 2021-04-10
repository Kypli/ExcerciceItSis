<?php

namespace App\DataFixtures;

use App\Entity\Commande;
use App\DataFixtures\UserFixtures;

use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class CommandeFixtures extends Fixture implements DependentFixtureInterface
{
	public function load(ObjectManager $manager)
	{

		for ($i = 1; $i <= 10; $i++){

			$date = new \Datetime('now');
			$newDate = rand(-10, 0);
			$date = date_modify($date, $newDate.' day');

			$entite = new Commande();
			$entite->setDate($date);

			if ($i <= 6){
				$user = 'user';

			} elseif ($i <= 9){
				$user = 'admin';

			} else {
				$user = 'superAdmin';
			}

			$entite->setUser($this->getReference($user));
			$this->addReference('commande'.$i, $entite);

			$manager->persist($entite);
		}

		$manager->flush();
	}

	public function getDependencies() : array
	{
		return [
			UserFixtures::class,
		];
	}
}
