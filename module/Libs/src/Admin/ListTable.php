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

    public function __construct($head, $data, $basicUrl, $currPage, $totalPage, $itemPerPage = 25) {

        $this->head = $head;
        $this->data = $data;
        $this->basicUrl = $basicUrl;
        $this->currPage = $currPage;
        $this->totalPage = $totalPage;
        $this->itemPerPage = $itemPerPage;
        $this->languages = [];

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

    public function prepareData($data) {
        $prepareData = [];
        foreach ($data as $item) {
            $prepareData[$item['t_id']]['id'] = $item['t_id'];
            $prepareData[$item['t_id']]['name'] = $item['t_name'];
            $prepareData[$item['t_id']]["lng_{$item['tl_lng']}.id"] = $item['tl_id'];
            $prepareData[$item['t_id']]["lng_{$item['tl_lng']}.menu_title"] = $item['tl_menuTitle'];
            $prepareData[$item['t_id']]["lng_{$item['tl_lng']}.page_title"] = $item['tl_pageTitle'];
            $prepareData[$item['t_id']]["lng_{$item['tl_lng']}.content_title"] = $item['tl_contentTitle'];
        }
        return $prepareData;
    }


    public function getListTable() {
        $keys = array_keys($this->head);
        $tHead = '';
        foreach ($keys as $key) {
            $tHead .= <<<EOD
<th>{$this->head[$key]}</th>
EOD;
        }
        $tHead = "<tr>{$tHead}</tr>";

        $tBody = '';
        $data = $this->prepareData($this->data);
        foreach ($data as $item) {
            $tBody .= '<tr>';
            foreach ($keys as $key) {
                $value = htmlspecialchars($item[$key]);
                $tBody .= <<<EOD
<td>{$value}</td>
EOD;
            }
            $tBody .= '</tr>';
        }

        return <<<EOD
<form action="" method="POST">
    <table>
        <thead>
            {$tHead}
       </thead>
       <tbody>
            {$tBody}
        </tbody>
    </table>
</form>
EOD;


    }

}