<?php
namespace Admin\Controller;

use Admin\Paginator\Adapter;
use Libs\Admin\ListTable;
use Libs\Admin\SessionHelper;
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
                    SessionHelper::setOrders($this->sessionContainer, static::class, $action['order']);
                } elseif (isset($action['addOrder'])) {
                    SessionHelper::addOrders($this->sessionContainer, static::class, $action['addOrder']);
                } elseif(isset($action['addItemsPerPage'])) {
                    SessionHelper::addItemsPerPage($this->sessionContainer, static::class, $action['addItemsPerPage']);
                }
            } else {
                $data = $this->params()->fromPost();

                if (isset($data['submit']) && $data['submit'] == 'Search') {
                    SessionHelper::addSearchData($this->sessionContainer, static::class, $data);
                } elseif (isset($data['clear']) && $data['clear'] == 'Clear') {
                    SessionHelper::clearSearchData($this->sessionContainer, static::class);
                    $data = [];
                }
                $searchForm->setData($data);
            }
        } else {
            $searchData = SessionHelper::getSearchData($this->sessionContainer, static::class);
            if (isset($searchData['filter'])) {
                $searchForm->setData($searchData['filter']);
            }
        }

        echo '<pre>' . print_r(SessionHelper::getSearchData($this->sessionContainer, static::class), true) . '</pre>';

        //TODO doctrine 2 partial!!!!!
        //TODO sled tova menu da go prevarna v asociativen masiv!!!!!

        $repo = $this->entityManager->getRepository(Menu::class);
        $adapter = new Adapter($repo, SessionHelper::getSearchData($this->sessionContainer, static::class), $this->siteConfig['languages'], $this->search);
        $paginator = new Paginator($adapter);
        $page = $this->params()->fromQuery('page', 1);
        $ipp = SessionHelper::getItemsPerPage($this->sessionContainer, static::class);
        $paginator->setCurrentPageNumber($page)->setItemCountPerPage($ipp);

//        $listTable = new ListTable($thead, $orders, $result, 'menu', '', '');
        $listTable = new ListTable($this->model, $this->list, SessionHelper::getOrdersData($this->sessionContainer, static::class), $paginator, $this->siteConfig['languages'], 'menu', '', '', 25, $this->url()->fromRoute('menu'));

        return new ViewModel([
            'listTable' => $listTable,
            'searchForm' => $searchForm,
            'searchModel' => $this->searchModel,
            'searchList' => $this->search,
            'languages' => $this->siteConfig['languages'],
            'paginator' => $paginator,
            'routeName' => 'menu',
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
