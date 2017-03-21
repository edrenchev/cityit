<?php

namespace Admin\Repository;

use Doctrine\ORM\EntityRepository;
use Admin\Entity\Menu;
use Libs\Admin\RepositoryHelper;

class MenuRepository extends EntityRepository {

    private $queryBuilderWithoutSelect;

    public function count($filterData, $languages, $searchModel) {
        $entityManager = $this->getEntityManager();
        $queryBuilder = $entityManager->createQueryBuilder();

        $queryBuilder->select('COUNT(t.id) as cnt')
            ->from(\Admin\Entity\Menu::class, 't')
            ->leftJoin(\Admin\Entity\MenuLng::class, 'en_GB', \Doctrine\ORM\Query\Expr\Join::WITH, "t.id=en_GB.mid AND en_GB.lng='en_GB'")
            ->leftJoin(\Admin\Entity\MenuLng::class, 'bg_BG', \Doctrine\ORM\Query\Expr\Join::WITH, "t.id=bg_BG.mid AND bg_BG.lng='bg_BG'");

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
            ->leftJoin(\Admin\Entity\MenuLng::class, 'en_GB', \Doctrine\ORM\Query\Expr\Join::WITH, "t.id=en_GB.mid AND en_GB.lng='en_GB'")
            ->leftJoin(\Admin\Entity\MenuLng::class, 'bg_BG', \Doctrine\ORM\Query\Expr\Join::WITH, "t.id=bg_BG.mid AND bg_BG.lng='bg_BG'");

        $queryBuilder = RepositoryHelper::addWhereClause($queryBuilder, $languages, $searchModel, $filterData);

        if (!empty($filterData['orders'])) {
            $queryBuilder = RepositoryHelper::addOrdersClause($queryBuilder, $filterData['orders']);
        }

        $result = $queryBuilder;

//        var_dump($result->getSql());
        return $result;
    }

    // Getter for the HTML menu builder
    public function getMenuTree() {
        $entityManager = $this->getEntityManager();
        $queryBuilder = $entityManager->createQueryBuilder();
        return $queryBuilder->select(array('t'))->from(\Admin\Entity\Menu::class, 't')->addOrderBy('t.ord', 'ASC')->getQuery()->getResult();
    }

    public function getMenuById($id) {
		$entityManager = $this->getEntityManager();

		$queryBuilder = $entityManager->createQueryBuilder();

		$queryBuilder->select(array('t', 'en_GB', 'bg_BG'))
			->from(\Admin\Entity\Menu::class, 't')
			->leftJoin(\Admin\Entity\MenuLng::class, 'en_GB', \Doctrine\ORM\Query\Expr\Join::WITH, "t.id=en_GB.mid AND en_GB.lng='en_GB'")
			->leftJoin(\Admin\Entity\MenuLng::class, 'bg_BG', \Doctrine\ORM\Query\Expr\Join::WITH, "t.id=bg_BG.mid AND bg_BG.lng='bg_BG'")
		 	->andWhere("t.id = :id")
            ->setParameter('id', $id);

		return $queryBuilder->getQuery()->getScalarResult();
	}

}