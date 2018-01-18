<?php

namespace App\Form\Advert;

use App\Form\Advert\AbstractAdvertType;
use App\Form\Car\AddCarType;
use App\Form\Location\AddLocationType;
use App\Form\Billing\BillingType;

use App\Service\Form\OptionsSetter;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddAdvertType extends AbstractAdvertType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$optionsSetter = new OptionsSetter();

		$optionsSetter->setOptions($builder, 'location', ['translation_domain' => 'addLocation'], AddLocationType::class);
		$optionsSetter->setOptions($builder, 'car', ['translation_domain' => 'addCar'], AddCarType::class);
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
			'data_class' => 'App\Entity\Advert',
			'translation_domain' => 'addAdvert',
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'app_addAdvert';
    }

	/**
	 * {@inheritdoc}
	 */
	public function getParent()
	{
		return AbstractAdvertType::class;
	}
}
