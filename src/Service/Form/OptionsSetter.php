<?php

namespace App\Service\Form;

use Symfony\Component\Form\FormBuilderInterface;

class OptionsSetter
{
	public function setOptions(FormBuilderInterface $builder, string $field, array $options = [], $type = ''){
		if (!$builder->has($field))
			return;

		$fieldOptions = $builder->get($field)->getOptions();

		if (!$type){
			$type = get_class($builder->get($field)->getType()->getInnerType());
		}

		foreach ($options as $option => $value){
			$fieldOptions[$option] = $value;
		}

		$builder->add($field, $type, $fieldOptions);
	}
}
