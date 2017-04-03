<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Admin\Controller;

use Admin\Form\ImageForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

class ImageController extends AbstractActionController {

    public function indexAction() {

        $form = new ImageForm();

        if($this->getRequest()->isPost()) {
            $request = $this->getRequest();
            $data = $request->getFiles()->toArray();

            $form->setData($data);

            if($form->isValid()) {
                $data = $form->getData();
            }

            echo '<pre>'.print_r($data,true).'</pre>';
            die();
        }
        return new ViewModel();
    }
    public function addAction() {

        return new JsonModel([
            'v' => 'asd'
        ]);
    }
}
