<?php
/**
 * Created by PhpStorm.
 * User: jhonatas
 * Date: 11/07/16
 * Time: 10:36
 */

namespace App\Admin\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Exceptions\DUsersExceptions;

class UserPermissionsRepository extends EntityRepository{
	/**
	 * @param UserPermissions $entity
	 * @return array
	 */
	public function read(UserPermissions $entity){

		$query = $this->_em->createQueryBuilder()
		                   ->select('c')
		                   ->from('App\Admin\Entity\UserPermissions', 'c')
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
			$dql = "SELECT c FROM App\Admin\Entity\UserPermissions c ORDER BY c.id";
			$query = $this->_em->createQuery($dql);
			$results = new Paginator($query);
			$totalResults = count($results);
			$return = $results->getQuery()->getArrayResult();

			return ["result" => $return, "count" => $totalResults];
		} catch (\Exception $e) {
			throw new \Exception($e->getMessage());
		}
	}

	/**
	 * @param UserPermissions $entity
	 * @return mixed
	 * @throws \Exception
	 */
	public function save(UserPermissions $entity) {
		try {
			$entity->setCreated(date('Y-m-d H:i:s'));

			$this->_em->persist($entity);
			$this->_em->flush();
		} catch (\Exception $e) {
			throw new \Exception($e->getMessage());
		}

		return $entity->getId();
	}

	/**
	 * @param UserPermissions $entity
	 * @return array
	 * @throws \Exception
	 */
	public function update(UserPermissions $entity) {
		try {
			$this->_em->merge($entity);
			$this->_em->flush();
		} catch (\Exception $e) {
			throw new \Exception($e->getMessage());
		}
		return ['id' => $entity->getId()];
	}

	/**
	 * @param $id
	 * @return bool
	 * @throws DUsersExceptions
	 * @throws \Exception
	 */
	public function delete($id) {
		/**
		 * @var $entity UserPermissions
		 */
		$entity = $this->find($id);
		if(empty($entity)){
			throw new DUsersExceptions('Permissão inválida ou não encontrado.');
		}

		try {
			$this->_em->remove($entity);
			$this->_em->flush();
			return true;
		}catch (\Exception $e){
			throw new \Exception($e->getMessage());
		}
	}
}