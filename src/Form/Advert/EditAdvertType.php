<?php

namespace App\Form\Advert;

use App\Form\Advert\AbstractAdvertType;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditAdvertType extends AbstractAdvertType
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
			'translation_domain' => 'editAdvert'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'xp_carbundle_editAdvert';
    }

	/**
	 * {@inheritdoc}
	 */
	public function getParent()
	{
		return AbstractAdvertType::class;
	}

}
