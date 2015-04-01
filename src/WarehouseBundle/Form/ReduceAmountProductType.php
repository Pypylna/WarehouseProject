<?php
	namespace WarehouseBundle\Form;
	
	use Symfony\Component\Form\AbstractType;
	use Symfony\Component\Form\FormBuilderInterface;
	use Symfony\Component\Validator\Constraints\Range;
	
	class ReduceAmountProductType extends AbstractType
	{
		#error - Jak przekazaæ konkretny obiekt do tej akcji?
		public function buildForm(FormBuilderInterface $builder, array $options)
		{
			$builder
				->add('ilosc', 'number', array(
					'label' => 'Zmniejsz o:',
					'precision' => 2,
					'constraints' => array(
						new Range(array(
							'max' => $product->getAmount(),
							'maxMessage' => "Nie wiecej niz {{ limit }} ",
							'min' => 1,
							'minMessage' => "Co najmniej {{ limit }}"
						))
					)
				));
		}
		
		public function getName()
		{
			return 'reduce_amount_product';
		}
		//todo deafult options
	}