<?php namespace WarehouseBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use WarehouseBundle\Entity\StoreGroup;
use WarehouseBundle\Entity\Store;
use WarehouseBundle\Entity\Product;
use Symfony\Component\HttpFoundation\Request;


class UserController extends Controller
{
    /**
     * #fixme zły name. Był "/user", zmienam na "user_choose_store_group"
     * @Route("/user", name="user_choose_store_group")
     */
    public function chooseStoreGroupAction(Request $request)
    {
        // $form1 = $this->createFormBuilder()
        $form = $this->createFormBuilder()   //
            ->add('group','entity', array(
                'class' => 'WarehouseBundle:StoreGroup',
                'property' => 'name',
                'required' => false,
            ))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            //może być obiektem klasy StoreGroup lub nullem
            // $group = $form->getData();

            // #notice: zakomentowałem, bo oba warunki robią dokładnie to samo
            //  plus ten kod nie jest potrzebny z racji querybuildera
            //      Ale muszę Cię pochwalić za pomysłowość.
            // if ($group==null) {
            //     $stores = $this->getDoctrine()
            //         ->getRepository('WarehouseBundle:Store')
            //         ->findByGroup(null);
            // } else {
            //     $stores = $this->getDoctrine()
            //             ->getRepository('WarehouseBundle:Store')
            //             ->findByGroup($group['group']);
            // }
            //
            $group = $form->getData()['group'];
            if ($group instanceof StoreGroup) {
                $groupId = $group->getId();
            } else {
                $groupId = 0;
            }

            // return $this->forward(
                // 'WarehouseBundle:User:chooseGroup', array(
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
     * #fixme brak annotacji
     * @Route(
     *     "/user/groupChoose/{groupId}",
     *     name="user_choose_group",
     *     defaults={"groupId": "0"}
     * )
     */
    public function chooseGroupAction(Request $request, $groupId)
    {
        //przykładowy query builder
        // 1. przygotowujemy sobie coś, co nam go zbuuje, np repozytorium
        $repo = $this->getDoctrine()->getManager()
            ->getRepository('WarehouseBundle:Store')
        ;

        $form = $this->createFormBuilder()
        ->add('store','entity', array(
            'class' => 'WarehouseBundle:Store',
            'property' => 'name',
            // 'choices' => $stores
            // #notice poniższy parametr oczekuje, żę dostanie obiekt
            //  klasy QueryBuilder. Nie jest ważne, czy zbuduje go funkcja anonimowa,
            // czy Qb będzie przygotowany wcześniej. Tutaj użyłem metody mieszanej
            // która jest nie najpiękniejsza (formularz powinien siedzieć w osobnej klasie).
            // Do funkcji anonimowe przekazuję dwa parametry: nasze repozytorium oraz
            // id wybranej we wcześniejszym kroku grupy.
            'query_builder' => function() use ($repo, $groupId) {
                // #notice zaczynam tworzyć Qb. daję mu alias "s"
                //  i dołączam do niego StoreGrupy zapisując je pod skrótem sg
                $qb = $repo->createQueryBuilder('s')
                    ->leftJoin('s.group', 'sg')
                ;

                // tutaj tworzymy warunek. Jeżeli zmienna $groupId istnieje
                // i nie jest zerem/nullem/pustym stringiem, to...
                if ($groupId) {
                    // ...to ogranicz ilość wyników do tych obiektów Store
                    // których id przypisanej im grupy jest równe $groupId
                    $qb->andWhere('sg.id = :groupId') //pokaż tylko te wyniki
                        ->setParameter('groupId', $groupId) //które mają literkę "a" w sobie
                    ;
                }
                // w przeciwnym wypadku po prostu pomijamy ten warunek.

                // oczywiście możemy dodać więcej obostrzeń. Poniżej
                // dodalibyśmy warunek, że Store musi mięć w nazwie
                // literkę "a"
                // $qb->andWhere('s.name LIKE :lit')
                // ->setParameter('lit', '%a%')


                // Wyniki przed wysłaniem dalej możemy jeszcze posortować
                $qb->orderBy('s.name', 'asc'); //alfabetycznie wg imienia

                // gdy to już wszystko, podajemy QueryBuildera do returna
                return $qb;
            }
        ))
        ->getForm();

        $form->handleRequest($request);

        #PROBLEM dalej nie chce mi wejść do tej pętli...
        #   #error nie chciało Ci wejść do pętli, bo to nie byłą akcja (nie miała routingu.
        #       wysyłany formularz wracał do akcji powyżej (bo stamtąd był wysłany)
        if($form->isValid())
        {
            $store = $form->getData();

            var_dump($store);
            dump($form->getData()['store']);//wynikiem jest obiekt klasy Store
            // wybrany z ograniczonej przez nas listy
            die;

            #todo
            #błędne array + obsluga pustej grupy
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
     * @Route("/user/{groupId}/{storeId}", name="userControl")
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
     * @Route("/user/{groupId}/{storeId}/store", name="/user/storeProducts")
     * @param type $groupId
     * @param type $storeId
     *
     */
    public function showStoreProductsAction($groupId, $storeId)
    {
        #todo? - wyświetlanie po kategoriach
        $dm = $this->getDoctrine()->getManager();

        $products = $dm->getRepository('WarehouseBundle:Product')
                ->createQueryBuilder('p')
                ->leftJoin('p.store', 's')
                ->where('s.id = :sid')
                ->setParameter('sid', $storeId)
                ->getQuery()
                ->getResult();


        return $this->render('user/viewStoreProducts.html.twig',array(
            'products'=>$products,
        )
        );

    }

    /**
     * @Route("/user/{groupId}/{storeId}/group", name="/user/groupProducts")
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
