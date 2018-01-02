<?php

namespace App\Form\Car;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Validator\Constraints\GreaterThan;

class AbstractCarType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
	    $builder->add('brand', TextType::class)
		    ->add('model', TextType::class)
		    ->add('sits', IntegerType::class,
				array(
					'data' => 5,
				))
		    ->add('fuel', ChoiceType::class,
				array(
		    		'choices' => array(
						'diesel' => 'diesel',
						'SP-98' => 'SP-98',
						'SP-95' => 'SP-95',
						'electric' => 'electric',
						'hybrid' => 'hybrid'
					),
					'required' => false,
				))
			->add('description', TextareaType::class,
				array(
					'required' => false
				))
		;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'App\Entity\Car'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'app_abstractCar';
    }


}
