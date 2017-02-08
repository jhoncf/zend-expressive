<?php
/**
 * Created by PhpStorm.
 * User: jhonatas
 * Date: 13/02/2017
 * Time: 20:55
 */

namespace App\Container;

use Doctrine\Common\Cache\RedisCache;
use Interop\Container\ContainerInterface;

class DoctrineRedisCacheFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $config = $container->has('config') ? $container->get('config') : [];

        $redis = new \Redis();
        $redis->connect(
            $config['doctrine']['cache']['redis']['host'],
            $config['doctrine']['cache']['redis']['port']
        );

        $cache = new RedisCache();
        $cache->setRedis($redis);

        return $cache;
    }
}