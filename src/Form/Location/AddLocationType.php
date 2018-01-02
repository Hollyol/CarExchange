<?php

namespace App\Form\Location;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use App\Form\Location\AbstractLocationType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class AddLocationType extends AbstractLocationType
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
			'data_class' => 'App\Entity\Location',
			'translation_domain' => 'addLocation',
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'app_addLocation';
    }

	/**
	 * {@inheritdoc}
	 */
	public function getParent()
	{
		return AbstractLocationType::class;
	}
}
