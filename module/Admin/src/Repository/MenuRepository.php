<?php

namespace Admin\Repository;

use Doctrine\ORM\EntityRepository;
use Admin\Entity\Menu;
use Libs\Admin\RepositoryHelper;

class MenuRepository extends EntityRepository {

    public function count($filterData, $languages, $searchModel) {

        $entityManager = $this->getEntityManager();
        $queryBuilder = $entityManager->createQueryBuilder();

        $queryBuilder->select('COUNT(t.id) as cnt')
            ->from(\Admin\Entity\Menu::class, 't')
            ->leftJoin(\Admin\Entity\MenuLng::class, 'en_GB', \Doctrine\ORM\Query\Expr\Join::WITH, "t.id=en_GB.menuId AND en_GB.lng='en_GB'")
            ->leftJoin(\Admin\Entity\MenuLng::class, 'bg_BG', \Doctrine\ORM\Query\Expr\Join::WITH, "t.id=bg_BG.menuId AND bg_BG.lng='bg_BG'");

        $queryBuilder = RepositoryHelper::addWhereClause($queryBuilder, $languages, $searchModel, $filterData);

        $result = $queryBuilder->getQuery()->getSingleScalarResult();

        return $result;
    }

    public function getItems($offset, $itemCountPerPage, $filterData, $languages, $searchModel = []) {
        $queryBuilder = $this->getMenus($filterData, $languages, $searchModel);
        $queryBuilder->setFirstResult($offset)->setMaxResults($itemCountPerPage);
        $result = $queryBuilder->getQuery()->getScalarResult();
        return $result;
    }

    public function getMenus($filterData, $languages, $searchModel = []) {
        $entityManager = $this->getEntityManager();

        $queryBuilder = $entityManager->createQueryBuilder();

        $queryBuilder->select(array('t', 'en_GB', 'bg_BG'))
            ->from(\Admin\Entity\Menu::class, 't')
            ->leftJoin(\Admin\Entity\MenuLng::class, 'en_GB', \Doctrine\ORM\Query\Expr\Join::WITH, "t.id=en_GB.menuId AND en_GB.lng='en_GB'")
            ->leftJoin(\Admin\Entity\MenuLng::class, 'bg_BG', \Doctrine\ORM\Query\Expr\Join::WITH, "t.id=bg_BG.menuId AND bg_BG.lng='bg_BG'");

        $queryBuilder = RepositoryHelper::addWhereClause($queryBuilder, $languages, $searchModel, $filterData);

        if (!empty($filterData['orders'])) {
            foreach ($filterData['orders'] as $column => $order) {
                if ($order == '') continue;
                $tmpPos = strrpos($column, '_');
                $table = substr($column, 0, $tmpPos);
                $column = substr($column, $tmpPos + 1);
                $queryBuilder->addOrderBy("{$table}.{$column}", $order);
            }
        }


//        $result = $queryBuilder->getQuery();
        $result = $queryBuilder;

//        var_dump($result->getSql());
        return $result;
    }

}