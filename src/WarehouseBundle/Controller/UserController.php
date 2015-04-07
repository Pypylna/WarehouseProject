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
		#todo do usuniecia po naprawieniu wszystkich �cie�ek
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
				#error
//				Dla id==0, chc�, by wy�wietla� store bez grupy
//				Co si� dzieje:
//					bez else wy�wietla wszystkie store
//					z else nie wy�wietla nic
                if ($groupId) {
                    $qb->andWhere('sg.id = :groupId')
                        ->setParameter('groupId', $groupId)
                    ;
                }else{
					$qb->andWhere('sg.id = :groupId')
                        ->setParameter('groupId', NULL)
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
			
            return  $this->redirectToRoute('user_control', array(
                'groupId' => $groupId,
                'storeId' => $store->getId(),
                ));
        }

        return $this->render('user/choose2.html.twig', array(
            'form' => $form->createView()
            ));
    }

    /**
     * @Route("/{groupId}/{storeId}", name="user_control")
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
        #todo? - wyświetlanie po kategoriach
        $dm = $this->getDoctrine()->getManager();

        $products = $dm->getRepository('WarehouseBundle:Product')
				->findAllInStore($storeId);
		
        return $this->render('user/viewStoreProducts.html.twig',array(
            'products'=>$products,
			'groupId' => $groupId,
			'storeId'=> $storeId,
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
		#error Gdzie� tu wyrzuca b��d. Zanim to wsadzi�am do repozytorium dzia�a�o
		
        $products1 = $this->getDoctrine()->getManager()
                ->getRepository('WarehouseBundle:Product')
                ->findAllInStore($storeId);

        $products2 = $this->getDoctrine()->getManager()
                ->getRepository('WarehouseBundle:Product')
                ->findAllInGroupNotInStore($groupId,$storeId);

		#pytanie
		#chce zwr�ci� wyniki posortowane - najpierw prdukty nale��cego do danego store
		#potem te nale��ce to ca�ego group. Podzieli�am to na dwie akcj�, dwie, dwie tablice
		#(kt�re w�a�ciwie mog�abym jeszcze scali�). Da sie to jako� �adnie zrobi�
		#w querybuilderze?
        return $this->render('user/viewGroupProducts.html.twig',array(
            'products1'=>$products1,
            'products2'=>$products2
        )
        );
    }


}
