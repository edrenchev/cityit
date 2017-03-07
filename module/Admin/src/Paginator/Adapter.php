<?php

namespace Admin\Paginator;

use Zend\Paginator\Adapter\AdapterInterface;

class Adapter implements AdapterInterface {
    protected $repository;
    protected $filterData;
    protected $searchModel;

    /**
     * Construct
     *
     * @param \Doctrine\ORM\EntityRepository $repository Repository class
     */
    public function __construct($repository, $filterData, $searchModel = []) {
        $this->repository = $repository;
        $this->filterData = $filterData;
        $this->searchModel = $searchModel;
    }

    /**
     * Returns an collection of items for a page.
     *
     * @param int $offset Page offset
     * @param int $itemCountPerPage Number of items per page
     *
     * @return array
     */
    public function getItems($offset, $itemCountPerPage) {
        return $this->repository->getItems($offset, $itemCountPerPage, $this->filterData, $this->searchModel);
    }

    /**
     * Count results
     *
     * @return int
     */
    public function count() {
        return $this->repository->count($this->filterData, $this->searchModel);
    }

}