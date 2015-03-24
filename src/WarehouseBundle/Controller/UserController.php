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
		
		if($form1->isValid()) #potrzebna inna funkcja? isClicked?
		{
			$group = $form1->getData();
	//		var_dump($group);
			
			$store = new Store();
			$form2 = $this->createFormBuilder()
				->add('store','entity',array(
					'class' => 'WarehouseBundle:Store',
					'property' => 'name',
					'choices' => $group->stores #PROBLEM
//					'query_builder' => function(\Doctrine\ORM\EntityRepository $er)
//						{
//							return $er->createQueryBuilder('st')
//								->leftJoin('st.group', 'group')
//								->where('group =: gr')
//								->setParameter('gr', $group)
//							;
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