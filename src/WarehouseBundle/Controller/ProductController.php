<?php

namespace WarehouseBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use WarehouseBundle\Entity\Product;
use Symfony\Component\HttpFoundation\Request;


class ProductController extends Controller
{
    //todo indexAction
    /**
     *
     * @Route("/product/new")
     */
    public function newAction(Request $request)
    {
		$product = new Product();

        $form=$this->createFormBuilder($product)
		#todo - łatwiejszy wybór - po kategoriach
        ->add('metaProduct', 'entity',array(
			'class' => 'WarehouseBundle:MetaProduct',
			'property' => 'name'
		))
		#todo - łatwiejszy wybór - po grupach
        ->add('store','entity',array(
			'class' => 'WarehouseBundle:Store',
			'property' => 'name'
		))
		->add('expireAt','date')
				#todo - domyślna data
                #info - wsadzenie "na start" daty do odpowiedniej
                #       własności w klasie powinno załatwić sprawę (strzelam)
		->add('amount','text')
		->getForm();


        $form->handleRequest($request);
        if($form->isValid())
            {
            $dm=$this->getDoctrine()->getManager();
            $dm->persist($product);
            $dm->flush();
				#todo - odpowiedni widok
                return $this->render('store/successNewStore.html.twig');
            }
        else
        {
			dump($form->getErrors());
        }

    return $this->render('product/newProduct.html.twig',
                 array(
                     'form'=>$form->createView()
                 ));
    }

}
