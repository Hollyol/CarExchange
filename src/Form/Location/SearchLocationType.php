<?php

namespace App\Form\Location;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use App\Form\Location\AbstractLocationType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

use App\Service\Form\OptionsSetter;

class SearchLocationType extends AbstractLocationType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$optionsSetter = new OptionsSetter();

		$townOptions = array(
			'required' => false
		);
		$optionsSetter->setOptions($builder, 'town', $townOptions, TextType::class);

		$stateOptions = array(
			'required' => false
		);
		$optionsSetter->setOptions($builder, 'state', $stateOptions, TextType::class);
	}
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'App\Entity\Location'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'app_searchLocation';
    }

	/**
	 * {@inheritdoc}
	 */
	public function getParent()
	{
		return AbstractLocationType::class;
	}
}
