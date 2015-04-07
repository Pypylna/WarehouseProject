<?php
	namespace WarehouseBundle\Form;
	
	use Symfony\Component\Form\AbstractType;
	use Symfony\Component\Form\FormBuilderInterface;
	use Symfony\Component\Validator\Constraints\Range;
	use WarehouseBundle\Entity\Product;
	
	class ReduceAmountProductType extends AbstractType
	{
		private $product;
		
		public function __construct(Product $arg)
		{
			$this->product = $arg;
		}


		public function buildForm(FormBuilderInterface $builder, array $options)
		{
			$builder
				->add('ilosc', 'number', array(
					'label' => 'Zmniejsz o:',
					'precision' => 2,
					'constraints' => array(
						new Range(array(
							'max' => $this->product->getAmount(),
							'maxMessage' => "Nie wiecej niz {{ limit }} ",
							'min' => 0,
							'minMessage' => "Co najmniej {{ limit }}"
						))
					)
				));
		}
		
		public function getName()
		{
			return 'reduce_amount_product';
		}
	}