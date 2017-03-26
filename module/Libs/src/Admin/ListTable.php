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
    private $baseUrl;
    private $mapData = [];

    public function __construct($model, $head, $orders = [], $data, $languages, $routeName, $currPage, $totalPage, $itemPerPage = 25, $baseUrl) {

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
        $this->basicUrl = $baseUrl;

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
        $this->tableHead['_selectId'] = [
            'lng' => '',
            'value' => <<<EOD
<input type="checkbox" name="allChecked" onclick="$('input[type=checkbox][name^=\'checked\']').prop('checked', this.checked).trigger('change')"/>
EOD
        ];
        $this->tableHead['_editId'] = [
            'lng'=>'',
            'value'=> 'Edit',
        ];
        foreach ($head as $k => $item) {
            $tFields = Helper::transformFiled($item, $this->languages);
            foreach ($tFields as $lng => $tField) {
                if ($this->model[$item]['type'] == 'enum') {
                    $this->mapData[$tField] = $this->model[$item]['options'];
                }
                $lngStr = $lng != '0' ? "{$lng}" : '';
                $this->tableHead[$tField] = [
                    'lng' => $lngStr,
                    'value' => $this->model[$item]['title'],
                ];
            }
        }
    }

    public function getTableData() {
        return $this->tableData;
    }

    public function setTableData($data) {
        foreach ($data as $k => $item) {
            $this->tableData[$k]['_selectId'] = $item['t_id'];
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
                } elseif ($key == '_selectId') {
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
            if ($key == '_selectId' || $key == '_editId') {
                $tableHeadHtml .= <<<EOD
<th><span>{$item['value']}</span></th>
EOD;
                continue;
            }
            $thClass = '';
            if(!empty($item['lng'])) {
                $thClass = " class='lng {$item['lng']}'";
                $item['value'] = "{$item['value']} <span class='value-lng'>({$item['lng']})</span>";
            }
            $tableHeadHtml .= <<<EOD
<th data-order-column="{$key}" data-order="{$order}"{$orderPosition}{$thClass}><span>{$item['value']}</span></th>
EOD;
        }

        return $tableHeadHtml;
    }

    public function getTableDataHtml() {
        $tableDataHtml = '';
        $trCnt = 1;
        foreach ($this->getTableData() as $item) {
            $tmp = '';

            foreach ($this->getTableHead() as $key=>$tmpItem) {
                if ($key == '_editId') {
                    $value = "<a href='{$this->basicUrl}/edit/{$item[$key]}' class='edit-link'>Edit</a>";
                } elseif ($key == '_selectId') {
                    $value = "<input type='checkbox' name='checked[{$item[$key]}]' value='{$item[$key]}' />";
                } elseif (isset($this->mapData[$key])) {
                    $value = htmlspecialchars($this->mapData[$key][$item[$key]]);
                } else {
                    $value = htmlspecialchars($item[$key]);
                }
                $tdClass = '';
                if(!empty($tmpItem['lng'])) {
                    $tdClass = " class='{$tmpItem['lng']}'";
                }
                $tmp .= "<td{$tdClass}>{$value}</td>";
            }

            $trClass = 'odd';
            if ($trCnt % 2 == 0) {
                $trClass = 'even';
            }
            $trCnt += 1;

            $tableDataHtml .= "<tr class='{$trClass}'>{$tmp}</tr>";
        }
        return $tableDataHtml;
    }

    public function getTableHtml() {
        $tHead = $this->getTableHeadHtml();
        $tBody =  $this->getTableDataHtml();
        return <<<EOD

<table class="list-data"  cellpadding="0" cellspacing="0">
    <thead>{$tHead}</thead>
    <tbody>{$tBody}</tbody>
</table>
EOD;
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