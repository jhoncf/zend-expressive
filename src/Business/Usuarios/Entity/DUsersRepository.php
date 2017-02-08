<?php
/**
 * Created by PhpStorm.
 * User: jhonatas
 * Date: 05/07/16
 * Time: 14:48
 */

namespace Business\Usuarios\Entity;

use Business\Usuarios\Action\UsuariosSearch;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping;
use Exceptions\DUsersExceptions;

class DUsersRepository extends EntityRepository {

    private $qb;

    public function __construct(EntityManager $em, Mapping\ClassMetadata $class) {
        parent::__construct($em, $class);
        $this->qb = $this->_em->createQueryBuilder();
    }

    /**
	 * @param DUsers $entity
	 * @return array
	 * @throws \Exception
	 */
	public function read(DUsers $entity) {

		try {
			$query = $this->_em->createQueryBuilder()
			                   ->select('c', 'dprof', 'dperm', 'dcomp', 'dnewsl', 'dprod')
			                   ->from('\Business\Usuarios\Entity\DUsers', 'c')
			                   ->leftJoin('c.dCompanies', 'dcomp')
			                   ->leftJoin('c.dProfile', 'dprof')
			                   ->leftJoin('dprof.dPermission', 'dperm')
			                   ->leftJoin('dperm.dProduct', 'dprod')
			                   ->leftJoin('c.dNewsletter', 'dnewsl')
			                   ->where('c.deleted != :deleted')
			                   ->andWhere('c = :param')
			                   ->setParameters([
				                   'param' => $entity,
				                   'deleted' => 1
			                   ])
			                   ->getQuery();

			if ($query->getArrayResult() == null) {
				throw new DUsersExceptions('Usuário não encontrado ou removido.');
			}

			$return = $query->getArrayResult();
			unset($return[0]['password']);
		} catch (\Exception $e) {
			throw new \Exception($e->getMessage());
		}

		return $return[0];
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
			                   ->select('partial c.{id,name,surname,email,created,modified,blocked,telefone}', 'dprof', 'dcomp', 'dnewsl')
			                   ->from('\Business\Usuarios\Entity\DUsers', 'c')
			                   ->leftJoin('c.dCompanies', 'dcomp')
			                   ->leftJoin('c.dProfile', 'dprof')
			                   ->leftJoin('c.dNewsletter', 'dnewsl')
			                   ->where('c.deleted != :deleted')
			                   ->setParameters([
				                   'deleted' => 1
			                   ])
			                   ->getQuery();

			$result = $query->getArrayResult();

			return [
				"result" => $result,
				"count" => count($result)
			];
		} catch (\Exception $e) {
			throw new \Exception($e->getMessage());
		}
	}

    /**
     * @return mixed
     * @throws \Exception
     */

	public function findProfilesUsers(){
        try {
            $this->qb->select('dUsers','dCompanies','dProfile');
            $this->qb->from('Business\Usuarios\Entity\DUsers', 'dUsers');
            $this->qb->leftJoin('dUsers.dCompanies', 'dCompanies');
            $this->qb->leftJoin('dUsers.dProfile', 'dProfile');

            //$this->qb->leftJoin('dPlans.dProduct', 'dProduct');

            $query = $this->qb->getQuery();
            $result = $query->execute(array(), \Doctrine\ORM\AbstractQuery::HYDRATE_SCALAR);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

        return $result;
    }
	/**
	 * function findByParams
	 *
	 * @see UsuariosSearch
	 *
	 * @param $params
	 * @return array
	 * @throws \Exception
	 */
	public function findByParams($params) {
		try {
			$query = $this->_em->createQueryBuilder();
			$query->select('partial c.{id,name,surname,email,created,modified,blocked}', 'dcomp', 'dnewsl');
			$query->from('\Business\Usuarios\Entity\DUsers', 'c');
			$query->leftJoin('c.dCompanies', 'dcomp');
			$query->leftJoin('c.dNewsletter', 'dnewsl');
			$query->where("c.deleted != '1'");

			foreach ($params as $key => $value) {
				if (is_array($value)) {
					$query->andWhere("{$key} IN (:ids)");
					$query->setParameter('ids', $value);
				} else {
					$query->andWhere("{$key} LIKE '{$value}'");
				}
			}

			$result = $query->getQuery()
			                ->getArrayResult();

		} catch (\Exception $e) {
			throw new \Exception($e->getMessage());
		}

		return [
			"result" => $result,
			"count" => count($result)
		];
	}

	/**
	 *
	 */
	public function findByCompanyId($companyId) {
		try {
			$dCompanies = $this->_em->getRepository('\Business\Empresas\Entity\DCompanies');
			$dCompaniesEntity = $dCompanies->find($companyId);

			$query = $this->_em->createQueryBuilder()
			                   ->select('c', 'dcomp', 'dplan', 'dprod', 'dprof', 'dperm')
			                   ->from('\Business\Usuarios\Entity\DUsers', 'c')
			                   ->leftJoin('c.dCompanies', 'dcomp')
			                   ->leftJoin('c.dProfile', 'dprof')
			                   ->leftJoin('dprof.dPermission', 'dperm')
			                   ->leftJoin('dcomp.dPlan', 'dplan')
			                   ->leftJoin('dplan.dProduct', 'dprod')
			                   ->where('dcomp = :param')
			                   ->setParameters(['param' => $dCompaniesEntity])
			                   ->getQuery();

			if ($query->getArrayResult() == null) {
				return false;
			}

			$return = $query->getArrayResult();
		} catch (\Exception $e) {
			throw new \Exception($e->getMessage());
		}

		return $return;
	}

	/**
	 * @param $email
	 * @return array|bool
	 * @throws \Exception
	 */
	public function findByEmail($email) {
		try {
			$query = $this->_em->createQueryBuilder()
			                   ->select('c', 'dcomp', 'dplan', 'dprod', 'dprof', 'dperm')
			                   ->from('\Business\Usuarios\Entity\DUsers', 'c')
			                   ->leftJoin('c.dCompanies', 'dcomp')
			                   ->leftJoin('c.dProfile', 'dprof')
			                   ->leftJoin('dprof.dPermission', 'dperm')
			                   ->leftJoin('dcomp.dPlan', 'dplan')
			                   ->leftJoin('dplan.dProduct', 'dprod')
			                   ->andWhere('c.email = :param')
			                   ->setParameters(['param' => $email])
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
	 * @param $key
	 * @return bool
	 * @throws \Exception
	 */
	public function findByActivationKey($key) {
		try {
			$query = $this->_em->createQueryBuilder()
			                   ->select('c', 'dcomp', 'dplan', 'dprod', 'dprof', 'dperm')
			                   ->from('\Business\Usuarios\Entity\DUsers', 'c')
			                   ->leftJoin('c.dCompanies', 'dcomp')
			                   ->leftJoin('c.dProfile', 'dprof')
			                   ->leftJoin('dprof.dPermission', 'dperm')
			                   ->leftJoin('dcomp.dPlan', 'dplan')
			                   ->leftJoin('dplan.dProduct', 'dprod')
			                   ->where('c.activationKey = :activationKey')
			                   ->setParameters(['activationKey' => $key])
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
	 * @param DUsers $entity
	 * @return int
	 * @throws \Exception
	 */
	public function save(DUsers $entity) {
		try {
			$this->_em->persist($entity);
			$this->_em->flush();
		} catch (\Exception $e) {
			throw new \Exception($e->getMessage());
		}

		return $entity->getId();
	}

	/**
	 * @param DUsers $entity
	 * @return array
	 * @throws \Exception
	 */
	public function update(DUsers $entity) {
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
		 * @var $entity DUsers
		 */
		$entity = $this->find($id);

		if (empty($entity)) {
			throw new DUsersExceptions('Usuário inválido ou não encontrado.');
		}

		/**
		 * @var $entity DUsers
		 */
		$entity->setDeleted(true);
		$entity->setDeletedDate(new \DateTime());
		try {
			$this->_em->merge($entity);
			$this->_em->flush();
			return true;
		} catch (\Exception $e) {
			throw new \Exception($e->getMessage());
		}
	}
}