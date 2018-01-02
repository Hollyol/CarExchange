<?php

use App\Service\Form\OptionsSetter;

use App\Form\Car\AddCarType;

use Symfony\Component\Form\ResolvedFormTypeInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormBuilder;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use PHPUnit\Framework\TestCase;

class OptionsSetterTest extends TestCase
{
	protected $optionsSetter;

	public function setUp()
	{
		$this->optionsSetter = new OptionsSetter();
	}

	/**
	 * If a field doesn't exists, the OptionsSetter will return before calling
	 * any method. If it exists, the OptionsSetter will get the current options
	 * using the method 'get' on the builder.
	 *
	 * @dataProvider fastReturnProvider
	 *
	 */
	public function testFastReturn(string $field)
	{
		$builder = $this->createMock(FormBuilderInterface::class);
		$builder
			->expects($this->exactly(2))
			->method('has')
			->will($this->returnValue('field' == $field));
		$builder->method('get')
			->will($this->returnSelf());
		$builder->method('getOptions')
			->will($this->returnValue([]));
		$builder->method('getType')
			->will($this->returnValue($this->createMock(ResolvedFormTypeInterface::class)));

		if ($builder->has($field)) {
			$builder->expects($this->atLeastOnce())
				->method('get');
		} else {
			$builder->expects($this->exactly(0))
				->method('get');
		}

		$this->optionsSetter->setOptions($builder, $field);
	}

	/**
	 * If no value is provided for the 'type' argument, we keep the one already here
	 *
	 * @dataProvider typeProvider
	 *
	 */
	public function testType(string $baseType = 'type')
	{
		if ($baseType)
			$expectedType = $baseType;
		else
			$expectedType = SubmitType::class;

		$mockedBuilder = $this->getMockBuilder(FormBuilder::class)
			->disableOriginalConstructor()
			->setMethods(['has', 'get', 'add', 'getOptions', 'getType', 'getInnerType'])
			->getMock();
		$mockedBuilder
			->expects($this->once())
			->method('has')
			->will($this->returnValue(true));
		$mockedBuilder
			->expects($this->atLeastOnce())
			->method('get')
			->will($this->returnSelf());
		$mockedBuilder
			->expects($this->once())
			->method('getOptions')
			->will($this->returnValue([]));
		$mockedBuilder
			->method('getType')
			->will($this->returnSelf());
		$mockedBuilder
			->method('getInnerType')
			->will($this->returnValue(new SubmitType()));
		$mockedBuilder
			->expects($this->exactly(2))
			->method('add')
			->withConsecutive(
				[$this->equalTo('field'), $this->equalTo(SubmitType::class)],
				[$this->equalTo('field'), $this->equalTo($expectedType)]
			);

		$mockedBuilder->add('field', SubmitType::class);

		$this->optionsSetter->setOptions($mockedBuilder, 'field', [], $baseType);
	}

	/**
	 * If an array of options is provided, the Options Setter MUST roam each
	 * options of this array, and add it in the real array if it doesn't exists
	 * or override and existing option.
	 *
	 * If an option already exists and is not provided for the Options Setter,
	 * its value MUST not change
	 *
	 * @dataProvider optionsProvider
	 *
	 */
	public function testOptions(array $providedOptions = []){
		$realOptions = array(
			'label' => 'realLabel',
			'required' => 'false',
		);

		$mockedBuilder = $this->createMock(FormBuilder::class);
		$mockedBuilder
			->method('has')
			->will($this->returnValue(true));
		$mockedBuilder
			->method('get')
			->will($this->returnSelf());
		$mockedBuilder
			->method('getOptions')
			->will($this->returnValue($realOptions));
		$mockedBuilder
			->expects($this->once())
			->method('add')
			->with(
				$this->equalTo('field'),
				$this->equalTo('type'),
				$this->callback(
				function($fieldOptions) use ($providedOptions, $realOptions) {
					$this->assertLessThanOrEqual(count($providedOptions) + count($realOptions), count($fieldOptions));

					if ($providedOptions == []) {
						foreach ($fieldOptions as $option => $value) {
							$this->assertArrayHasKey($option, $realOptions);
							$this->assertEquals($value, $realOptions[$option]);
							unset($fieldOptions[$option]);
						}
						$this->assertEmpty($fieldOptions, 'You will add more options than the real ones, whereas not any were provided');
						return true;
					}

					foreach($providedOptions as $option => $value){
						$this->assertArrayHasKey($option, $fieldOptions);
						$this->assertEquals($value, $fieldOptions[$option]);
					}

					foreach ($realOptions as $option => $value) {
						$this->assertArrayHasKey($option, $fieldOptions);
						if (!array_key_exists($option, $providedOptions))
							$this->assertEquals($value, $fieldOptions[$option]);
					}
					return true;
				}
			));

		$this->optionsSetter->setOptions($mockedBuilder, 'field', $providedOptions, 'type');
	}

	public function optionsProvider()
	{
		return array(
			'empty array' => array(
				[]
			),
			'only addition' => array([
				'data' => 'fakeData',
				'empty_data' => 'fakePlaceholder',
			]),
			'only replacements' => array([
				'label' => 'newLabel',
				'required' => 'true',
			]),
			'both addition and replacement' => array([
				'label' => 'newLabel',
				'data' => 'fakeData',
			]),
		);
	}

	public function fastReturnProvider()
	{
		return array(
			'empty string' => array(''),
			'not existing field' => array('fake'),
			'existing field' => array('field'),
		);
	}

	public function typeProvider()
	{
		return array(
			'empty type' => array(''),
			'blank type' => array(' '),
			'valid type' => array('type'),
		);
	}
}
