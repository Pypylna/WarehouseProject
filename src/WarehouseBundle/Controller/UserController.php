<?php

namespace WarehouseBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use WarehouseBundle\Entity\StoreGroup;
use WarehouseBundle\Entity\Store;
use Symfony\Component\HttpFoundation\Request;


class UserController extends Controller
{
	/**
	 * @Route("/user")
	 */
	public function chooseGroupAction(Request $request)
	{
		$form1 = $this->createFormBuilder()
			->add('group','entity', array(
				'class' => 'WarehouseBundle:StoreGroup',
				'property' => 'name'
			))
			->getForm();

		$form1->handleRequest($request);

        #potrzebna inna funkcja? isClicked?
        #info nie porzebna
		if($form1->isValid())
		{
			$group = $form1->getData();
            //#info u mnie tak się zdażyło, że $group było puste.
            // nie miałem też controllera dla StoreGroup. Nie dodałaś plików może?
			// dump($group);
   //          die;

			$store = new Store();
			$form2 = $this->createFormBuilder()
				->add('store','entity',array(
					'class' => 'WarehouseBundle:Store',
                    //#info powyższe nam mówi "w tym polu będą używane obiekty klasy Store"
                    //      czy to na pewno to, co chcesz tu osiągnąć?
					'property' => 'name',
					'choices' => $group->stores //u mnie null ~KM
                    // #PROBLEM
                    // #error - wysyłając pierwszy formularz pusty zmienna $group
                    //      ma wartość null. A wykonywać ->stores na nullu nie wolno
                    //
                    // #info poniższy problem z querybuilderem wynika z tego, że:
                    //      klucz 'query_builder' wskazuje na >> funkcję anonimową <<
                    //      Czasem się w php coś takiego spotyka. W Javascripcie
                    //      cały czas. Żeby przykazać coś do niej trzeba zastosować
                    //      taką konstrukcję:
                    //
                    //      function(Klasa $obiekt) use ($parametr1, $parametr2, $itd) {
                    //          // ciałko
                    //      }
                    //
                    //      dopiero w środku można wtedy używać podanych parametrów
                    //      i po nich np filtrować tak, jak chciałaś to zrobić.
                    //
//					'query_builder' => function(\Doctrine\ORM\EntityRepository $er)
//						{
//							return $er->createQueryBuilder('st')
//								->leftJoin('st.group', 'group')   //ładnie, dobrze
//								->where('group =: gr')            //brzydko, źle
//								->setParameter('gr', $group)
//
//                                #error powyżej powinno być
//                              ->where('group.id = :gr')
//                              ->setParameter('gr', $group->getId()) // lub bez ->getId()
//
//							;
//
//
//                          #info $group może być puste. W takim wypadku queryBuilder
//                                  może się wysypać, gdy dostanie nulla.
//                                  Można to załatwić np tak:
//
        //                          $qb = $er->createQueryBuilder('st')
        //                              ->leftJoin('st.group', 'group');
        //
    //                              if ($group) {
    //                                  $qb->where('jakieś warunki')
//                                          ->setParameter('jakieś parametry');
    //                              }
    //
    //                              return $qb
    //                                  ->andWhere('więcej warunków, jeśli trzeba');
    //
//						},
				))
				->getForm();

			return $this->render('user/choose2.html.twig', array(
				'form' => $form2->createView()
			));
		}



		return $this->render('user/choose1.html.twig', array(
			'form' => $form1->createView()
		));
	}

}
