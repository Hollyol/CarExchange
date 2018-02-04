<?php

namespace App\Form\Member;

use App\Form\Member\AbstractMemberType;
use App\Form\Location\AddLocationType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

use App\Service\Form\OptionsSetter;
use App\Form\Member\EventListener\MemberFormatingSubscriber;
use App\Form\Member\EventListener\PasswordEncoderSubscriber;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ApiMemberSignUpType extends AbstractMemberType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$optionsSetter = new OptionsSetter();

		$builder
			->add('mail', HiddenType::class)
			->add('language', HiddenType::class)
			->remove('password')

			->addEventSubscriber(new MemberFormatingSubscriber())
			;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
			'data_class' => 'App\Entity\Member',
			'translation_domain' => 'apiSignup',
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'app_facebookMemberSignUp';
    }

	/**
	 * {@inheritdoc}
	 */
	public function getParent()
	{
			return AbstractMemberType::class;
	}
}
