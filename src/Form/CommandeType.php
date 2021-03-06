<?php

namespace App\Form;

use App\Entity\Commande;
use App\Entity\Type;
use App\Entity\Ligne;
use App\Entity\Produit;
use App\Repository\TypeRepository;
use App\Repository\LigneRepository;
use App\Repository\ProduitRepository;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommandeType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->add(
				'lignes',
				CollectionType::class,
				[
					'entry_type' => LigneType::class,
					'entry_options' => [
						'label' => false
					],
					'allow_add' => true,
					'allow_delete' => true,
				]
			)
		;
	}

	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults([
			'data_class' => Commande::class,
        	'by_reference' => false,
		]);
	}
}
