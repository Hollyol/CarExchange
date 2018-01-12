<?php

namespace App\Form\Rental;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;

class AbstractRentalType extends AbstractType
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
			->add('beginDate', DateType::class, array(
				'constraints' => $futureDate,
			))
			->add('endDate', DateType::class, array(
				'constraints' => $futureDate,
			))
			->add('submit', SubmitType::class)
		;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
			'data_class' => 'App\Entity\Rental',
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'app_abstractRental';
    }
}
