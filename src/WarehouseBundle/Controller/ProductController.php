<?php

namespace WarehouseBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use WarehouseBundle\Entity\Product;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\Range;
use WarehouseBundle\Form\ReduceAmountProductType;
use WarehouseBundle\Form\SearchProductsType;
use WarehouseBundle\Repository\ProductReposirtory;

/**
 * @Route("/user/{groupId}/{storeId}/product")
 * #chce zachowaæ t¹ sæie¿kê, by mieæ ca³y czas dotêp do groupId i storeId
 * #¿eby móc wróciæ do odpowiedniej strony dla usera
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
			
			$this->addFlash(
				'notice',
				'Dodano!'
			);
			
				#todo - odpowiedni widok
                return $this->render('store/successNewStore.html.twig');
        }

    return $this->render('product/newProduct.html.twig',
                 array(
                     'form'=>$form->createView()
                 ));
    }

	/**
	 * @Route("/reduce/{id}", name="product_reduce")
	 */
	public function reduceAmountAction(Request $request, $id,$groupId,$storeId)
	{
		$product = $this->getDoctrine()
				->getRepository('WarehouseBundle:Product')
				->findOneById($id);
		$form = $this->createForm(new ReduceAmountProductType($product), array(
			'action' => $this->generateUrl('product_reduce', array(
				'groupId'=>$groupId,
				'storeId'=>$storeId,
				'id'=>$id,
			)),//
			'method' => 'POST',
		));

         $form->handleRequest($request);
		 
		if($form->isValid())
		{
			$product->setAmount( $product->getAmount() - $form->getData()['ilosc'] );

			$dm=$this->getDoctrine()
					->getManager()
					->flush();
			
			$this->addFlash(
				'notice',
				'Zapisano!'
			);
			
			#todo - odpowiedni widok
			return $this->redirectToRoute('user_control', array(
			'groupId' => $groupId,
			'storeId' => $storeId,
			));
		}

		return $this->render('product/reduceAmount.html.twig', array(
			'form' => $form->createView(),
			'limit' => $product->getAmount(),
			'groupId' =>$groupId,
			'storeId' => $storeId,
			));
	}
	
	/**
	 * @Route("/search/{option}",
	 *	name="search_products",
	 *	defaults={"option": "1"})
	 * 
	 * $option == 0 produkty tylko dla store
	 * $option == 1 produkty dla ca³ej grupy
	 */
	public function searchProductsAction(Request $request,$groupId, $storeId, $option)
	{
		$form = $this->createForm(new SearchProductsType($groupId,$storeId,$option), array(
			'action' => $this->generateUrl('search_products', array(
				'groupId'=>$groupId,
				'storeId'=>$storeId
			)),
			'method' => 'POST',
		));
		
		$form->handleRequest($request);
		if($form->isValid()){
			
			$products = $this->getDoctrine()
					->getManager()
					->getRepository('WarehouseBundle:Product')
					->findByData($form->getData());
			
			return $this->render('user/viewStoreProducts.html.twig',array(
				'products'=>$products,
				'groupId' => $groupId,
				'storeId'=> $storeId,
				));
		}
		
		
		return $this->render('product/searchProducts.html.twig', array(
			'form' => $form->createView(),
			'groupId' =>$groupId,
			'storeId' => $storeId,
			'option' => $option,
			));
	}

}
