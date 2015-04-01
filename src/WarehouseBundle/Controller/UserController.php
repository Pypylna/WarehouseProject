<?php namespace WarehouseBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use WarehouseBundle\Entity\StoreGroup;
use WarehouseBundle\Entity\Store;
use WarehouseBundle\Entity\Product;
use WarehouseBundle\Repository\ProductReposirtory;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/user")
 */
class UserController extends Controller
{
    /**
	 * @Route("/test", name="user_test")
	 */
	public function testAction()
	{
		return $this->render('base.html.twig');
	}


	
	/**
     * @Route("", name="user_choose_store_group")
     */
    public function chooseStoreGroupAction(Request $request)
    {
        $form = $this->createFormBuilder()
            ->add('group','entity', array(
                'class' => 'WarehouseBundle:StoreGroup',
                'property' => 'name',
                'required' => false,
            ))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $group = $form->getData()['group'];
            if ($group instanceof StoreGroup) {
                $groupId = $group->getId();
            } else {
                $groupId = 0;
            }

            return $this->redirectToRoute(
                'user_choose_group', array(
                    'groupId' => $groupId,
                )
            );
        }

        return $this->render('user/choose1.html.twig', array(
            'form' => $form->createView()
            ));
    }


    /**
     * @Route(
     *     "/groupChoose/{groupId}",
     *     name="user_choose_group",
     *     defaults={"groupId": "0"}
     * )
     */
    public function chooseGroupAction(Request $request, $groupId)
    {
		#fragment Konrada (commit ba22c)
        $repo = $this->getDoctrine()->getManager()
            ->getRepository('WarehouseBundle:Store')
        ;

        $form = $this->createFormBuilder()
        ->add('store','entity', array(
            'class' => 'WarehouseBundle:Store',
            'property' => 'name',
            'query_builder' => function() use ($repo, $groupId) {
                $qb = $repo->createQueryBuilder('s')
                    ->leftJoin('s.group', 'sg')
                ;
                if ($groupId) {
                    $qb->andWhere('sg.id = :groupId')
                        ->setParameter('groupId', $groupId)
                    ;
                }
                $qb->orderBy('s.name', 'asc');
				
                return $qb;
            }
        ))
        ->getForm();

        $form->handleRequest($request);

        if($form->isValid())
        {
            $store = $form->getData()['store'];
			die;

            #todo
            #bÅ‚Ä™dne array + obsluga pustej grupy
            return  $this->redirectToRoute('userControl', array(
                'group' => $group,
                'store' => $store
                ));
        }

        return $this->render('user/choose2.html.twig', array(
            'form' => $form->createView()
            ));
    }




    /**
     * @Route("/{groupId}/{storeId}", name="user_Control")
     * @param type $groupId
     * @param type $storeId
     */
    public function userControlAction($groupId, $storeId)
    {
        return $this->render('user/control.html.twig', array(
            'group' => $groupId,
            'store' => $storeId
        ));
    }

    /**
     * @Route("/{groupId}/{storeId}/store", name="user_store_products")
     * @param type $groupId
     * @param type $storeId
     *
     */
    public function showStoreProductsAction($groupId, $storeId)
    {
        #todo? - wyÅ›wietlanie po kategoriach
        $dm = $this->getDoctrine()->getManager();

		#error! Wyrzuca b³¹d - findAllInStore() niezdfiniowana...
        $products = $dm->getRepository('WarehouseBundle:Product')
				->findAllInStore($storeId);
		
        return $this->render('user/viewStoreProducts.html.twig',array(
            'products'=>$products,
        )
        );

    }

    /**
     * @Route("/{groupId}/{storeId}/group", name="user_group_products")
     * @param type $groupId
     * @param type $storeId
     *
     */
    public function showGroupProductsAction($groupId, $storeId)
    {
        $products1 = $this->getDoctrine()->getManager()
                ->getRepository('WarehouseBundle:Product')
                ->createQueryBuilder('p')
                ->leftJoin('p.store', 's')
                ->where('s.id = :sid')
                ->setParameter('sid', $storeId)
                ->getQuery()
                ->getResult();

        $products2 = $this->getDoctrine()->getManager()
                ->getRepository('WarehouseBundle:Product')
                ->createQueryBuilder('p')
                ->leftJoin('p.group', 'g')
                ->where('g.id = :gid')
                ->setParameter('gid', $groupId)
                ->leftJoin('g.store','s')
                ->where('s.id != :sid')
                ->setParameter('sid', $storeId)
                ->getQuery()
                ->getResult();


        return $this->render('user/viewGroupProducts.html.twig',array(
            'products1'=>$products1,
            'products2'=>$products2
        )
        );
    }


}
