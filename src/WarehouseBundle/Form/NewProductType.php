<?php
	namespace WarehouseBundle\Form;
	
	use Symfony\Component\Form\AbstractType;
	use Symfony\Component\Form\FormBuilderInterface;
	use Symfony\Component\Validator\Constraints;
	
	class NewProductType extends AbstractType
	{

		public function buildForm(FormBuilderInterface $builder, array $options)
		{
			$builder
			#todo - latwiejszy wybor - po kategoriach
			->add('metaProduct', 'entity',array(
				'class' => 'WarehouseBundle:MetaProduct',
				'property' => 'name'
			))
			#todo - latwiejszy wybor - po grupach
			->add('store','entity',array(
				'class' => 'WarehouseBundle:Store',
				'property' => 'name'
			))
			->add('expireAt','date')
					#todo - domyslna data
					#info - wsadzenie "na start" daty do odpowiedniej
					#       wlasnosci w klasie powinno zalatwic sprawe (strzelam)
			->add('amount','text')
			;
		}
			
		public function getName()
		{
			return 'new_product';
		}

		#todo default options
	}