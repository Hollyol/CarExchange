<?php

namespace App\Form\Location;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Doctrine\ORM\EntityManagerInterface;

use App\Form\Location\AbstractLocationType;
use App\Form\Location\EventListener\LocationDuplicateSubscriber;

class AddLocationType extends AbstractLocationType
{
	private $em;

	public function __construct(EntityManagerInterface $em)
	{
		$this->em = $em;
	}
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->addEventSubscriber(new LocationDuplicateSubscriber($this->em))
			;
	}
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
			'data_class' => 'App\Entity\Location',
			'translation_domain' => 'addLocation',
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'app_addLocation';
    }

	/**
	 * {@inheritdoc}
	 */
	public function getParent()
	{
		return AbstractLocationType::class;
	}
}
