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

    private $searchModel;

    /**
     * Constructor.
     */
    public function __construct($searchModel) {

        parent::__construct('search-form');

        $this->searchModel = $searchModel;

        $this->setAttribute('method', 'post');

        $this->addElements();
    }

    protected function addElements() {
        $inputFilter = new InputFilter();
        $this->setInputFilter($inputFilter);
        if (!empty($this->searchModel)) {
            foreach ($this->searchModel as $key => $item) {
                $tmpArr = [];

                if (empty($item['options'])) {
                    $tmpArr['type'] = 'text';
                } else {
                    $tmpArr['type'] = 'select';
                    $tmpArr['options']['empty_option'] = '';
                    $tmpArr['options']['value_options'] = $item['options'];
                    $inputFilter->add([
                        'name' => $key,
                        'required' => false,
                    ]);
                }
                $tmpArr['name'] = $key;
                $tmpArr['attributes']['id'] = $key;
                $tmpArr['options']['label'] = $item['label'];

                $this->add($tmpArr);
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