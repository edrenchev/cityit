<?php

namespace Admin\Repository;

use Doctrine\ORM\EntityRepository;
use Admin\Entity\Menu;

class MenuRepository extends EntityRepository {

    public function count($filterData, $searchModel) {

        $entityManager = $this->getEntityManager();
        $queryBuilder = $entityManager->createQueryBuilder();

        $queryBuilder->select('COUNT(menu.id) as cnt')
            ->from(\Admin\Entity\Menu::class, 'menu')
            ->leftJoin(\Admin\Entity\MenuLng::class, 'en_GB', \Doctrine\ORM\Query\Expr\Join::WITH, "menu.id=en_GB.menuId AND en_GB.lng='en_GB'")
            ->leftJoin(\Admin\Entity\MenuLng::class, 'bg_BG', \Doctrine\ORM\Query\Expr\Join::WITH, "menu.id=bg_BG.menuId AND bg_BG.lng='bg_BG'");

        if (!empty($searchModel)) {
            $queryBuilder->where('1=1');
            foreach ($searchModel as $key => $item) {
                if (isset($filterData['filter'][$key]) && $filterData['filter'][$key] !== '') {
                    if ($item['comparison'] == 'eq') {
                        $queryBuilder->andWhere("{$item['field']} = :{$key}");
                        $queryBuilder->setParameter($key, $filterData['filter'][$key]);
                    } elseif ($item['comparison'] == 'ge') {
                        $queryBuilder->andWhere("{$item['field']} >= :{$key}");
                        $queryBuilder->setParameter($key, $filterData['filter'][$key]);
                    } elseif ($item['comparison'] == 'le') {
                        $queryBuilder->andWhere("{$item['field']} <= :{$key}");
                        $queryBuilder->setParameter($key, $filterData['filter'][$key]);
                    }
                }
            }
        }

        $result = $queryBuilder->getQuery()->getSingleScalarResult();

        return $result;
    }

    public function getItems($offset, $itemCountPerPage, $filterData, $searchModel = []) {
        $queryBuilder = $this->getMenus($filterData, $searchModel);
        $queryBuilder->setFirstResult($offset)->setMaxResults($itemCountPerPage);
        $result = $queryBuilder->getQuery()->getScalarResult();
        return $result;
    }

    public function getMenus($filterData, $searchModel = []) {
        $entityManager = $this->getEntityManager();

        $queryBuilder = $entityManager->createQueryBuilder();

        $queryBuilder->select(array('menu', 'en_GB', 'bg_BG'))
            ->from(\Admin\Entity\Menu::class, 'menu')
            ->leftJoin(\Admin\Entity\MenuLng::class, 'en_GB', \Doctrine\ORM\Query\Expr\Join::WITH, "menu.id=en_GB.menuId AND en_GB.lng='en_GB'")
            ->leftJoin(\Admin\Entity\MenuLng::class, 'bg_BG', \Doctrine\ORM\Query\Expr\Join::WITH, "menu.id=bg_BG.menuId AND bg_BG.lng='bg_BG'");
//		$result = $queryBuilder->getQuery()->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);

        if (!empty($searchModel)) {
            $queryBuilder->where('1=1');
            foreach ($searchModel as $key => $item) {
                if (isset($filterData['filter'][$key]) && $filterData['filter'][$key] !== '') {
                    if ($item['comparison'] == 'eq') {
                        $queryBuilder->andWhere("{$item['field']} = :{$key}");
                        $queryBuilder->setParameter($key, $filterData['filter'][$key]);
                    } elseif ($item['comparison'] == 'ge') {
                        $queryBuilder->andWhere("{$item['field']} >= :{$key}");
                        $queryBuilder->setParameter($key, $filterData['filter'][$key]);
                    } elseif ($item['comparison'] == 'le') {
                        $queryBuilder->andWhere("{$item['field']} <= :{$key}");
                        $queryBuilder->setParameter($key, $filterData['filter'][$key]);
                    }
                }
            }
        }

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