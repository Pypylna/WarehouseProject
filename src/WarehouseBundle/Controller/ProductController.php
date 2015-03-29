<?php

namespace WarehouseBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use WarehouseBundle\Entity\Product;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\Range;


class ProductController extends Controller
{

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

	/**
     * #notice strasznie zły name/alias -> "/product/reduce"
	 * @Route("/product/reduce/{id}", name="product_reduce")
	 */
	public function reduceAmountAction(Request $request, $id)
	{
		$product = $this->getDoctrine()
				->getRepository('WarehouseBundle:Product')
				->findOneById($id);

		$form = $this->createFormBuilder()
        // typ pola "text" też zadziała, ale lepszy byłby tutaj
        // chyba integer. Mniej problemogenny
				->add('ilosc', 'text', array(
					'label' => 'Zmniejsz o:',
					'constraints' => array(
						new Range(array(
							'max' => $product->getAmount(),
                            // żeby działało, potrzeba spacji dookoła wąsatych
							'maxMessage' => "Mniej niż {{limit}}",
						))
					)
				))
				->getForm();

		#PROBLEM - Kolejny if, do którego nie chce mi wejść...
		#Na pewno właściwe constraints u góry?
        #error: Zapomniałaś o $form->handleRequest() ?

        // $form->handleRequest($request);

        // var_dump($form->isValid()); // false
		if($form->isValid())
		{
			var_dump($form->getData());
            die;

			//$product->setAmount( $product->getAmount() -  )

			$dm=$this->getDoctrine()
					->getManager()
					->persist($product)
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
