<?php namespace WarehouseBundle\Repository;


use Doctrine\ORM\EntityRepository;

class ProductRepository extends EntityRepository
{
	/**
	 * 
	 * @param type $storeId
	 * @return type
	 */
	public function findAllInStore($storeId)
	{
		return $this->getEntityManager()
			->createQueryBuilder('p')
			->leftJoin('p.store', 's')
			->where('s.id = :sid')
			->setParameter('sid', $storeId)
			->getQuery()
			->getResult();
	}
}