<?php

namespace App\Form\Car;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use App\Form\Car\AbstractCarType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

use App\Service\Form\OptionsSetter;

class SearchCarType extends AbstractCarType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$optionsSetter = new OptionsSetter();

		$sitsOptions = array(
			'required' => false,
			'constraints' => null,
		);
		$optionsSetter->setOptions($builder, 'sits', $sitsOptions, IntegerType::class);

		$builder
			->remove('brand')
			->remove('model')
			->remove('description')
		;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'App\Entity\Car',
			'translation_domain' => 'searchCar',
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'app_searchCar';
    }

	/**
	 * {@inheritdoc}
	 */
	public function getParent()
	{
		return AbstractCarType::class;
	}
}
