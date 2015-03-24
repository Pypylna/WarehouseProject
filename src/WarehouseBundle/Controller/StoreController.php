<?php

namespace WarehouseBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use WarehouseBundle\Entity\Store;
use Symfony\Component\HttpFoundation\Request;


class StoreController extends Controller
{
    #todo editAction
	#todo deleteAction


	/**
	 * @Route("/store")
	 */
	public function indexAction()
	{
		return $this->render('store/index.html.twig');
	}


	/**
	 * @Route("/store/viewall")
	 * Listuje wszystkie sklepy
	 */
	public function viewallAction()
	{
		$storegroups = $this->getDoctrine()->getRepository('WarehouseBundle:StoreGroup')->findAll();

		return $this->render('store/viewallStore.html.twig',array(
			'storegroups' => $storegroups,
		));
	}


	/**
     *
     * @Route("/store/new")
     */
    public function newAction(Request $request)
    {

        $store = new Store();

        $form=$this->createFormBuilder($store)
        ->add('name', 'text')
        ->add('localization','text')// #dlaczego submit do widoku?
		->add('group', 'entity', array(
			'class' => 'WarehouseBundle:StoreGroup',
			'required' => false,	#zakładam, że moze istnieć sklep niebędący w sieci
			'property' => 'name'
			#todo nowa grupa
		))
        ->getForm();

		#info -> submit do widoku, bo:
        # 1. jest w nim definowany używany znacznik (<submit>),
        #       a decyzja czy go użyć, czy nie leży w gestii frontendowców.
        #       Gdyby postanowili użyć innego znacznika w jego miejsce
        #       (np <input>) albo nie chcieli go użüwać wcale (javascript)
        #       musieliby odnaleźć to miejsce w kodzie,
        #       gdzie został on dodany do formularza lub w brzydki sposób
        #       go ukryć za pomocą css-ów, co mogłoby się odbić
        #       np na pozycjonowaniu strony
        # 2. celem backendowców jest stworzyć część serwerową,
        #       która będzie możliwie kuloodporna. Brzmi skromnie, ale to nawał
        #       pracy.

        $form->handleRequest($request);
        if($form->isValid())
        {
            $dm=$this->getDoctrine()->getManager();
            $dm->persist($store);
            $dm->flush();

                return $this->render('store/successNewStore.html.twig');
        }

    // #dobrepraktyki - pilnujemy wielkości literek
    return $this->render('store/newStore.html.twig',
                 array(
                     'form'=>$form->createView()
                 ));
    }

}
