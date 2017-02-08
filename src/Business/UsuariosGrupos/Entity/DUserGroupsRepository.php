<?php
/**
 * Created by PhpStorm.
 * User: jhonatas
 * Date: 05/07/16
 * Time: 14:48
 */

namespace Business\UsuariosGrupos\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Exceptions\DUsersExceptions;

class DUserGroupsRepository extends EntityRepository {

	/**
	 * @param DUserGroups $entity
	 * @return array
	 * @throws \Exception
	 */
	public function read(DUserGroups $entity) {

		try {
			$query = $this->_em->createQueryBuilder()
			                   ->select('c')
			                   ->from('\Business\UsuariosGrupos\Entity\DUserGroups', 'c')
			                   ->where('c = :param')
			                   ->setParameters(['param' => $entity])
			                   ->getQuery();

			if ($query->getArrayResult() == null) {
				throw new DUsersExceptions('Grupo não encontrado ou removido.');
			}

			$return = $query->getArrayResult();
		} catch (\Exception $e) {
			throw new \Exception($e->getMessage());
		}

		return ["result" => $return];
	}

	/**
	 * @param int $page
	 * @param int $limit
	 * @return array
	 * @throws \Exception
	 */
	public function findAll($page = 0, $limit = null) {
		try {
			$dql = "SELECT c FROM Business\UsuariosGrupos\Entity\DUserGroups c ORDER BY c.id";
			$query = $this->_em->createQuery($dql)
			                   ->setFirstResult($page)
			                   ->setMaxResults($limit);
			$results = new Paginator($query);
			$totalResults = count($results);
			$return = $results->getQuery()
			                  ->getArrayResult();

			return ["result" => $return, "count" => $totalResults];
		} catch (\Exception $e) {
			throw new \Exception($e->getMessage());
		}
	}

	/**
	 * @param DUserGroups $entity
	 * @return int
	 * @throws \Exception
	 */
	public function save(DUserGroups $entity) {
		try {
			$this->_em->persist($entity);
			$this->_em->flush();
		} catch (\Exception $e) {
			throw new \Exception($e->getMessage());
		}

		return $entity->getId();
	}

	/**
	 * @param DUserGroups $entity
	 * @return bool
	 * @throws \Exception
	 */
	public function update(DUserGroups $entity) {
		try {
			$this->_em->merge($entity);
			$this->_em->flush();
			return ['id' => $entity->getId()];
		} catch (\Exception $e) {
			throw new \Exception($e->getMessage());
		}
	}

	/**
	 * @param $id
	 * @return bool
	 * @throws DUsersExceptions
	 * @throws \Exception
	 */
	public function delete($id) {
		/**
		 * @var $entity DUserGroups
		 */
		$entity = $this->find($id);

		if (empty($entity)) {
			throw new DUsersExceptions('Grupo inválido ou não encontrado.');
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