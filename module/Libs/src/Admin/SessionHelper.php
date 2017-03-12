<?php
/**
 * Created by PhpStorm.
 * User: ervin
 * Date: 11.03.2017
 * Time: 12:34
 */

namespace Libs\Admin;

use Zend\Session\Container;

class SessionHelper {

    public static function setOrders(Container $sessionContainer, $sessionKey, $orderData) {
        $filterData = [];
        if ($sessionContainer->offsetExists($sessionKey) === true) {
            $filterData = $sessionContainer->offsetGet($sessionKey);
        }

        list($column, $order) = explode(' ', $orderData, 2);

        if (empty($order)) {
            unset($filterData['orders'][$column]);
        } else {
            $filterData['orders'] = [];
            $filterData['orders'][$column] = $order;
        }

        $sessionContainer->offsetSet($sessionKey, $filterData);
    }

    public static function addOrders(Container $sessionContainer, $sessionKey, $orderData) {
        $filterData = [];
        if ($sessionContainer->offsetExists($sessionKey) === true) {
            $filterData = $sessionContainer->offsetGet($sessionKey);
        }

        list($column, $order) = explode(' ', $orderData, 2);

        if (empty($order)) {
            $filterData['orders'][$column] = 'ASC';
        } else {
            $filterData['orders'][$column] = $order;
        }

        $sessionContainer->offsetSet($sessionKey, $filterData);
    }

    public static function addSearchData(Container $sessionContainer, $sessionKey, $searchData) {
        $filterData = [];
        if ($sessionContainer->offsetExists($sessionKey) === true) {
            $filterData = $sessionContainer->offsetGet($sessionKey);
        }
        $filterData['filter'] = $searchData;

        $sessionContainer->offsetSet($sessionKey, $filterData);
    }

    public static function clearSearchData(Container $sessionContainer, $sessionKey) {
        if ($sessionContainer->offsetExists($sessionKey) === true) {
            $sessionContainer->offsetSet($sessionKey, []);
        }
    }

    public static function getSearchData(Container $sessionContainer, $sessionKey) {
        if ($sessionContainer->offsetExists($sessionKey) === true) {
            return $sessionContainer->offsetGet($sessionKey);
        }
        return [];
    }

    public static function getFilterData(Container $sessionContainer, $sessionKey) {
        if ($sessionContainer->offsetExists($sessionKey) === true) {
            $data = $sessionContainer->offsetGet($sessionKey);
            return isset($data['filter']) ? $data['filter'] : [];
        }
        return [];
    }

    public static function getOrdersData(Container $sessionContainer, $sessionKey) {
        if ($sessionContainer->offsetExists($sessionKey) === true) {
            $data = $sessionContainer->offsetGet($sessionKey);
            return isset($data['orders']) ? $data['orders'] : [];
        }
        return [];
    }

    public static function addItemsPerPage(Container $sessionContainer, $sessionKey, $ipp) {
        $filterData = [];
        if ($sessionContainer->offsetExists($sessionKey) === true) {
            $filterData = $sessionContainer->offsetGet($sessionKey);
        }
        $filterData['ipp'] = $ipp;
        $sessionContainer->offsetSet($sessionKey, $filterData);
    }

    public static function getItemsPerPage(Container $sessionContainer, $sessionKey) {
        if ($sessionContainer->offsetExists($sessionKey) === true) {
            $data = $sessionContainer->offsetGet($sessionKey);
            return isset($data['ipp']) ? $data['ipp'] : 1;
        }
        return 1;
    }

}