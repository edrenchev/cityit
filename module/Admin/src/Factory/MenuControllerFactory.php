<?php
/**
 * Created by PhpStorm.
 * User: ervin
 * Date: 12.01.2017
 * Time: 20:50
 */

namespace Admin\Factory;

use Admin\Service\AdminService;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Admin\Controller\MenuController;

class MenuControllerFactory {

    public function __invoke(ContainerInterface $container,
                             $requestedName, array $options = null) {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $config = $container->get('config');

        // The $container variable is the service manager.
        $sessionContainer = $container->get('Admin');

        $adminService = $container->get(AdminService::class);

        // Instantiate the controller and inject dependencies
        return new MenuController($entityManager, $config['site_config'], $sessionContainer, $adminService);
    }
}