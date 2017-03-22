<?php
/**
 * Created by PhpStorm.
 * User: ervin
 * Date: 22.03.2017
 * Time: 20:33
 */

namespace Admin\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Admin\Service\AdminService;

class AdminServiceFactory {
    public function __invoke(ContainerInterface $container,
                             $requestedName, array $options = null) {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $config = $container->get('config');

        return new AdminService($entityManager, $config['site_config']);
    }
}