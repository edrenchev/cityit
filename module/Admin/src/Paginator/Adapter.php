<?php

namespace Admin\Paginator;

use Zend\Paginator\Adapter\AdapterInterface;

class Adapter implements AdapterInterface {
    protected $repository;
    protected $filterData;
    protected $model;
    protected $search;

    /**
     * Construct
     *
     * @param \Doctrine\ORM\EntityRepository $repository Repository class
     */
    public function __construct($repository, $filterData, $model, $searchModel = []) {
        $this->repository = $repository;
        $this->filterData = $filterData;
        $this->model = $model;
        $this->search = $searchModel;
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
        return $this->repository->getItems($offset, $itemCountPerPage, $this->filterData, $this->model, $this->search);
    }

    /**
     * Count results
     *
     * @return int
     */
    public function count() {
        return $this->repository->count($this->filterData, $this->model, $this->searchModel);
    }

}