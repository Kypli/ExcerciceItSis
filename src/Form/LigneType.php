<?php

namespace App\Form;

use App\Entity\Ligne;
use App\Entity\Type;
use App\Entity\Produit;
use App\Repository\TypeRepository;
use App\Repository\ProduitRepository;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class LigneType extends AbstractType
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
					'required' => true,
					'mapped' => false,
					'attr' => [
						'class' => "selectType",
					],
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
					'required' => true,
					'attr' => [
						'class' => "selectProduit",
					],
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
					'required' => true,
					'attr' => [
						'title' => "Sélectionner une quantité",
						'placeholder' => "Quantité de la ligne",
						'min' => 1,
						'max' => 50,
						'value' => 1,
					],
				]
			)
			->add(
				'comment',
				TextType::class,
				[
					'label' => "Commentaire",
					'required' => true,
					'attr' => [
						'title' => "Commentaire",
						'placeholder' => "Commentaire de la ligne",
					]
				]
			)
		;
	}

	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults([
			'data_class' => Ligne::class,
		]);
	}
}
