<?php

namespace App\Form\Advert;

use App\Form\Advert\AbstractAdvertType;
use App\Form\Location\SearchLocationType;
use App\Form\Car\SearchCarType;

use App\Service\Form\OptionsSetter;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchAdvertType extends AbstractAdvertType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$optionsSetter = new OptionsSetter();

		$optionsSetter->setOptions($builder, 'car', ['translation_domain' => 'searchCar'], SearchCarType::class);
		$optionsSetter->setOptions($builder, 'location', ['translation_domain' => 'searchLocation'], SearchLocationType::class);
		
		$builder
			->remove('title')
			->remove('billing')
		;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
			'data_class' => 'App\Entity\Advert',
			'translation_domain' => 'searchAdvert',
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'app_searchAdvert';
    }

	/**
	 * {@inheritdoc}
	 */
	public function getParent()
	{
		return AbstractAdvertType::class;
	}
}
