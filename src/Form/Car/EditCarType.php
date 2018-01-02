<?php

namespace App\Form\Car;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use App\Form\Car\AbstractCarType;

class EditCarType extends AbstractCarType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->remove('brand')
			->remove('model')
			->remove('sits')
			->remove('fuel')
		;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
			'data_class' => 'App\Entity\Car',
			'translation_domain' => 'editCar',
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'xp_carbundle_editCar';
    }

	/**
	 * {@inheritdoc}
	 */
	public function getParent()
	{
		return AbstractCarType::class;
	}
}
