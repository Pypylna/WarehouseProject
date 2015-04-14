<?php

namespace WarehouseBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
#fixme: Template jest (i ma pozostać) nieużywane, więc przydałoby się
#   usunąć lub zakomentować tą linię (minimalna kwestia wydajności)
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use WarehouseBundle\Entity\StoreGroup;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/group")
 */
class StoreGroupController extends Controller
{
    #todo editAction
    #todo deleteAction

    /**
     *
     */
    public function indexAction()
    {
        #todo
    }


    /**
     * @Route("/viewall", name="/group/viewall")
     * Listuje wszystkie dostepne grupy
     */
    public function viewallAction()
    {
        #todo
    }


    /**
     *
     * @Route("/new", name="/group/new")
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
