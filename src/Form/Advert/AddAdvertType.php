<?php

namespace App\Form\Advert;

use App\Form\Advert\AbstractAdvertType;
use App\Form\Car\AddCarType;
use App\Form\Location\AddLocationType;
use App\Form\Billing\BillingType;

use App\Services\Form\OptionsSetter;

use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Valid;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddAdvertType extends AbstractAdvertType
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
