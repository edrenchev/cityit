<?php
/**
 * Created by PhpStorm.
 * User: ervin
 * Date: 22.03.2017
 * Time: 20:33
 */

namespace Admin\Factory;

use Admin\Controller\FileController;
use Interop\Container\ContainerInterface;

class FileFactory {
    public function __invoke(ContainerInterface $container,
                             $requestedName, array $options = null) {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        return new FileController($entityManager);
    }
}