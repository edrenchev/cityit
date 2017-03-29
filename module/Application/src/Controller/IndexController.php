<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController {

    public function indexAction() {
        return new ViewModel();
    }

    public function staticAction() {
        // Get path to view template from route params
        $pageTemplate = $this->params()->fromRoute('page', null);
        echo '<pre>'.print_r($pageTemplate,true).'</pre>';
        die();
        if ($pageTemplate == null) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        // Render the page
        $viewModel = new ViewModel([
            'page' => $pageTemplate
        ]);
        $viewModel->setTemplate($pageTemplate);
        return $viewModel;
    }
}
