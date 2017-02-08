<?php
/**
 * Created by PhpStorm.
 * User: jhonatas
 * Date: 05/07/16
 * Time: 14:48
 */

namespace Business\Planos\Entity;

use Doctrine\ORM\EntityRepository;
use Exceptions\DUsersExceptions;

class DPlansRepository extends EntityRepository {

	/**
	 * @param DPlans $entity
	 * @return array
	 * @throws \Exception
	 */
	public function read(DPlans $entity) {
		try {
			$query = $this->_em->createQueryBuilder()
			                   ->select('pla', 'pro')
			                   ->from('\Business\Planos\Entity\DPlans', 'pla')
			                   ->innerJoin('pla.dProduct', 'pro')
			                   ->setParameter('param', $entity)
			                   ->where('pla = :param')
			                   ->getQuery();

			$return = $query->getArrayResult();
		} catch (\Exception $e) {
			throw new \Exception($e->getMessage());
		}

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
			$query = $this->_em->createQueryBuilder()
			                   ->select('pla', 'pro')
			                   ->from('\Business\Planos\Entity\DPlans', 'pla')
			                   ->innerJoin('pla.dProduct', 'pro')
			                   ->getQuery();

			$return = $query->getArrayResult();
		} catch (\Exception $e) {
			throw new \Exception($e->getMessage());
		}
		return [
			"result" => $return,
			"count" => count($return)
		];
	}

	/**
	 * @param DPlans $entity
	 * @return int
	 * @throws \Exception
	 */
	public function save(DPlans $entity) {
		try {
			$this->_em->persist($entity);
			$this->_em->flush();
		} catch (\Exception $e) {
			throw new \Exception($e->getMessage());
		}

		return $entity->getId();
	}

	/**
	 * @param DPlans $entity
	 * @return array
	 * @throws \Exception
	 */
	public function update(DPlans $entity) {
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
		 * @var $entity DPlans
		 */
		$entity = $this->find($id);
		if (empty($entity)) {
			throw new DUsersExceptions('Permissão inválido ou não encontrado.');
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