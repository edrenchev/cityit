<?php

namespace Admin\Repository;

use Doctrine\ORM\EntityRepository;
use Admin\Entity\Menu;

class MenuRepository extends EntityRepository {

    public function getMenus($filterData) {
        $entityManager = $this->getEntityManager();

        $queryBuilder = $entityManager->createQueryBuilder();

        $queryBuilder->select(array('menu', 'en_GB', 'bg_BG'))
            ->from(\Admin\Entity\Menu::class, 'menu')
            ->leftJoin(\Admin\Entity\MenuLng::class, 'en_GB', \Doctrine\ORM\Query\Expr\Join::WITH, "menu.id=en_GB.menuId AND en_GB.lng='en_GB'")
            ->leftJoin(\Admin\Entity\MenuLng::class, 'bg_BG', \Doctrine\ORM\Query\Expr\Join::WITH, "menu.id=bg_BG.menuId AND bg_BG.lng='bg_BG'");
//		$result = $queryBuilder->getQuery()->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);

        if(!empty($filterData['orders'])) {
            foreach($filterData['orders'] as $column=>$order) {
                if($order == '') continue;
                $tmpPos = strrpos($column, '_');
                $table = substr($column, 0, $tmpPos);
                $column = substr($column, $tmpPos + 1);
                $queryBuilder->addOrderBy("{$table}.{$column}", $order);
            }
        }


        $result = $queryBuilder->getQuery();
        var_dump($result->getSql());
        return $result;
    }

}