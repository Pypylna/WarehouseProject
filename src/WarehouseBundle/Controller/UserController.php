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
	 * @Route("/user", name="/user")
	 */
	public function chooseStoreGroupAction(Request $request)
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
			
			return $this->forward('WarehouseBundle:User:chooseGroup', array(
					'stores' => $stores,
					));
		}
			
		return $this->render('user/choose1.html.twig', array(
			'form' => $form1->createView()
			));
	}

	
	public function chooseGroupAction(Request $request,$stores)
	{
		$form2 = $this->createFormBuilder()
		->add('store','entity', array(
			'class' => 'WarehouseBundle:Store',
			'property' => 'name',
			'choices' => $stores
			))
		->getForm();
		
		$form2->handleRequest($request);
		
		#PROBLEM dalej nie chce mi wejść do tej pętli...
		if($form2->isValid())
		{
			$store = $form2->getData();

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
		$products1 = $this->getDoctrine()->getManager()
				->getRepository('WarehouseBundle:Product')
				->createQueryBuilder('p')
				->leftJoin('p.store', 's')
				->where('s.id = :sid')
				->setParameter('sid', $storeId)
				->getQuery()
				->getResult();
		
		$products2 = $this->getDoctrine()->getManager()
				->getRepository('WarehouseBundle:Product')
				->createQueryBuilder('p')
				->leftJoin('p.group', 'g')
				->where('g.id = :gid')
				->setParameter('gid', $groupId)
				->leftJoin('g.store','s')
				->where('s.id != :sid')
				->setParameter('sid', $storeId)
				->getQuery()
				->getResult();
		
		
		return $this->render('user/viewGroupProducts.html.twig',array(
			'products1'=>$products1,
			'products2'=>$products2
		)
		);
	}
	
	
}
