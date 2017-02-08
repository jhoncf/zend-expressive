<?php
/**
 * Created by PhpStorm.
 * User: jhonatas
 * Date: 08/07/16
 * Time: 14:52
 */

namespace Business\Newsletter\Entity;

use Doctrine\ORM\EntityRepository;
use Exceptions\DUsersExceptions;

/**
 * Class DNewslettersRepository
 * @package Business\Newsletter\Entity
 */
class DNewslettersRepository extends EntityRepository {

	/**
	 * @param DNewsletters $entity
	 * @return array
	 * @throws \Exception
	 */
	public function read(DNewsletters $entity) {
		try {
			$query = $this->_em->createQueryBuilder()
			                   ->select('dnewsl')
			                   ->from('Business\Newsletter\Entity\DNewsletters', 'dnewsl')
			                   ->where('dnewsl = :param')
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
			                   ->select('dnewsl')
			                   ->from('Business\Newsletter\Entity\DNewsletters', 'dnewsl')
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
	 * @param DNewsletters $entity
	 * @return int
	 * @throws \Exception
	 */
	public function save(DNewsletters $entity) {
		try {
			$this->_em->persist($entity);
			$this->_em->flush();
		} catch (\Exception $e) {
			throw new \Exception($e->getMessage());
		}

		return $entity->getId();
	}

	/**
	 * @param DNewsletters $entity
	 * @return array
	 * @throws \Exception
	 */
	public function update(DNewsletters $entity) {
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
		 * @var $entity DNewsletters
		 */
		$entity = $this->find($id);
		if (empty($entity)) {
			throw new DUsersExceptions('Dado inválido ou não encontrado.');
		}

		try {
			$this->_em->remove($entity);
			$this->_em->flush();
		} catch (\Exception $e) {
			throw new DUsersExceptions($e->getMessage());
		}
		return true;
	}
}