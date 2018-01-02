<?php

namespace App\Form\Location;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;

use App\Form\Location\EventListener\LocationFormatingSubscriber;

class AbstractLocationType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
		$builder
			->add('country', CountryType::class,
				array(
					'placeholder' => 'France',
					'empty_data' => 'FR',
					'required' => false,
				)
			)
			->add('state', TextType::class)
			->add('town', TextType::class)

			->addEventSubscriber(new LocationFormatingSubscriber());
		;
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
        return 'app_abstractLocation';
    }


}
