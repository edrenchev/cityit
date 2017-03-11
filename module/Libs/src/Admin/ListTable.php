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
    private $tableData;
    private $tableHead;

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
        $this->tableData = [];
        $this->tableHead = [];

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

        $this->setTableHead($head);
        $this->setTableData($data);
    }

    public function getTableHead() {
        return $this->tableHead;
    }

    public function setTableHead($head) {
        $this->tableHead['_editId'] = 'Edit';
        foreach ($head as $k => $item) {
            $tFields = Helper::transformFiled($item, $this->languages);
            foreach ($tFields as $lng => $tField) {
                $lngStr = $lng != '0' ? " {$lng}" : '';
                $this->tableHead[$tField] = $this->model[$item]['title'] . $lngStr;
            }
        }
    }

    public function getTableData() {
        return $this->tableData;
    }

    public function setTableData($data) {
        foreach ($data as $k => $item) {
            $this->tableData[$k]['_editId'] = $item['t_id'];
            /*foreach ($this->head as $head) {
                $tFields = Helper::transformFiled($head, $this->languages);
                foreach ($tFields as $tField) {
                    $this->tableData[$k][$tField] = $item[$tField];
                }
            }*/
            foreach (array_keys($this->getTableHead()) as $key) {
                if ($key == '_editId') {
                    $this->tableData[$k][$key] = $item['t_id'];
                    continue;
                }
                $this->tableData[$k][$key] = $item[$key];
            }
        }
    }

    public function getTableHeadHtml() {
        $tableHeadHtml = '';
        foreach ($this->getTableHead() as $key => $item) {
            $order = '';
            $orderPosition = '';
            if (isset($this->orders[$key])) {
                $order = $this->orders[$key];
                $this->orderCnt += 1;
                $orderPosition = " data-order-position='{$this->orderCnt}'";
            }
            if($key == '_edit') {
                $tableHeadHtml .= <<<EOD
<th>{$item}</th>
EOD;
                continue;
            }
            $tableHeadHtml .= <<<EOD
<th data-order-column="{$key}" data-order="{$order}"{$orderPosition}>{$item}</th>
EOD;
        }

        return $tableHeadHtml;
    }

    public function getTableDataHtml() {
        $tableDataHtml = '';
        foreach($this->getTableData() as $item) {
            $tmp = '';
            foreach(array_keys($this->getTableHead()) as $key) {
                $tmp .= '<td>' . htmlspecialchars($item[$key]) . '</td>';
            }
            $tableDataHtml .= "<tr>{$tmp}</tr>";
        }
        return $tableDataHtml;
    }

    public function getTableHtml() {
        return '<table>'.$this->getTableHeadHtml() . $this->getTableDataHtml().'</table>';
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