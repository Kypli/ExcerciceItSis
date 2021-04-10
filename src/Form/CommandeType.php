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
				'type',
				EntityType::class,
				[
					'class' => Type::class,
					'placeholder' => 'Sélectionner un type',
					'label' => 'Type',
					'mapped' => false,
					'required' => false,
					'query_builder' => function(TypeRepository $entityRepository)
					{
						return $entityRepository->createQueryBuilder('t')
							->orderBy('t.libelle')
						;
					},
				]
			)
			->add(
				'produit',
				EntityType::class,
				[
					'class' => Produit::class,
					'placeholder' => 'Sélectionner un produit',
					'mapped' => false,
					'required' => false,
					'query_builder' => function(ProduitRepository $entityRepository)
					{
						return $entityRepository->createQueryBuilder('p')
							->orderBy('p.name')
						;
					},
				]
			)
			->add(
				'quantite',
				IntegerType::class,
				[
					'label' => 'Quantité',
					'mapped' => false,
					'required' => false,
					'attr' => [
						'title' => "Sélectionner une quantité",
						'min' => 1,
						'max' => 50,
						'value' => 1,
					],
				]
			)
			->add(
				'commentaire',
				TextType::class,
				[
					'label' => "Commentaire",
					'mapped' => false,
					'required' => false,
					'attr' => [
						'placeholder' => "Commentaire de la ligne",
						'title' => "Commentaire",
						//'class' => "inputTexte-nni",
					]
				]
			)
			->add(
				'test',
				HiddenType::class,
				[
					'attr' => [
						'value' => '',
					],
					'mapped' => false,
				]
			)
		;
	}

	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults([
			'data_class' => Commande::class,
		]);
	}
}
