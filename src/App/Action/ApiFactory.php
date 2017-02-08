<?php
/**
 * Created by PhpStorm.
 * User: jhonatas
 * Date: 13/02/2017
 * Time: 15:43
 */

namespace App\Action;


use Interop\Container\ContainerInterface;
use Doctrine\ORM\EntityManager;
use Zend\ServiceManager\Factory\FactoryInterface;


class ApiFactory implements FactoryInterface
{
    
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
       return new $requestedName($container,$container->get(EntityManager::class));
    }
}