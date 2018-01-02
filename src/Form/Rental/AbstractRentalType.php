<?php

namespace App\Form\Rental;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\Callback;

class AbstractRentalType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
		$positiveDuration = new Callback(array(
			'callback' => function ($endDate, ExecutionContextInterface $context) {
				if ($endDate < $context->getRoot()->getData()->getBeginDate()){
					$context->buildViolation('rental.negative_duration')
						->atPath('endDate')
						->addViolation()
					;
				}
			}
		));

		$futureDate = new GreaterThanOrEqual(array("value" => "today",
			"message" => "date.in_past"));

		$builder
			->add('beginDate', DateType::class,
				array(
					'constraints' => array(
						new NotBlank,
						$futureDate
					),
				))
			->add('endDate', DateType::class,
				array(
					'constraints' => array(
						new NotBlank,
						$positiveDuration,
					),
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
