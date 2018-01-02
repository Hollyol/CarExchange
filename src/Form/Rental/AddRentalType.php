<?php

namespace App\Form\Rental;

use App\Form\Rental\AbstractRentalType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddRentalType extends AbstractRentalType
{
	/**
	 * {@inheritdoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
	}

	/**
	 * {@inheritdoc}
	 */
	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults(array(
			'data_class' => 'App\Entity\Rental',
			'translation_domain' => 'addRental',
		));
	}

	/**
	 * {@inheritdoc}
	 */
	public function getBlockPrefix()
	{
		return 'app_addRental';
	}

	/**
	 * {@inheritdoc}
	 */
	public function getParent()
	{
		return AbstractRentalType::class;
	}
}
