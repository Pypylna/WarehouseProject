<?php
	namespace WarehouseBundle\Form;
	
	use Symfony\Component\Form\AbstractType;
	use Symfony\Component\Form\FormBuilderInterface;
	use Symfony\Component\Validator\Constraints\Range;
	
	class SearchProductsType extends AbstractType
	{
		private $groupId;
		private $storeId;
		
//		0 - tylko dla store
//		1 - dla ca�ej grupy
		private $option;
		
		public function __contruct($arg1,$arg2,$option)
		{
			$this->groupId = $arg1;
			$this->storeId = $arg2;
			$this->option = $option;
		}
		
		public function buildForm(FormBuilderInterface $builder, array $options)
		{
			$builder
				->add('name','text',array(
					'label' => 'nazwa',
					'required' => false,
				))
				->add('price_min', 'number',array(
					'label' => 'dro�sze ni�',
					'required' => false,
					'precision' => 2,
					)
				)
				->add('price_max', 'number',array(
					'label' => 'ta�sze ni�',
					'required' => false,
					'precision' => 2,
					'constraints' => array(
						new Range( array(
							'min' => 1,
							'minMessage' => "Co najmniej {{ limit }} "
							))
					))
				)
					#todo
//				->add('category','text',array(
//					'label' => 'kategoria',
//					'required' => false,
//				))
				->add('amount_max','number',array(
					'label' => 'mniej sztuk ni�',
					'required' => false,
					'precision' => 2,
					'constraints' => array(
						new Range( array(
							'min' => 1,
							'minMessage' => "Co najmniej {{ limit }} "
							))
					#bo mo�na te� sprzedawa� rzeczy na wag� - st�d u�amki w ilo�ci
				)))
				->add('amount_min','number',array(
					'label' => 'wi�cej sztuk ni�',
					'required' => false,
					'precision' => 2,
					#brak constraints - zak�adam, ze je�eli wpisze
					#np. liczbe ujemn� to zawsze prawdziwe
				))
				->add('expireAt_min','number',array(
					'label' => 'data wa�no�ci d�u�sza ni� (dni)',
					'required' => false,
				))
				->add('expireAt_max','number',array(
					'label' => 'data wa�no�ci kr�tsza ni� (dni)',
					'required' => false,
				));
			#error - jakby nie wchodzi� do tego ifa
			if($this->option == 1){#produkty dla ca�ej grupy
				$repo = $this->getDoctrine()->getManager()
					->getRepository('WarehouseBundle:Store')
				;
				$groupId=$this->groupId;
				$builder->add('store','entity',array(
					'class' => 'WarehouseBundle:Store',
					'property' => 'name',
					'label' => 'w sklepie',
					'required' => false,
					'query_builder' => function() use ($repo, $groupId) {
						$qb = $repo->createQueryBuilder('s')
							->leftJoin('s.group', 'sg')
							->where('sg.id = :gid')
							->setParameter('gid',$groupId)
						;
						$qb->orderBy('s.name', 'asc');

						return $qb;
					}
				));
			}
			
			
			
			#todo groups and stores
			
		}
		
		public function getName()
		{
			return 'search_products';
		}
	}