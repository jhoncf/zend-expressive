<?php
/**
 * Created by PhpStorm.
 * User: jhonatas
 * Date: 28/06/16
 * Time: 10:12
 */

namespace Business\Empresas\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * Class DCompaniesRepository
 * @package Business\Empresas\Entity
 */
class DCompaniesRepository extends EntityRepository {

	/**
	 * @var null
	 */
	private $result = null;

	/**
	 * @var null
	 */
	private $totalResult = null;

	/**
	 *
	 */
	public function getResult() {
		return $this->result;
	}

	/**
	 *
	 */
	public function getTotal() {
		return $this->totalResult;
	}


	/**
	 * @param DCompanies $entity
	 * @return array
	 * @throws \Exception
	 */
	public function read(DCompanies $entity) {

		try {
			$query = $this->_em->createQueryBuilder()
			                   ->select('c','dPlan')
			                   ->from('Business\Empresas\Entity\DCompanies', 'c')
                                ->leftJoin('c.dPlan', 'dPlan')
			                   ->where('c.deleted != :deleted')
			                   ->andWhere('c = :param')
			                   ->setParameters([
				                   'param' => $entity,
				                   'deleted' => 1
			                   ])
			                   ->getQuery();

			$return = $query->getArrayResult();

			if (!isset($return[0])) {
				return false;
			}
		} catch (\Exception $e) {
			throw new \Exception($e->getMessage());
		}

		return ["result" => $return[0]];
	}

	/**
	 * @return $this
	 * @throws \Exception
	 */
	public function findAll() {
		try {
			$query = $this->_em->createQueryBuilder()
			                   ->select('c','dPlan')
			                   ->from('Business\Empresas\Entity\DCompanies', 'c')
                ->leftJoin('c.dPlan', 'dPlan')

                ->where('c.deleted != :param')
			                   ->setParameter('param', 1)
			                   ->getQuery();

			$this->result = $query->getArrayResult();
			$this->totalResult = count($this->result);

			return $this->result;
		} catch (\Exception $e) {
			throw new \Exception($e->getMessage());
		}
	}

	/**
	 * @param DCompanies $entity
	 * @return int
	 * @throws \Exception
	 */

	public function save(DCompanies $entity) {
		try {
			$entity->setCreated(new \DateTime());

			$this->_em->persist($entity);
			$this->_em->flush();
		} catch (\Exception $e) {
			throw new \Exception($e->getMessage());
		}

		return $entity->getId();
	}

	/**
	 * function update()
	 *
	 * @param DCompanies $entity
	 * @return object
	 * @throws \Exception
	 */
	public function update(DCompanies $entity) {
		try {
			$entity->setModified(new \DateTime());

			$saved = $this->_em->merge($entity);
			$this->_em->flush();
			return $saved;
		} catch (\Exception $e) {
			throw new \Exception($e->getMessage());
		}
	}

	/**
	 * @param $id
	 * @throws \Exception
	 */
	public function delete($id) {
		/**
		 * @var $entity DCompanies
		 */
		$entity = $this->find($id);
		$entity->setDeleted(true);
		$entity->setDeletedDate(new \DateTime());

		try {
			$this->_em->merge($entity);
			$this->_em->flush();
		} catch (\Exception $e) {
			throw new \Exception($e->getMessage());
		}
	}

}