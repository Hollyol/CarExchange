<?php

namespace App\Form\Advert;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;

use App\Form\Car\AbstractCarType;
use App\Form\Location\AbstractLocationType;
use App\Form\Billing\BillingType;

use App\Form\Advert\EventListener\AdvertFormatingSubscriber;

class AbstractAdvertType extends AbstractType
{
	/**
	 * {@inheritdoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$futureDate = new GreaterThanOrEqual(array(
			'value' => new \Datetime('today'),
			'message' => 'date.in_past',
		));

		$builder
			->add('title', TextType::class, array(
				'required' => false,
			))
			->add('beginDate', DateType::class, array(
				'constraints' => $futureDate,
			))
			->add('endDate', DateType::class, array(
				'constraints' => $futureDate,
			))
			->add('car', AbstractCarType::class)
			->add('location', AbstractLocationType::class)
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
