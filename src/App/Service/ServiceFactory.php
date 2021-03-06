<?php
/**
 * Created by PhpStorm.
 * User: jhonatas
 * Date: 13/02/2017
 * Time: 11:03
 */

namespace App\Service;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class ServiceFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container,  $requestedName, array $options = null){
        $config = $container->has('config') ? $container->get('config') : [];
        $args = isset($config[$requestedName])? $config[$requestedName] : " ";
        return new $requestedName($container,$args);
    }
}