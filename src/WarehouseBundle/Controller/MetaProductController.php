<?php

namespace WarehouseBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use WarehouseBundle\Entity\MetaProduct;
use Symfony\Component\HttpFoundation\Request;


class MetaProductController extends Controller
{
    //todo indexAction
    /**
     *
     * @Route("/metaProduct/new")
     */
    public function newAction(Request $request)
    {
		$metaProduct = new MetaProduct();

        $form=$this->createFormBuilder($metaProduct)
        ->add('name', 'text')
        ->add('description','text')
		->add('price','money',array(
			'currency' => 'PLN',
		))
		->add ('category','entity',array(
			'class' => 'WarehouseBundle:Category',
			'property' => 'name'
			#todo hierarchical choice list
		))
		->add('keywords','text')
		->getForm();
		

        $form->handleRequest($request);
        if($form->isValid())
            {
            $dm=$this->getDoctrine()->getManager();
            $dm->persist($metaProduct);
            $dm->flush();
				#todo - odpowiedni widok
                return $this->render('store/successNewStore.html.twig');
            }
        else
        {
			dump($form->getErrors());
        }

    return $this->render('product/newMetaProduct.html.twig',
                 array(
                     'form'=>$form->createView()
                 ));
    }

}
