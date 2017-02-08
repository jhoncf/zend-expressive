<?php
/**
 * Created by PhpStorm.
 * User: jhonatas
 * Date: 04/07/16
 * Time: 17:28
 */

namespace Business\Permissoes\Entity;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping;
use Exceptions\DUsersExceptions;

class DPermissionsRepository extends EntityRepository {
    private $qb;

    public function __construct(EntityManager $em, Mapping\ClassMetadata $class) {
        parent::__construct($em, $class);
        $this->qb = $this->_em->createQueryBuilder();
    }

    /**
     * @param DPermissions $entity
     * @return array
     * @throws \Exception
     */
    public function read(DPermissions $entity) {

        try {
            $query = $this->_em->createQueryBuilder()->select($entity::PREFIX, 'dprod')
                ->from('Business\Permissoes\Entity\DPermissions', $entity::PREFIX)
                ->leftJoin($entity::PREFIX . '.dProduct', 'dprod')->where(DPermissions::PREFIX . ' = :param')
                ->setParameter('param', $entity)->getQuery();
            $result = $query->getArrayResult();
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

        return $result[0];
    }

    /**
     * @param $productId
     * @return array
     */
    public function findByProductId($productId) {
        $query = $this->_em->createQueryBuilder()->select('dperm', 'dprod')
            ->from('Business\Permissoes\Entity\DPermissions', 'dperm')->leftJoin('dperm.dProduct', 'dprod')
            ->where('dperm.dProduct = ?1')->setParameter(1, $productId)->getQuery();

        $result = $query->getArrayResult();
        return $result;
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function findAll() {
        try {
            $query = $this->_em->createQueryBuilder()->select('dperm', 'dprod')
                ->from('Business\Permissoes\Entity\DPermissions', 'dperm')->leftJoin('dperm.dProduct', 'dprod')
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
     * @return mixed
     * @throws \Exception
     */
    public function findPerfisPermissoes() {
        try {
            $this->qb->select('dPermissions', 'dProfile','dProduct');
            $this->qb->from('Business\Permissoes\Entity\DPermissions', 'dPermissions');
            $this->qb->leftJoin('dPermissions.dProfile', 'dProfile');
            $this->qb->leftJoin('dPermissions.dProduct', 'dProduct');

            $query = $this->qb->getQuery();
            $result = $query->execute(array(), \Doctrine\ORM\AbstractQuery::HYDRATE_SCALAR);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

        return $result;
    }

    /**
     * @param DPermissions $entity
     * @return mixed
     * @throws \Exception
     */
    public function save(DPermissions $entity) {
        try {
            $this->_em->persist($entity);
            $this->_em->flush();
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

        return $entity->getId();
    }

    /**
     * @param DPermissions $entity
     * @return array
     * @throws \Exception
     */
    public function update(DPermissions $entity) {
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
         * @var $entity DPermissions
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