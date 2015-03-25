<?php

namespace WarehouseBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use WarehouseBundle\Entity\StoreGroup;
use Symfony\Component\HttpFoundation\Request;


class StoreGroupController extends Controller
{
    #todo editAction
	#todo deleteAction


	/**
	 * @Route("/group")
	 */
	public function indexAction()
	{
		#todo 
	}


	/**
	 * @Route("/group/viewall", name="/group/viewall")
	 * Listuje wszystkie dostępne grupy
	 */
	public function viewallAction()
	{
		#todo
	}


	/**
     *
     * @Route("/group/new", name="/group/new")
     */
    public function newAction(Request $request)
    {

        $group = new StoreGroup();

        $form=$this->createFormBuilder($group)
        ->add('name', 'text')
        ->getForm();

        $form->handleRequest($request);
        if($form->isValid())
        {
            $dm=$this->getDoctrine()->getManager();
            $dm->persist($group);
            $dm->flush();

				#todo - flash! i odpowiedni widok
                return $this->render('store/successNewStore.html.twig');
        }

    return $this->render('group/newGroup.html.twig',
                 array(
                     'form'=>$form->createView()
                 ));
    }

}
