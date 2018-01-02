<?php

namespace App\Form\Car;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use App\Form\Car\AbstractCarType;

class AddCarType extends AbstractCarType
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
            'data_class' => 'App\Entity\Car',
			'translation_domain' => 'addCar',
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'app_addCar';
    }

	/**
	 * {@inheritdoc}
	 */
	public function getParent()
	{
		return AbstractCarType::class;
	}
}
