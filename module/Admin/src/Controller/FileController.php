<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Admin\Controller;

use Admin\Entity\File;
use Admin\Form\ImageForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

class FileController extends AbstractActionController {

    private $entityManager;

    public function __construct($entityManager) {
        $this->entityManager = $entityManager;
    }

    public function indexAction() {

        $form = new ImageForm();

        if ($this->getRequest()->isPost()) {
            $request = $this->getRequest();
            $data = $request->getFiles()->toArray();

            $form->setData($data);

            if ($form->isValid()) {
                $data = $form->getData();
            }


            $prepareFiles = [];

            if (!empty($data['files'])) {
                foreach ($data['files'] as $file) {

                    $f = new File();
                    $f->setName($file['name']);
                    $f->setSize($file['size']);
//                    $f->setThumb('data:image/jpeg;base64,' . base64_encode(file_get_contents('data/files/' . $file['name'])));
                    $f->setType($file['type']);
                    $this->entityManager->persist($f);
                    $this->entityManager->flush();

                    $prepareFiles[] = [
                        'name' => $file['name'],
                        'size' => $file['size'],
                        'url' => '/images/' . $file['name'],
                        'thumbnailUrl' => '/images/thumb/' . $file['name'],
                        'deleteUrl' => '/images/delete/' . $file['name'],
                        'deleteType' => 'DELETE',
                    ];
                }
            }

            return new JsonModel([
                'files' => $prepareFiles
            ]);

//            echo '<pre>'.print_r($data,true).'</pre>';

        }

        return new ViewModel();
    }

    public function addAction() {

        return new JsonModel([
            'v' => 'asd'
        ]);
    }
}
