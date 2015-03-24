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
     * #fixme ustawienia tworzenia kodu: nie używać tabów - używać 4 spacji
     *
     * @Route("/store/new")
     */
    public function newAction(Request $request)
    {

        $store = new Store();

        $form=$this->createFormBuilder($store)
        ->add('name', 'text')
        ->add('localization','text')
        ->add('dodaj','submit') // #fixme submity wsadzamy do widoku (twig)
        ->getForm();

        $form->handleRequest($request);
        if($form->isValid())
            {
            $dm=$this->getDoctrine()->getManager();
            $dm->persist($store);
            $dm->flush();

                // #fixme taki widok nie istnieje
                // jeszcze nie był Ci potrzebny lub go nie dodałaś do commita
                //      $ git add --all
                // lub
                //      $ git add .
                return $this->render('store/successNewStore.html.twig');
            }
        else
        {
            // PROBLEM! z walidacją
            dump($form->getErrors());
        }

    // #fixme: na windowsie działa, na unixowych systemach nie.
    // Różnica: wielkość literki 'S'
    // Smaczek systemowy, o którym trzeba pamiętać:
    //                          zawsze pilnujemy wielkości liter
    return $this->render('store/newStore.html.twig',
                 array(
                     'form'=>$form->createView()
                 ));
    }

}
