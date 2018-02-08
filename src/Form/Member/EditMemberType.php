<?php

namespace App\Form\Member;

use App\Form\Member\AbstractMemberType;
use App\Form\Location\AddLocationType;
use App\Form\Member\EventListener\MemberFormatingSubscriber;
use App\Form\Member\EventListener\PasswordEncoderSubscriber;

use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class EditMemberType extends AbstractMemberType
{
	private $encoder;

	public function __construct(UserPasswordEncoderInterface $encoder)
	{
		$this->encoder = $encoder;
	}

	/**
	 * {@inheritdoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->add('password', RepeatedType::class, array(
				'type' => PasswordType::class,
				'first_options' => array('label' => 'Password'),
				'second_options' => array('label' => 'Confirm Password'),
			))
			->add('mail', RepeatedType::class, array(
				'type' => EmailType::class,
				'first_options' => array('label' => 'Email'),
				'second_options' => array('label' => 'Confirm Email'),
			))
			->addSubscriber(new MemberFormatingSubscriber)
			->addSubscriber(new PasswordEncoderSubscriber($this->encoder))
			;
	}

	/**
	 * {@inheritdoc}
	 */
	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults(array(
			'data_class' => 'App\Entity\Member',
			'translation_domain' => 'editMember',
		));
	}

	/**
	 * {@inheritdoc}
	 */
	public function getBlockPrefix()
	{
		return 'app_editMember';
	}

	/**
	 * {@inheritdoc}
	 */
	public function getParent()
	{
		return AbstractMemberType::class;
	}
}
