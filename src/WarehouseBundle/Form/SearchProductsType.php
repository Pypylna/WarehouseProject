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
//		1 - dla ca³ej grupy
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
					'label' => 'dro¿sze ni¿',
					'required' => false,
					'precision' => 2,
					)
				)
				->add('price_max', 'number',array(
					'label' => 'tañsze ni¿',
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
					'label' => 'mniej sztuk ni¿',
					'required' => false,
					'precision' => 2,
					'constraints' => array(
						new Range( array(
							'min' => 1,
							'minMessage' => "Co najmniej {{ limit }} "
							))
					#bo mo¿na te¿ sprzedawaæ rzeczy na wagê - st¹d u³amki w iloœci
				)))
				->add('amount_min','number',array(
					'label' => 'wiêcej sztuk ni¿',
					'required' => false,
					'precision' => 2,
					#brak constraints - zak³adam, ze je¿eli wpisze
					#np. liczbe ujemn¹ to zawsze prawdziwe
				))
				->add('expireAt_min','number',array(
					'label' => 'data wa¿noœci d³u¿sza ni¿ (dni)',
					'required' => false,
				))
				->add('expireAt_max','number',array(
					'label' => 'data wa¿noœci krótsza ni¿ (dni)',
					'required' => false,
				));
			#error - jakby nie wchodzi³ do tego ifa
            #fixme: Sprawdź, jaką wartość ma $this->option.
            #       Możliwe, że jest nullem, bo nic nie przyszło z wyszukiwarki.
            #       Warto też zobaczyć jaką ma wartość w searchProductsAction
			if($this->option == 1){#produkty dla ca³ej grupy
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
