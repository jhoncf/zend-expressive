<?php
/**
 * Created by PhpStorm.
 * User: Dcide
 * Date: 18/05/2016
 * Time: 19:57
 */

namespace App\Helper;


use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class HelperFormFactory implements FactoryInterface{
    
    public function __invoke(ContainerInterface $container,  $requestedName, array $options = null){
        $config = $container->has('config') ? $container->get('config') : [];
        $args = isset($config['REQUIRED-INPUTS'][$requestedName])? $config['REQUIRED-INPUTS'][$requestedName] : " ";
        return new $requestedName($container,$args);
    }
}