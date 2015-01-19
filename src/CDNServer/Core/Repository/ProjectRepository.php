<?php

namespace CDNServer\Core\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

/**
 * 
 *
 * @author Pierre LECERF <pierre.lecerf@gmail.com>
 */
class ProjectRepository extends EntityRepository
{
	public function findByUserGroupIn(array $userGroups)
	{
		return $this->getQueryBuilder()
		->join('p.userGroup', 'ug')
		->where('ug.id IN (:user_group_ids)')
		->setParameter('user_group_ids', $userGroups)
		->getQuery()
		->getResult();
	}
	
	/**
	 * @return QueryBuilder
	 */
	private function	getQueryBuilder()	{ return $this->getEntityManager()->createQueryBuilder()->select('p')->from('CDNServerCore:Project', 'p'); }
}
