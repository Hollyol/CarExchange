<?php

namespace App\Form\Billing;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\CurrencyType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BillingType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
		$builder
			->add('currency', CurrencyType::class,
				array(
					'placeholder' => 'Euro',
					'empty_data' => 'EUR',
					'required' => false,
				)
			)
			->add('price', IntegerType::class)
			->add('timeBase', ChoiceType::class,
				array(
					'choices' => array(
						'hour' => 'hour',
						'day' => 'day',
						'month' => 'month',
						'year' => 'year',
					),
				)
			)
		;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
			'data_class' => 'App\Entity\Billing',
			'translation_domain' => 'addBilling',
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'app_billing';
    }


}
