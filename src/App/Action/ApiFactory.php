<?php
/**
 * Created by PhpStorm.
 * User: Dcide
 * Date: 12/05/2016
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