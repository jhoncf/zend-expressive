<?php
/**
 * Created by PhpStorm.
 * User: jhonatas
 * Date: 16/02/17
 * Time: 11:08
 */

namespace Business\Entities;

use Doctrine\ORM\EntityRepository;

class PostRepository extends EntityRepository {

    /**
     * @return array
     * @throws \Exception
     */
    public function findAll(){
        try {
            $query = $this->_em->createQueryBuilder()
                ->select('post')
                ->from('Business\Entities\Post', 'post')
                ->getQuery();

            $result = $query->getArrayResult();
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

        return $result;
    }
    /**
     * @param Post $entity
     * @return int
     * @throws \Exception
     */
    public function save(Post $entity) {
        try {
            $this->_em->persist($entity);
            $this->_em->flush();
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

        return $entity->getId();
    }

}