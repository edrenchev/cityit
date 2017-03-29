<?php

namespace Application\Service;

use Application\Entity\StaticPage;
use Doctrine\ORM\Query\ResultSetMapping;
use Admin\Libs\Helper;

class StaticService {

    /**
     * Doctrine entity manager.
     * @var Doctrine\ORM\EntityManager
     */
    private $entityManager;

    private $siteConfig;

    // Constructor is used to inject dependencies into the service.
    public function __construct($entityManager, $siteConfig) {
        $this->entityManager = $entityManager;
        $this->siteConfig = $siteConfig;
    }
}