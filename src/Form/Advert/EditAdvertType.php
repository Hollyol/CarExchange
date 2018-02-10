<?php

namespace App\Form\Advert;

use App\Form\Advert\AbstractAdvertType;
use App\Form\Car\EditCarType;
use App\Form\Location\AddLocationType;
use App\Service\Form\OptionsSetter;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class EditAdvertType extends AbstractAdvertType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$notCollidingRentalBeginDate = new Callback(
			function (\Datetime $beginDate, ExecutionContextInterface $context, $payload)
			{
				if ($context->getRoot()->get('hiddenBeginDate')->getData() == '')
					return;

				if (($beginDate > new \Datetime($context->getRoot()->get('hiddenBeginDate')->getData()))
					AND (new \Datetime($context->getRoot()->get('firstRentalBeginDate')->getData()) < $beginDate)) {
					$context->addViolation('advert_edit.rentals_collision');
				}
			}
		);

		$notCollidingRentalEndDate = new Callback(
			function (\Datetime $endDate, ExecutionContextInterface $context, $payload)
			{
				if ($context->getRoot()->get('hiddenEndDate')->getData() == '')
					return;

				if (($endDate < new \Datetime($context->getRoot()->get('hiddenEndDate')->getData()))
					AND ($endDate < new \Datetime($context->getRoot()->get('lastRentalEndDate')->getData()))) {
					$context->addViolation('advert_edit.rentals_collision');
				}
			}
		);

		$optionsSetter = new OptionsSetter();
		$optionsSetter->setOptions($builder, 'car', ['translation_domain' => 'editCar'], EditCarType::class);
		$optionsSetter->setOptions($builder, 'location', ['translation_domain' => 'addLocation'], AddLocationType::class);

		$builder
			->add('beginDate', DateType::class, array(
				'constraints' => $notCollidingRentalBeginDate,
			))
			->add('endDate', DateType::class, array(
				'constraints' => $notCollidingRentalEndDate,
			))
			->add('lastRentalEndDate', HiddenType::class, array(
				'mapped' => false,
			))
			->add('firstRentalBeginDate', HiddenType::class, array(
				'mapped' => false,
			))
			->add('hiddenBeginDate', HiddenType::class, array(
				'mapped' => false,
			))
			->add('hiddenEndDate', HiddenType::class, array(
				'mapped' => false,
			))
			;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
			'data_class' => 'App\Entity\Advert',
			'translation_domain' => 'editAdvert'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'xp_carbundle_editAdvert';
    }

	/**
	 * {@inheritdoc}
	 */
	public function getParent()
	{
		return AbstractAdvertType::class;
	}

}
