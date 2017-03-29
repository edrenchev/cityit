<?php

namespace Application\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Application\Service\StaticPage;

class StaticPageFactory {
    public function __invoke(ContainerInterface $container,
                             $requestedName, array $options = null) {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $config = $container->get('config');

        return new StaticPage($entityManager, $config['siteConfig']);
    }
}