<?php
/**
 * Created by PhpStorm.
 * User: ervin
 * Date: 30.01.2017
 * Time: 21:31
 */

namespace Libs\Admin;


class ListTable {

    private $head;
    private $basicUrl;
    private $data;
    private $currPage;
    private $totalPage;
    private $itemPerPage;
    private $itemsPerPage;
    private $languages;
    private $orders;

    public function __construct($head, $orders, $data, $routeName, $currPage, $totalPage, $itemPerPage = 25) {

        $this->head = $head;
        $this->data = $data;
        $this->routeName = $routeName;
        $this->currPage = $currPage;
        $this->totalPage = $totalPage;
        $this->itemPerPage = $itemPerPage;
        $this->languages = [];
        $this->orders = $orders;

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

    public function getListTable($editLink) {
        $orderCnt = 0;
        $keys = array_keys($this->head);
        $tHead = '<th>Edit</th>';
        foreach ($keys as $key) {
            $order = '';
            $orderPosition = '';
            if(isset($this->orders[$key])) {
                $order = $this->orders[$key];
                $orderCnt += 1;
                $orderPosition = " data-order-position='{$orderCnt}'";
            }
            $tHead .= <<<EOD
<th data-order-column="{$key}" data-order="{$order}"{$orderPosition}>{$this->head[$key]}</th>
EOD;
        }
        $tHead = "<tr>{$tHead}</tr>";

        $tBody = '';
        foreach ($this->data as $item) {
            $tBody .= '<tr>';
            $tBody .= '<td><a href="' . $editLink . '/' . $item['menu_id'] . '">Edit</a></td>';
            foreach ($keys as $key) {
                $value = htmlspecialchars($item[$key]);
                $tBody .= <<<EOD
<td>{$value}</td>
EOD;
            }
            $tBody .= '</tr>';
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