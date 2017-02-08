<?php
/**
 * Created by PhpStorm.
 * User: jhonatas
 * Date: 04/07/16
 * Time: 16:50
 */

namespace Business\Perfis\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Exceptions\DUsersExceptions;

/**
 * Class DProfilesRepository
 * @package Business\Perfis\Entity
 */
class DProfilesRepository extends EntityRepository {

	/**
	 * @param DProfiles $entity
	 * @return array
	 * @throws \Exception
	 */
	public function read(DProfiles $entity) {
		try {
			$query = $this->_em->createQueryBuilder()
			                   ->select('dprof', 'dper', 'dprod')
			                   ->from('Business\Perfis\Entity\DProfiles', 'dprof')
			                   ->leftJoin('dprof.dPermission', 'dper')
			                   ->leftJoin('dper.dProduct', 'dprod')
			                   ->setParameter('param', $entity)
			                   ->where('dprof = :param')
			                   ->getQuery();

			$return = $query->getSingleResult(\Doctrine\ORM\AbstractQuery::HYDRATE_ARRAY);
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
	public function findAll($page = 0, $limit = 20) {
		try {
			$query = $this->_em->createQueryBuilder()
			                   ->select('dprof', 'dper', 'dProd')
			                   ->from('Business\Perfis\Entity\DProfiles', 'dprof')
			                   ->leftJoin('dprof.dPermission', 'dper')
			                   ->leftJoin('dper.dProduct', 'dProd')
			                   ->where('dprof.deleted = 0')
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
	 * @param DProfiles $entity
	 * @return mixed
	 * @throws \Exception
	 */
	public function save(DProfiles $entity) {
		try {
			$entity->setCreated(new \DateTime("now"));
			$this->_em->persist($entity);
			$this->_em->flush();
		} catch (\Exception $e) {
			throw new \Exception($e->getMessage());
		}

		return $entity->getId();
	}

	/**
	 * @param DProfiles $entity
	 * @return object
	 * @throws \Exception
	 */
	public function update(DProfiles $entity) {
		try {
			$this->_em->merge($entity);
			$this->_em->flush();
		} catch (\Exception $e) {
			throw new \Exception($e->getMessage());
		}
		return true;
	}

	public function delete($id) {
		/**
		 * @var $entity DProfiles
		 */
		$entity = $this->find($id);
		if (empty($entity)) {
			throw new DUsersExceptions('Perfil inválido ou não encontrado.');
		}

		$entity->setDeleted(true);
		$entity->setDeletedDate(new \DateTime());

		try {
			$this->_em->flush();
			return true;
		} catch (\Exception $e) {
			throw new \Exception($e->getMessage());
		}
	}
}