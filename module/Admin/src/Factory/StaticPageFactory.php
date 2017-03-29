<?php
/**
 * Created by PhpStorm.
 * User: ervin
 * Date: 29.03.2017
 * Time: 21:23
 */

namespace Admin\Factory;

use Admin\Service\AdminService;
use Interop\Container\ContainerInterface;
use Admin\Controller\StaticPageController;


class StaticPageFactory {
    public function __invoke(ContainerInterface $container,
                             $requestedName, array $options = null) {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $config = $container->get('config');

        // The $container variable is the service manager.
        $sessionContainer = $container->get('Admin');

        $adminService = $container->get(AdminService::class);

        // Instantiate the controller and inject dependencies
        return new StaticPageController($entityManager, $config['siteConfig'], $sessionContainer, $adminService);
    }
}