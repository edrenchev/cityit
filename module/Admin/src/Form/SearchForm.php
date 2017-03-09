<?php

/**
 * Created by PhpStorm.
 * User: ervin
 * Date: 04.03.2017
 * Time: 9:54
 */

namespace Admin\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;

class SearchForm extends Form {

    private $model;
    private $searchList;
    private $languages;
    private $inputFilter;

    /**
     * Constructor.
     */
    public function __construct($model, $searchList, array $languages) {

        parent::__construct('search-form');

        $this->model = $model;
        $this->searchList = $searchList;
        $this->languages = $languages;
        $this->inputFilter = new InputFilter();

        $this->setAttribute('method', 'post');

        $this->addElements();
    }

    protected function isLanguageField($field) {
        return strpos($field, '*.') === false ? false : true;
    }

    private function getHtmlElementType($type) {
        if ($type == 'char') {
            return 'text';
        } elseif ($type == 'enum') {
            return 'select';
        } elseif ($type == 'text') {
            return 'textarea';
        } elseif ($type == 'datetime') {
            return 'text';
        }
    }

    private function createElement($name, $elementData, $lng = '') {
        $tmpArr = [];
        $tmpArr['name'] = $name;
        $tmpArr['attributes']['id'] = $name;
        $tmpArr['options']['label'] = $elementData['title'] . ' ' . $lng;
        $tmpArr['type'] = $this->getHtmlElementType($elementData['type']);
        if ($tmpArr['type'] == 'select') {
            $tmpArr['options']['empty_option'] = '';
            $tmpArr['options']['value_options'] = $elementData['options'];
            $this->inputFilter->add([
                'name' => $name,
                'required' => false,
            ]);
        }

        return $tmpArr;
    }

    private function createElementArray($field, array $elementData) {
        $tmpArr = [];
        if ($this->isLanguageField($field)) {
            $tmpField = substr($field, 2);
            foreach (array_keys($this->languages) as $lng) {
                $tmpArr['lng'][$lng] = $this->createElement("{$lng}_{$tmpField}", $elementData, $lng);
            }
        } else {
            $tmpArr = $this->createElement("t_{$field}", $elementData);
        }
        return $tmpArr;
    }

    protected function addElements() {
        $inputFilter = new InputFilter();
        $this->setInputFilter($inputFilter);
        if (!empty($this->searchList)) {
            foreach ($this->searchList as $key => $item) {
                if (is_array($item)) {
                    $tmpArr = $this->createElementArray($key, $item);
                } else {
                    $tmpArr = $this->createElementArray($item, $this->model[$item]);
                }

                if (isset($tmpArr['lng'])) {
                    foreach ($tmpArr['lng'] as $v) {
                        $this->add($v);
                    }
                } else {
                    $this->add($tmpArr);
                }

            }
        }

        $this->add([
            'type' => 'submit',
            'name' => 'submit',
            'attributes' => [
                'value' => 'Search',
                'id' => 'submitbutton',
            ],
        ]);
        $this->add([
            'type' => 'submit',
            'name' => 'clear',
            'attributes' => [
                'value' => 'Clear',
                'id' => 'clearbutton',
            ],
        ]);
    }
}