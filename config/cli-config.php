<?php
/**
 * Created by PhpStorm.
 * User: jhonatas
 * Date: 01/02/2017
 * Time: 11:10
 */


use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Doctrine\ORM\EntityManager;

require 'vendor/autoload.php';

/** @var \Interop\Container\ContainerInterface $container */
$container = require 'config/container.php';

/** @var \Doctrine\ORM\EntityManager $em */
$em = $container->get(EntityManager::class);

return ConsoleRunner::createHelperSet($em);