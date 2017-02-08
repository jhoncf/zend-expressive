<?php
/**
 * Created by PhpStorm.
 * User: jhonatas
 * Date: 26/09/16
 * Time: 10:04
 */

namespace Business\Entities;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;

/**
 * Class DLoginStatusesRepository
 * @package Business\Entities
 */
class DLoginStatusesRepository extends EntityRepository {

    /**
     * @var \Doctrine\ORM\QueryBuilder
     */
    private $qb;

    /**
     * DLoginStatusesRepository constructor.
     * @param EntityManager $em
     * @param ClassMetadata $class
     */
    public function __construct(EntityManager $em, ClassMetadata $class) {
        parent::__construct($em, $class);
        $this->qb = $this->_em->createQueryBuilder();
    }


    /**
     * @param DLoginStatuses $entity
     * @return array
     * @throws \Exception
     */
    public function read(DLoginStatuses $entity) {

        try {
            $query = $this->_em->createQueryBuilder()->select('dLoginStatus')
                ->from('Business\Entities\DLoginStatuses', 'dLoginStatus')->where('dLoginStatus = :param')
                ->setParameter('param', $entity)->getQuery();

            $result = $query->getArrayResult();
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

        return ["result" => $result[0]];
    }

    /**
     * @param $userId
     * @return mixed
     * @throws \Exception
     */
    public function kickSessionByUserId($userId) {
        try {
            $qb = $this->_em->createQueryBuilder();

            $q = $qb->update('Business\Entities\DLoginStatuses', 'dLoginStatuses')->set('dLoginStatuses.active', 0)
                ->where('dLoginStatuses.dUser = ?1')->setParameter(1, $userId)->getQuery();
            $p = $q->execute();

        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

        return $p;
    }

    /**
     * @param $sessionKey
     * @return bool
     * @throws \Exception
     */
    public function findBySessionKey($sessionKey) {
        try {
            $query = $this->_em->createQueryBuilder()->select('dLoginStatus', 'dUser')
                ->from('Business\Entities\DLoginStatuses', 'dLoginStatus')->leftJoin('dLoginStatus.dUser', 'dUser')
                ->where("dLoginStatus.sessionKey = '{$sessionKey}'")->getQuery();

            $result = $query->getArrayResult();

            if (empty($result)) {
                return false;
            }
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

        return $result[0];
    }

    /**
     * @param string $status
     * @param string $rangeDate
     * @return array|bool
     * @throws \Exception
     */
    public function findByStatus($status = '', $rangeDate = '') {
        try {
            $this->qb->select('dLoginStatus', 'dUser', 'dCompanies');
            $this->qb->from('Business\Entities\DLoginStatuses', 'dLoginStatus');
            $this->qb->leftJoin('dLoginStatus.dUser', 'dUser');
            $this->qb->leftJoin('dUser.dCompanies', 'dCompanies');

            if (!empty($status)) {
                $this->qb->where('dLoginStatus.active = :paramStatus');
                $this->qb->setParameter('paramStatus', $status);
            }

            if (!empty($rangeDate)) {
                $this->qb->where("dLoginStatus.lastAccess >= '" . $rangeDate['startDate'] . " 00:00:00'");
                $this->qb->andWhere("dLoginStatus.lastAccess <= '" . $rangeDate['endDate'] . " 23:59:59'");
            }

            $query = $this->qb->getQuery();

            $result = $query->getArrayResult();
            if (empty($result)) {
                return false;
            }
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

        return $result;
    }

    /**
     * @param int $page
     * @param int $limit
     * @return array
     * @throws \Exception
     */
    public function findAll($page = 0, $limit = 20) {
        try {
            $query = $this->_em->createQueryBuilder()->select('dLoginStatus')
                ->from('Business\Entities\DLoginStatuses', 'dLoginStatus')->getQuery();

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
     * @param DLoginStatuses $entity
     * @return int
     * @throws \Exception
     */
    public function save(DLoginStatuses $entity) {
        try {
            $this->_em->persist($entity);
            $this->_em->flush();
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

        return $entity->getId();
    }

    /**
     * @param DLoginStatuses $entity
     * @return array
     * @throws \Exception
     */
    public function update(DLoginStatuses $entity) {
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
     * @throws \Exception
     */
    public function delete($id) {
        /**
         * @var $entity DLoginStatuses
         */
        $entity = $this->find($id);
        if (empty($entity)) {
            return false;
        }

        try {
            $this->_em->remove($entity);
            $this->_em->flush();
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
        return true;
    }
}