<?php
	namespace WarehouseBundle\Form;
	
	use Symfony\Component\Form\AbstractType;
	use Symfony\Component\Form\FormBuilderInterface;
	use Symfony\Component\Validator\Constraints\Range;
	
	class MailType extends AbstractType
	{
		public function buildForm(FormBuilderInterface $builder, array $options)
		{
			$builder
				->add('from','email')
				->add('subject', 'text')
				->add('body','text');
		}
		
		public function getName()
		{
			return 'mail';
		}
	}