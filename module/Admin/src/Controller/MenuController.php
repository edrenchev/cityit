<?php
namespace Admin\Controller;

use Admin\Paginator\Adapter;
use Libs\Admin\ListTable;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Paginator\Paginator;
use Zend\Session\Container;
use Zend\View\Model\ViewModel;
use Admin\Entity\Menu;
use Admin\Form\SearchForm;

class MenuController extends AbstractActionController {

    private $entityManager;
    private $siteConfig;

    public $model = [
        'name' => [
            'title' => 'Name',
            'type' => 'char'
        ],
        'isActive' => [
            'title' => 'Is Active',
            'type' => 'enum',
            'options' => [0 => 'No', 1 => 'Yes'],
        ],
        'createDate' => [
            'title' => 'Create Date',
            'type' => 'datetime',
        ],
        '*.menuTitle' => [
            'title' => 'Menu Title',
            'type' => 'char',
        ],
        '*.pageTitle' => [
            'title' => 'Page Title',
            'type' => 'char',
        ],
        '*.contentTitle' => [
            'title' => 'Content Title',
            'type' => 'char',
        ],
    ];

    public $search = [
        'name',
        'isActive',
        'createDateFrom' => [
            'title' => 'Create Date From',
            'type' => 'datetime',
            'comparison' => 'ge',
            'dbField' => 'create_date',
        ],
        'createDateTo' => [
            'title' => 'Create Date To',
            'type' => 'datetime',
            'comparison' => 'le',
            'dbField' => 'create_date',
        ],
        '*.menuTitle',
        '*.pageTitle',
        '*.contentTitle',
    ];

    public $list = [
        'name',
        'isActive',
        '*.menuTitle',
        '*.pageTitle',
        '*.contentTitle',
    ];

    public $searchModel = [
        'menu_id' => [
            'label' => 'Menu Id',
            'field' => 'menu.id',
            'comparison' => 'eq',
        ],
        'menu_name' => [
            'label' => 'Menu Name',
            'field' => 'menu.name',
            'comparison' => 'eq',
        ],
        'menu_is_active' => [
            'label' => 'Is Active',
            'field' => 'menu.isActive',
            'comparison' => 'eq',
            'options' => [0 => 'No', 1 => 'Yes'],
        ],
        'menu_create_date_from' => [
            'label' => 'Create From',
            'field' => 'menu.createFrom',
            'comparison' => 'ge',
        ],
        'menu_create_date_to' => [
            'label' => 'Create To',
            'field' => 'menu.createFrom',
            'comparison' => 'le',
        ],
    ];

    /**
     * Session container.
     * @var Container
     */
    private $sessionContainer;

    public function __construct($entityManager, $siteConfig, $sessionContainer) {
        $this->entityManager = $entityManager;
        $this->siteConfig = $siteConfig;
        $this->sessionContainer = $sessionContainer;
    }

    public function indexAction() {

        $searchForm = new SearchForm($this->model, $this->search, $this->siteConfig['languages']);

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

                    if (empty($order)) {
                        unset($filterData['orders'][$column]);
                    } else {
                        $filterData['orders'] = [];
                        $filterData['orders'][$column] = $order;
                    }

                    $this->sessionContainer->menuIndexFilterData = $filterData;

                } elseif (isset($action['addOrder'])) {
                    $orderData = $action['addOrder'];

                    $filterData = [];
                    if (isset($this->sessionContainer->menuIndexFilterData)) {
                        $filterData = $this->sessionContainer->menuIndexFilterData;
                    }

                    list($column, $order) = explode(' ', $orderData, 2);

                    if (empty($order)) {
                        $filterData['orders'][$column] = 'ASC';
                    } else {
                        $filterData['orders'][$column] = $order;
                    }

                    $this->sessionContainer->menuIndexFilterData = $filterData;
                }
            } else {

                $data = $this->params()->fromPost();

                $filterData = [];
                if (isset($this->sessionContainer->menuIndexFilterData)) {
                    $filterData = $this->sessionContainer->menuIndexFilterData;
                }

                if (isset($data['submit']) && $data['submit'] == 'Search') {
                    $filterData['filter'] = $data;
                } elseif (isset($data['clear']) && $data['clear'] == 'Clear') {
                    $filterData['filter'] = [];
                    $data = [];
                }
                $searchForm->setData($data);
                $this->sessionContainer->menuIndexFilterData = $filterData;

            }
        } else {
            if (!empty($this->sessionContainer->menuIndexFilterData['filter'])) {
                $searchForm->setData($this->sessionContainer->menuIndexFilterData['filter']);
            }
        }

        $filterData = [];
        if (isset($this->sessionContainer->menuIndexFilterData)) {
            $filterData = $this->sessionContainer->menuIndexFilterData;
        }

        echo '<pre>' . print_r($filterData, true) . '</pre>';

//        $result = $this->entityManager->getRepository(Menu::class)->getMenus($filterData, $searchModel);
//        $result = $result->getScalarResult();

        //TODO doctrine 2 partial!!!!!
        //TODO sled tova menu da go prevarna v asociativen masiv!!!!!


        $orders = [];
        if (isset($this->sessionContainer->menuIndexFilterData['orders'])) {
            $orders = $this->sessionContainer->menuIndexFilterData['orders'];
        }

        $repo = $this->entityManager->getRepository(Menu::class);
        $adapter = new Adapter($repo, $filterData, $this->model, $this->searchModel);
        $paginator = new Paginator($adapter);
        $page = $this->params()->fromQuery('page', 1);
        $paginator->setCurrentPageNumber($page)->setItemCountPerPage(2);

//        $listTable = new ListTable($thead, $orders, $result, 'home', '', '');
        $listTable = new ListTable($this->model, $this->list, $orders, $paginator, $this->siteConfig['languages'], 'home', '', '');

        return new ViewModel([
            'listTable' => $listTable,
            'searchForm' => $searchForm,
            'searchModel' => $this->searchModel,
            'searchList' => $this->search,
            'languages' => $this->siteConfig['languages'],
            'paginator' => $paginator,
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
