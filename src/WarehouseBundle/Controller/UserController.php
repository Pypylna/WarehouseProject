<?php namespace WarehouseBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use WarehouseBundle\Entity\StoreGroup;
use WarehouseBundle\Entity\Store;
use WarehouseBundle\Entity\Product;
use Symfony\Component\HttpFoundation\Request;


class UserController extends Controller
{
	/**
	 * @Route("/user")
	 */
	public function chooseGroupAction(Request $request, Request $request2)
	{
		$form1 = $this->createFormBuilder()
			->add('group','entity', array(
				'class' => 'WarehouseBundle:StoreGroup',
				'property' => 'name',
				'required' => false,
			))
			->getForm();

		$form1->handleRequest($request);

		if($form1->isValid())
		{
			$group = $form1->getData();
			
			if($group['group']==null)
			{
				$stores = $this->getDoctrine()
						->getRepository('WarehouseBundle:Store')
						->findByGroup(null);
				
			}
			else
			{
				$stores = $this->getDoctrine()
						->getRepository('WarehouseBundle:Store')
						->findByGroup($group['group']);
			}
			
			
			$form2 = $this->createFormBuilder()
					->add('store','entity', array(
						'class' => 'WarehouseBundle:Store',
						'property' => 'name',
						'choices' => $stores
					))
					->getForm();

			#PROBLEM - W ogóle nie wchodzi mi do pętli niżej. Dlaczego?
			#Pytanie:
			#zagnieżdżone ify, 3 returny... Czy nie powinnam jakoś inaczej,
			#lepiej zorganizować tę metodę? Wrzucić jakoś część do modelu?
			
			$form2->handleRequest($request);
			if($form2->isValid())
			{
				$store = $form2->getData();
				
				var_dump($group);
				var_dump($store);
				
				#todo
				#błędne array + obsluga pustej grupy
				return  $this->redirectToRoute('userControl', array(
					'group' => $group,
					'store' => $store
				));
			}
			
			return $this->render('user/choose2.html.twig', array(
				'form' => $form2->createView()
			));
		}

		return $this->render('user/choose1.html.twig', array(
			'form' => $form1->createView()
		));
	}

	
	/**
	 * @Route("/user/{groupId}/{storeId}", name="userControl")
	 * @param type $groupId
	 * @param type $storeId
	 */
	public function userControlAction($groupId, $storeId)
	{
		return $this->render('user/control.html.twig', array(
			'group' => $groupId,
			'store' => $storeId
		));
	}

	/**
	 * @Route("/user/{groupId}/{storeId}/store", name="/user/storeProducts")
	 * @param type $groupId
	 * @param type $storeId
	 * 
	 */
	public function showStoreProductsAction($groupId, $storeId)
	{
		#todo - sortowanie
		#todo? - wyświetlanie po kategoriach
		$dm = $this->getDoctrine()->getManager();
		
		$products = $dm->getRepository('WarehouseBundle:Product')
				->createQueryBuilder('p')
				->leftJoin('p.store', 's')
				->where('s.id = :sid')
				->setParameter('sid', $storeId)
				->getQuery()
				->getResult();
		
		
		return $this->render('user/viewStoreProducts.html.twig',array(
			'products'=>$products,
		)
		);
		
	}

	/**
	 * @Route("/user/{groupId}/{storeId}/group", name="/user/groupProducts")
	 * @param type $groupId
	 * @param type $storeId
	 * 
	 */
	public function showGroupProductsAction($groupId, $storeId)
	{
		#todo
		echo "showGroupProductsAction";
	}
	
	
}
