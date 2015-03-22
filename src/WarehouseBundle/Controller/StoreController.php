<?php

namespace WarehouseBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use WarehouseBundle\Entity\Store;
use Symfony\Component\HttpFoundation\Request;


class StoreController extends Controller
{
    //DODAC indexAction
    /**
     * @Route("/store/new")
     */
    public function newAction(Request $request)
    {
        
        $store = new Store();
        
        $form=$this->createFormBuilder($store)
    	->add('name', 'text')
    	->add('localization','text')
        ->add('dodaj','submit')
    	->getForm();
        
        $form->handleRequest($request);
    	if($form->isValid())
            {
    		$dm=$this->getDoctrine()->getManager();
    		$dm->persist($store);
    		$dm->flush();
                
                return $this->render('store/successNewStore.html.twig');
            }
        else
        {
            // PROBLEM! z walidacją
            dump($form->getErrors());
        }
	return $this->render('store/newstore.html.twig',
                 array(
                     'form'=>$form->createView()
                 ));
    }
}
