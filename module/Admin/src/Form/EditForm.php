<?php

/**
 * Created by PhpStorm.
 * User: ervin
 * Date: 04.03.2017
 * Time: 9:54
 */

namespace Admin\Form;

use Admin\Libs\Helper;
use Zend\Form\Form;
use Zend\InputFilter\InputFilter;

class EditForm extends Form {

	private $model;
	private $edit;
	private $languages;
	private $inputFilter;
	private $formData;

	/**
	 * Constructor.
	 */
	public function __construct($model, $edit, array $languages, $data = []) {

		parent::__construct('search-form');

		$this->model = $model;
		$this->edit = $edit;
		$this->languages = $languages;
		$this->inputFilter = new InputFilter();
		$this->formData = $data;

		$this->setAttribute('method', 'post');

		$this->addElements();
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
		$lngStr = $lng != '0' ? " {$lng}" : '';
		$tmpArr = [];
		$tmpArr['name'] = $name;
		$tmpArr['attributes']['id'] = $name;
		$tmpArr['options']['label'] = $elementData['title'] . $lngStr;
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
		$tFields = Helper::transformFiled($field, $this->languages);
		foreach ($tFields as $lng => $tField) {
			$tmpArr[] = $this->createElement($tField, $elementData, $lng);
		}
		return $tmpArr;
	}

	protected function addElements() {
		$inputFilter = new InputFilter();
		$this->setInputFilter($inputFilter);
		if (!empty($this->edit)) {
			foreach ($this->edit as $key => $item) {
				if (is_array($item)) {
					$tmpArr = $this->createElementArray($key, $item);
				} else {
					$tmpArr = $this->createElementArray($item, $this->model[$item]);
				}

				foreach ($tmpArr as $v) {
					$this->add($v);
				}

			}
		}

		$this->add([
			'type' => 'submit',
			'name' => 'save',
			'attributes' => [
				'value' => 'Save',
				'id' => 'savebutton',
			],
		]);
	}
}