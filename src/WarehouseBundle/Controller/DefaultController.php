<?php namespace WarehouseBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use WarehouseBundle\Entity\StoreGroup;
use WarehouseBundle\Entity\Store;
use WarehouseBundle\Entity\Product;
use WarehouseBundle\Repository\ProductReposirtory;
use Symfony\Component\HttpFoundation\Request;


class DefaultController extends Controller
{
    /**
	 * @Route("", name="index")
	 */
	public function indexAction()
	{
		return $this->render('default/index.html.twig');
	}
}