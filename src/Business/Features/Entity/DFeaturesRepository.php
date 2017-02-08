<?php
/**
 * Created by PhpStorm.
 * User: jhonatas
 * Date: 08/07/16
 * Time: 14:52
 */

namespace Business\Features\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Exceptions\DUsersExceptions;

class DFeaturesRepository extends EntityRepository {

	/**
	 * @param DFeatures $entity
	 * @return array
	 * @throws \Exception
	 */
	public function read(DFeatures $entity) {

		try {
			$query = $this->_em->createQueryBuilder()
			                   ->select('dfeat', 'dprod')
			                   ->from('Business\Features\Entity\DFeatures', 'dfeat')
			                   ->leftJoin('dfeat.dProduct', 'dprod')
			                   ->where('dfeat = :param')
			                   ->setParameter('param', $entity)
			                   ->getQuery();

			$result = $query->getArrayResult();
		} catch (\Exception $e) {
			throw new \Exception($e->getMessage());
		}

		return ["result" => $result[0]];
	}

	/**
	 * @param int $page
	 * @param int $limit
	 * @return array
	 * @throws \Exception
	 */
	public function findAll($page = 0, $limit = 20) {
		try {
			$query = $this->_em->createQueryBuilder()
			                   ->select('dfeat', 'dprod')
			                   ->from('Business\Features\Entity\DFeatures', 'dfeat')
			                   ->leftJoin('dfeat.dProduct', 'dprod')
			                   ->getQuery();

			$result = $query->getArrayResult();
		} catch (\Exception $e) {
			throw new \Exception($e->getMessage());
		}

		return [
			"result" => $result,
			"count" => count($result)
		];
	}

	/**
	 * @param DFeatures $entity
	 * @return mixed
	 * @throws \Exception
	 */
	public function save(DFeatures $entity) {
		try {
			$this->_em->persist($entity);
			$this->_em->flush();
		} catch (\Exception $e) {
			throw new \Exception($e->getMessage());
		}

		return $entity->getId();
	}

	/**
	 * @param DFeatures $entity
	 * @return bool
	 * @throws \Exception
	 */
	public function update(DFeatures $entity) {
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
		 * @var $entity DFeatures
		 */
		$entity = $this->find($id);
		if (empty($entity)) {
			throw new DUsersExceptions('Feature inválido ou não encontrado.');
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