<?php

namespace App\Form\Member;

use App\Form\Member\AbstractMemberType;
use App\Form\Location\AddLocationType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use App\Service\Form\OptionsSetter;
use App\Form\Member\EventListener\MemberFormatingSubscriber;
use App\Form\Member\EventListener\PasswordEncoderSubscriber;

use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Valid;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MemberSignUpType extends AbstractMemberType
{
	private $encoder;

	public function __construct(\Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface $encoder)
	{
		$this->encoder = $encoder;
	}

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->add('password', RepeatedType::class,
				array('type' => PasswordType::class,
					'first_options' => array('label' => 'Password'),
					'second_options' => array('label' => 'Confirm Password'),
				))
			->add('mail', RepeatedType::class,
				array('type' => EmailType::class,
					'first_options' => array('label' => 'E-mail'),
					'second_options' => array('label' => 'Confirm E-mail')
				))

			->addEventSubscriber(new MemberFormatingSubscriber())
			->addEventSubscriber(new PasswordEncoderSubscriber($this->encoder))
			;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
			'data_class' => 'App\Entity\Member',
			'translation_domain' => 'signup',
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'app_memberSignUp';
    }

	/**
	 * {@inheritdoc}
	 */
	public function getParent()
	{
			return AbstractMemberType::class;
	}
}
