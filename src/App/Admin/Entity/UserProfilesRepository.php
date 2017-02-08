<?php
/**
 * Created by PhpStorm.
 * User: jhonatas
 * Date: 11/07/16
 * Time: 10:37
 */

namespace App\Admin\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Exceptions\DUsersExceptions;

class UserProfilesRepository extends EntityRepository {

	/**
	 * @param UserProfiles $entity
	 * @return array
	 */
	public function read(UserProfiles $entity) {

		$query = $this->_em->createQueryBuilder()
		                   ->select('c', 'userPer')
		                   ->from('App\Admin\Entity\UserProfiles', 'c')
		                   ->leftJoin('c.userPermission', 'userPer')
		                   ->where('c = :param')
		                   ->setParameter('param', $entity)
		                   ->getQuery();

		$return = $query->getArrayResult();
		return ["result" => $return[0]];
	}

	/**
	 * @param int $page
	 * @param int $limit
	 * @return array
	 * @throws \Exception
	 */
	public function findAll($page = 0, $limit = 20) {
		try {

			$dql = "SELECT c FROM App\Admin\Entity\UserProfiles c ORDER BY c.id";
			$query = $this->_em->createQuery($dql)
			                   ->setFirstResult($page)
			                   ->setMaxResults($limit);
			$results = new Paginator($query);
			$totalResults = count($results);
			$return = $results->getQuery()
			                  ->getArrayResult();

			return [
				"result" => $return,
				"count" => $totalResults
			];
		} catch (\Exception $e) {
			throw new \Exception($e->getMessage());
		}
	}

	/**
	 * @param UserProfiles $entity
	 * @return mixed
	 * @throws \Exception
	 */
	public function save(UserProfiles $entity) {
		try {
			$entity->setCreated(date('Y-m-d H:i:s'));

			$this->_em->persist($entity);
			$this->_em->flush();
		} catch (\Exception $e) {
			throw new \Exception($e->getMessage());
		}

		return ['id' => $entity->getId()];
	}

	/**
	 * @param UserProfiles $entity
	 * @return array
	 * @throws \Exception
	 */
	public function update(UserProfiles $entity) {
		try {
			$this->_em->merge($entity);
			$this->_em->flush();
		} catch (\Exception $e) {
			throw new \Exception($e->getMessage());
		}
		return ['id' => $entity->getId()];
	}

	public function delete($id) {
		/**
		 * @var $entity UserProfiles
		 */
		$entity = $this->find($id);
		if (empty($entity)) {
			throw new DUsersExceptions('Perfil inválido ou não encontrado.');
		}
		try {
			$this->_em->remove($entity);
			$this->_em->flush();
			return true;
		} catch (\Exception $e) {
			throw new \Exception($e->getMessage());
		}
	}
}