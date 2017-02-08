<?php
/**
 * Created by PhpStorm.
 * User: jhonatas
 * Date: 11/07/16
 * Time: 10:19
 */

namespace App\Admin\Entity;

use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityRepository;
use Exceptions\DUsersExceptions;

class UserUsersRepository extends EntityRepository {
	/**
	 * @param UserUsers $entity
	 * @return array
	 * @throws \Exception
	 */
	public function read(UserUsers $entity) {

		try {
			$query = $this->_em->createQueryBuilder()
			                   ->select('users', 'userP')
			                   ->from('\App\Admin\Entity\UserUsers', 'users')
			                   ->leftJoin('users.userProfile', 'userP')
			                   ->where('users = :param')
			                   ->setParameter('param', $entity)
			                   ->getQuery();

			$return = $query->getSingleResult(AbstractQuery::HYDRATE_ARRAY);
			unset($return['password']);
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
			$query = $this->_em->createQueryBuilder()
			                   ->select('c.id, c.username, c.name, c.surname, c.email, c.created, c.modified')
			                   ->from('\App\Admin\Entity\UserUsers', 'c')
			                   ->getQuery();
			$return = $query->getArrayResult();

			return [
				"result" => $return,
				"count" => count($return)
			];
		} catch (\Exception $e) {
			throw new \Exception($e->getMessage());
		}
	}

	public function findByUserName($username) {
		try {
			$query = $this->_em->createQueryBuilder()
			                   ->select('c')
			                   ->from('\App\Admin\Entity\UserUsers', 'c')
			                   ->andWhere('c.username = :param')
			                   ->setParameters(['param' => $username])
			                   ->getQuery();

			if ($query->getArrayResult() == null) {
				return false;
			}

			$return = $query->getArrayResult();
		} catch (\Exception $e) {
			throw new \Exception($e->getMessage());
		}

		return $return[0];
	}

	/**
	 * @param UserUsers $entity
	 * @return int
	 * @throws \Exception
	 */
	public function save(UserUsers $entity) {
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
	 * @param UserUsers $entity
	 * @return array
	 * @throws \Exception
	 */
	public function update(UserUsers $entity) {
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
		 * @var $entity UserUsers
		 */
		$entity = $this->find($id);

		if (empty($entity)) {
			throw new DUsersExceptions('Usuário inválido ou não encontrado.');
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