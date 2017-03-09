<?php
/**
 * Created by PhpStorm.
 * User: ervin
 * Date: 30.01.2017
 * Time: 21:31
 */

namespace Libs\Admin;


class ListTable {

    private $model;
    private $head;
    private $basicUrl;
    private $data;
    private $currPage;
    private $totalPage;
    private $itemPerPage;
    private $itemsPerPage;
    private $languages;
    private $orders;
    private $orderCnt;

    public function __construct($model, $head, $orders = [], $data, $languages, $routeName, $currPage, $totalPage, $itemPerPage = 25) {

        $this->model = $model;
        $this->head = $head;
        $this->data = $data;
        $this->routeName = $routeName;
        $this->currPage = $currPage;
        $this->totalPage = $totalPage;
        $this->itemPerPage = $itemPerPage;
        $this->setLanguages($languages);
        $this->orders = $orders;
        $this->orderCnt = 0;

        $this->itemsPerPage = [
            5 => 5,
            10 => 10,
            25 => 25,
            50 => 50,
            100 => 100,
            200 => 200,
            500 => 500,
            1000 => 1000,
        ];

    }

    /**
     * @return array
     */
    public function getLanguages() {
        return $this->languages;
    }

    /**
     * @param array $languages
     */
    public function setLanguages(array $languages) {
        $this->languages = $languages;
    }

    private function renderTh($key, $lng = '') {
        if ($lng == '') {
            $formatKey = "t_{$key}";
        } else {
            $formatKey = "{$lng}_{$key}";
            $key = "*.{$key}";
        }
        $order = '';
        $orderPosition = '';
        if (isset($this->orders[$formatKey])) {
            $order = $this->orders[$formatKey];
            $this->orderCnt += 1;
            $orderPosition = " data-order-position='{$this->orderCnt}'";
        }
        return <<<EOD
<th data-order-column="{$formatKey}" data-order="{$order}"{$orderPosition}>{$this->model[$key]['title']} {$lng}</th>
EOD;
    }

    private function renderTd($item, $key, $lng = '') {
        if ($lng == '') {
            $key = "t_{$key}";
        } else {
            $key = "{$lng}_{$key}";
        }
        $value = htmlspecialchars($item[$key]);
        return "<td>{$value}</td>";
    }

    public function getListTable($editLink) {
        $keys = $this->head;
        $tHead = '<th>Edit</th>';
        foreach ($keys as $key) {
            if (strpos($key, '*.') !== false) {
                $tmpKey = substr($key, 2);
                foreach (array_keys($this->languages) as $lng) {
                    $tHead .= $this->renderTh($tmpKey, $lng);
                }
            } else {
                $tHead .= $this->renderTh($key);
            }
        }
        $tHead = "<tr>{$tHead}</tr>";

        $tBody = '';
        if (!empty($this->data)) {
            foreach ($this->data as $item) {
                $tBody .= '<tr>';
                $tBody .= '<td><a href="' . $editLink . '/' . $item['t_id'] . '">Edit</a></td>';
                foreach ($keys as $key) {
                    if (strpos($key, '*.') !== false) {
                        $tmpKey = substr($key, 2);
                        foreach (array_keys($this->languages) as $lng) {
                            $tBody .= $this->renderTd($item, $tmpKey, $lng);
                        }
                    } else {
                        $tBody .= $this->renderTd($item, $key);
                    }
                }
                $tBody .= '</tr>';
            }
        }

        return <<<EOD
    <table class="AdminListTable">
        <thead>
            {$tHead}
       </thead>
       <tbody>
            {$tBody}
        </tbody>
    </table>
EOD;


    }

}