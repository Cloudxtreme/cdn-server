<?php

namespace CDNServer\Core\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

/**
 * 
 *
 * @author Pierre LECERF <pierre.lecerf@gmail.com>
 */
class ResourceRepository extends EntityRepository
{
	public function findOneByProjectAndName($project_id, $name)
	{
		return $this->getQueryBuilder()
		->join('r.project', 'p')
		->where('p.id = :project_id')
        ->andWhere('r.name = :name')
		->setParameter('project_id', $project_id)
        ->setParameter('name', $name)
		->getQuery()
		->getOneOrNullResult();
	}
	
	/**
	 * @return QueryBuilder
	 */
	private function	getQueryBuilder()	{ return $this->getEntityManager()->createQueryBuilder()->select('r')->from('CDNServerCore:Resource', 'r'); }
}
