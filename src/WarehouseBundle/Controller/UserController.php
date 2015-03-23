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
	#PROBLEM - Nieudana próba.
	/*
	 * Chciałam, żeby user miał możliwość wybrania własnego StoreGroup,
	 * następnie dostępnego Store i później z tymi danymi działać dalej.
	 * Najlepiej żeby ten wybór był w jednym widoku - jak to zrobić?
	 */
	
			
	{
		$T = array(
			'group' => new StoreGroup(),
		);
		
		$form1 = $this->createFormBuilder($T)
			->add('group','entity', array(
				'class' => 'WarehouseBundle:StoreGroup',
				'property' => 'name'
			))
			->getForm();
		
		
		$form1->handleRequest($request);
		
		if($form1->isValid()) #potrzebna inna funkcja?
		{
			$T['store'] = new Store();
			
			$form2 = $this->createFormBuilder($T)
				->add('store','entity',array(
					'class' => 'WarehouseBundle:Store',
					'property' => 'name'
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