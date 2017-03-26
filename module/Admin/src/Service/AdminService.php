<?php

/**
 * Created by PhpStorm.
 * User: ervin
 * Date: 22.03.2017
 * Time: 20:18
 */

namespace Admin\Service;

use Admin\Entity\MenuLng;
use Doctrine\ORM\Query\ResultSetMapping;
use Libs\Admin\Helper;

class AdminService {

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

    public function insert($insertData, $entityName) {

        $prepareData = [];
        foreach ($insertData as $key => $value) {
            $invertTransformField = Helper::inverseTransformField($key);
            $prepareData[$invertTransformField['table']][$invertTransformField['column']] = $value;
        }

        $recordId = 0;
        foreach ($prepareData as $table => $item) {
            if ($table == 't') {
                $className = $entityName;
                $t = new $className();
                $t->setOptions($item);
                $this->entityManager->persist($t);
            } elseif (isset($this->siteConfig['languages'][$table])) {
                $className = $entityName . 'Lng';
                $tLng = new $className();
                $tLng->setMid($t);
                $tLng->setLng($table);
                $tLng->setOptions($item);
                $this->entityManager->persist($tLng);
            }
        }
        $this->entityManager->flush();
        $recordId = $t->getId();

        return $recordId;
    }

    public function update($recordId, $data, $entityName) {
        $prepareData = [];
        foreach ($data as $key => $value) {
            $invertTransformField = Helper::inverseTransformField($key);
            $prepareData[$invertTransformField['table']][$invertTransformField['column']] = $value;
        }

        foreach ($prepareData as $table => $item) {
            if ($table == 't') {
                $t = $this->entityManager->getRepository($entityName)->findOneBy(['id' => $recordId]);
                $t->setOptions($item);
                $this->entityManager->persist($t);
            } elseif (isset($this->siteConfig['languages'][$table])) {
                $tLng = $this->entityManager->getRepository("{$entityName}Lng")->findOneBy(['mid' => $recordId, 'lng' => $table]);
                if($tLng === null) {
                    $className = "{$entityName}Lng";
                    $tLng = new $className();
                    $tLng->setMid($t);
                    $tLng->setLng($table);
                }
                $tLng->setOptions($item);
                $this->entityManager->persist($tLng);
            }
        }
        $this->entityManager->flush();

        return $t->getId();
    }

    public function delete($ids, $entityName) {
        if(!empty($ids)) {
            $qb = $this->entityManager->createQueryBuilder();
            $qb->delete($entityName, 't')->where("t.id IN ({$ids})");
            $qb->getQuery()->execute();

            $qb = $this->entityManager->createQueryBuilder();
            $qb->delete("{$entityName}Lng", 'tLng')->where("tLng.mid IN ($ids)");
            $qb->getQuery()->execute();
        }
    }

}