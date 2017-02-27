<?php
namespace Admin\Controller;

use Libs\Admin\ListTable;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Admin\Entity\Menu;
use Zend\Session\Container;

class MenuController extends AbstractActionController {

    private $entityManager;
    private $siteConfig;

    /**
     * Session container.
     * @var Zend\Session\Container
     */
    private $sessionContainer;

    public function __construct($entityManager, $siteConfig, $sessionContainer) {
        $this->entityManager = $entityManager;
        $this->siteConfig = $siteConfig;
        $this->sessionContainer = $sessionContainer;
    }

    public function indexAction() {

        if ($this->getRequest()->isPost()) {
            // Fill in the form with POST data
            $data = $this->params()->fromPost();
            if (isset($data['do'])) {
                $action = $data['do'];
                if (isset($action['order'])) {
                    $orderData = $action['order'];

                    $filterData = [];
                    if (isset($this->sessionContainer->menuIndexFilterData)) {
                        $filterData = $this->sessionContainer->menuIndexFilterData;
                    }

                    list($column, $order) = explode(' ', $orderData, 2);

                    if(empty($order)) {
                        unset($filterData['orders'][$column]);
                    } else {
                        $filterData['orders'] = [];
                        $filterData['orders'][$column] = $order;
                    }

                    $this->sessionContainer->menuIndexFilterData = $filterData;

//					unset($this->sessionContainer->menuIndexFilterData);

                } elseif(isset($action['addOrder'])) {
                    $orderData = $action['addOrder'];

                    $filterData = [];
                    if (isset($this->sessionContainer->menuIndexFilterData)) {
                        $filterData = $this->sessionContainer->menuIndexFilterData;
                    }

                    list($column, $order) = explode(' ', $orderData, 2);

                    if(empty($order)) {
                        $filterData['orders'][$column] = 'ASC';
                    } else {
                        $filterData['orders'][$column] = $order;
                    }

                    $this->sessionContainer->menuIndexFilterData = $filterData;
                }
            }
        }

        echo '<pre>'.print_r($this->sessionContainer->menuIndexFilterData,true).'</pre>';
//        die();
        $filterData = [];
        if (isset($this->sessionContainer->menuIndexFilterData)) {
            $filterData = $this->sessionContainer->menuIndexFilterData;
        }
        $result = $this->entityManager->getRepository(Menu::class)->getMenus($filterData);
        $result = $result->getScalarResult();

        //TODO doctrine 2 partial!!!!!
        //TODO sled tova menu da go prevarna v asociativen masiv!!!!!

        $thead = [
            'menu_id' => 'Id',
            'menu_name' => 'Name',
            'en_GB_menuTitle' => 'Menu Title (en)',
            'bg_BG_menuTitle' => 'Menu Title (bg)',
            'en_GB_pageTitle' => 'Page Title (en)',
            'bg_BG_pageTitle' => 'Page Title (bg)',
            'en_GB_contentTitle' => 'Content Title (en)',
            'bg_BG_contentTitle' => 'Content Title (bg)',
        ];

//        var_dump($this->sessionContainer->menuIndex = $filterData);

        $orders = '';
        $listTable = new ListTable($thead, $this->sessionContainer->menuIndexFilterData['orders'], $result, 'home', '', '');

        return new ViewModel([
            'listTable' => $listTable
        ]);
    }

    public function addAction() {
        return new ViewModel();
    }

    public function editAction() {
        return new ViewModel();
    }

    public function deleteAction() {
        return new ViewModel();
    }
}
