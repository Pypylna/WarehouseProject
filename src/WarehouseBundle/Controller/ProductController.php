<?php

namespace WarehouseBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use WarehouseBundle\Entity\Product;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\Range;
use WarehouseBundle\Form\ReduceAmountProductType;

/**
 * @Route("/product")
 */
class ProductController extends Controller
{

    /**
     *
     * @Route("/new", name="product_new")
     */
    public function newAction(Request $request)
    {
		$product = new Product();

        $form=$this->createForm(new NewProductType, $product, array(
			'action' => $this->generateUrl('product_new'),
			'method' => 'POST',
		));
		
        $form->handleRequest($request);
        if($form->isValid())
        {
            $dm=$this->getDoctrine()->getManager();
            $dm->persist($product)
				->flush();
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

	/**
	 * @Route("/reduce/{id}", name="product_reduce")
	 */
	public function reduceAmountAction(Request $request, $id)
	{
		$product = $this->getDoctrine()
				->getRepository('WarehouseBundle:Product')
				->findOneById($id);
		#error wywo³anie niedzia³aj¹cego formularza
		$form = $this->createForm(new ReduceAmountProductType, $product, array(
			'action' => $this->generateUrl('product_new'),
			'method' => 'POST',
		));

         $form->handleRequest($request);
		 
		if($form->isValid())
		{
			$product->setAmount( $product->getAmount() - $form->getData()['ilosc'] );

			$dm=$this->getDoctrine()
					->getManager()
					->flush();
				#todo - odpowiedni widok
                return $this->render('store/successNewStore.html.twig');
		}

		return $this->render('product/reduceAmount.html.twig', array(
			'form' => $form->createView(),
			'limit' => $product->getAmount(),
			));
	}

}
