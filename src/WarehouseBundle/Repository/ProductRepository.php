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
		return $this
			->createQueryBuilder('p')
			->leftJoin('p.store', 's')
			->where('s.id = :sid')
			->setParameter('sid', $storeId)
			->getQuery()
			->getResult();
	}
	
	public function findAllInGroupNotInStore($groupId, $storeId)
	{
		return $this
			->createQueryBuilder('p')
			->leftJoin('p.group', 'g')
			->where('g.id = :gid')
			->setParameter('gid', $groupId)
			->leftJoin('g.store','s')
			->where('s.id != :sid')
			->setParameter('sid', $storeId)
			->getQuery()
			->getResult();
	}
	
	public function findByData($data)
	{
		$ret = $this
			->createQueryBuilder('p')
			->leftJoin('p.metaProduct','mp')
			->leftJoin('p.store','st')
		;
		
		if(!empty($data['name'])){
			$ret
				->andWhere('mp.name LIKE :lit')
				->setParameter('lit', '%'.$data['name'].'%');
		}
		
		if(!empty( $data[ 'price_min'])){
			$ret
				->andWhere('mp.price > :pmin')
				->setParameter('pmin',$data['price_min']);
		}
		
		if(!empty($data['price_max'])){
			$ret
				->andWhere('mp.price < :pmax')
				->setParameter('pmax',$data['price_max']);
		}
		
		if(!empty($data['amount_max'])){
			$ret
				->andWhere('p.amount < :amax')
				->setParameter('amax',$data['amount_max']);
		}
		
		if(!empty($data['amount_min'])){
			$ret
				->andWhere('p.amount > :amin')
				->setParameter('amin',$data['amount_min']);
		}
		
		if(!empty($data['expireAt_min'])){
			$date = date_create();
			$date->modify('+'.$data['expireAt_min'].' days');
			$ret
				->andWhere('p.expireAt > :t')
				->setParameter('t',$date->format('Y-m-d'));
		}
		
		if(!empty($data['expireAt_max'])){
			$date = date_create();
			$date->modify('+'.$data['expireAt_max'].' days');
			$ret
				->andWhere('p.expireAt < :t')
				->setParameter('t',$date->format('Y-m-d'));
		}
		
		#todo - przetestowaæ
		if(!empty($data['store'])){
			$ret
				->andWhere('st.id = :store')
				->setParameter('store', $data['store']->id);
		}
				
		return $ret->getQuery()
				->getResult();
	}
}