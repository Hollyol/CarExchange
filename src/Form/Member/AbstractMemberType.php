<?php

namespace App\Form\Member;

use App\Form\Location\AddLocationType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Valid;
use Symfony\Component\Validator\Constraints\Callback;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AbstractMemberType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$phoneRegex = new Regex(array(
			'pattern' => '/^(\d{2}[ \/.,_-]){4}\d{2}$/',
			'message' => 'The phone number must be 5 groups of 2 digits separated by either : . , / - or a space',
		));

		$builder
			->add('username', TextType::class)
			->add('password', PasswordType::class)
			->add('mail', EmailType::class)
			->add('phone', TextType::class,	array(
				'constraints' => $phoneRegex,
				'required' => false,
				))
			->add('location', AddLocationType::class, array(
				'constraints' => new Valid(),
				))
			->add('language', ChoiceType::class, array(
				'choices' => array(
					'Français' => 'fr',
					'English' => 'en',
				),
			))
			->add('submit', SubmitType::class);
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'App\Entity\Member'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'xp_userbundle_abstractMember';
    }
}
