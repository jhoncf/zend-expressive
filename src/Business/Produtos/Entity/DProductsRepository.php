<?php
/**
 * Created by PhpStorm.
 * User: jhonatas
 * Date: 05/07/16
 * Time: 14:40
 */

namespace Business\Produtos\Entity;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping;
use Exceptions\DUsersExceptions;

class DProductsRepository extends EntityRepository {

    private $qb;

    public function __construct(EntityManager $em, Mapping\ClassMetadata $class) {
        parent::__construct($em, $class);
        $this->qb = $this->_em->createQueryBuilder();
    }

    /**
     * @param DProducts $entity
     * @return array
     */
    public function read(DProducts $entity) {

        $query = $this->_em->createQueryBuilder()->select('c')->from('\Business\Produtos\Entity\DProducts', 'c')
            ->setParameter('param', $entity)->where('c = :param')->getQuery();

        $return = $query->getArrayResult();

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
            $this->qb->select('dprod');
            $this->qb->from('Business\Produtos\Entity\DProducts', 'dprod');
            $query = $this->qb->getQuery();
            $result = $query->getArrayResult();
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

        return [
            "result" => $result,
            "count" => count($result)
        ];
    }

    public function findProductUsers() {
        try {
            $this->qb->select('dPlans','dProduct','dCompany');
            $this->qb->from('Business\Planos\Entity\DPlans', 'dPlans');
            $this->qb->leftJoin('dPlans.dProduct', 'dProduct');
            $this->qb->leftJoin('dPlans.dCompany', 'dCompany');

            $query = $this->qb->getQuery();
            $result = $query->execute(array(), \Doctrine\ORM\AbstractQuery::HYDRATE_SCALAR);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

        return $result;
    }

    /**
     * @param DProducts $entity
     * @return int
     * @throws \Exception
     */
    public function save(DProducts $entity) {
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
     * @param DProducts $entity
     * @return bool
     * @throws \Exception
     */
    public function update(DProducts $entity) {
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
         * @var $entity DProducts
         */
        $entity = $this->find($id);
        if (empty($entity)) {
            throw new DUsersExceptions('Produto inválido ou não encontrado.');
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