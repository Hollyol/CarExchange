<?php

namespace App\Form\Advert;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use App\Form\Car\AddCarType;
use App\Form\Location\AddLocationType;
use App\Form\Billing\BillingType;

use App\Form\Advert\EventListener\AdvertFormatingSubscriber;

class AbstractAdvertType extends AbstractType
{
	/**
	 * {@inheritdoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->add('title', TextType::class, array(
				'required' => false,
			))
			->add('beginDate', DateType::class)
			->add('endDate', DateType::class)
			->add('car', AddCarType::class)
			->add('location', AddLocationType::class)
			->add('billing', BillingType::class)
			->add('submit', SubmitType::class)

			->addEventSubscriber(new AdvertFormatingSubscriber())
		;
	}

	/**
	 * {@inheritdoc}
	 */
	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults(array(
			'data_class' => 'App\Entity\Advert',
		));
	}

	/**
	 * {@inheritdoc}
	 */
	public function getBlocPrefix()
	{
		return 'app_abstractAdvert';
	}
}
