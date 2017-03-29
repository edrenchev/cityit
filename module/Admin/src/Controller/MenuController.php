<?php
namespace Admin\Controller;

use Admin\Entity\MenuLng;
use Admin\Form\EditForm;
use Admin\Paginator\Adapter;
use Admin\Service\AdminService;
use Admin\Libs\Helper;
use Admin\Libs\ListTable;
use Admin\Libs\SessionHelper;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Paginator\Paginator;
use Zend\Session\Container;
use Zend\View\Model\ViewModel;
use Admin\Entity\Menu;
use Admin\Form\SearchForm;

class MenuController extends AbstractActionController {

    private $entityManager;
    private $siteConfig;

    /**
     * Post manager.
     * @var AdminService
     */
    private $adminService;


    public $model = [
        'pid' => [
            'title' => 'Pid',
            'type' => 'integer'
        ],
        'ord' => [
            'title' => 'Ord',
            'type' => 'integer'
        ],
        'tags' => [
            'title' => 'Tags',
            'type' => 'set'
        ],
        'url' => [
            'title' => 'Url',
            'type' => 'char'
        ],
        'name' => [
            'title' => 'Name',
            'type' => 'char'
        ],
        'pageModule' => [
            'title' => 'Page Module',
            'type' => 'enum'
        ],
        'skinId' => [
            'title' => 'Skin Id',
            'type' => 'integer'
        ],
        'templateId' => [
            'title' => 'Template Id',
            'type' => 'integer'
        ],
        'params' => [
            'title' => 'Params',
            'type' => 'char'
        ],
        'headHtml' => [
            'title' => 'Head HTML',
            'type' => 'text'
        ],
        'preBodyHtml' => [
            'title' => 'Pre Body HTML',
            'type' => 'text'
        ],
        'posBodyHtml' => [
            'title' => 'Pos Body HTML',
            'type' => 'text'
        ],
        'jsonData' => [
            'title' => 'JSON Data',
            'type' => 'text'
        ],
//        'isActive' => [
//            'title' => 'Is Active',
//            'type' => 'enum',
//            'options' => [0 => 'No', 1 => 'Yes'],
////        ],
//        'createDate' => [
//            'title' => 'Create Date',
//            'type' => 'datetime',
//        ],
        '*.isActive' => [
            'title' => 'Is Active',
            'type' => 'enum',
            'options' => [0 => 'No', 1 => 'Yes'],
        ],
        '*.url' => [
            'title' => 'Url',
            'type' => 'char'
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
        '*.metaDescription' => [
            'title' => 'Meta Descriptions',
            'type' => 'text',
        ],
        '*.metaKeywords' => [
            'title' => 'Meta Keywords',
            'type' => 'text',
        ],
        '*.headHtml' => [
            'title' => 'Head HTML',
            'type' => 'text',
        ],
        '*.preBodyHtml' => [
            'title' => 'Pre Body HTML',
            'type' => 'text'
        ],
        '*.posBodyHtml' => [
            'title' => 'Pos Body HTML',
            'type' => 'text'
        ],
    ];

    public $edit = [
        'pid',
        'ord',
        'tags',
        'url',
        'name',
        'pageModule',
        'skinId',
        'templateId',
        'params',
        'headHtml',
        'preBodyHtml',
        'posBodyHtml',
        'jsonData',
        '*.isActive',
        '*.url',
        '*.menuTitle',
        '*.pageTitle',
        '*.contentTitle',
        '*.metaDescription',
        '*.metaKeywords',
        '*.headHtml',
        '*.preBodyHtml',
        '*.posBodyHtml',
    ];

    public $search = [
        'name',
        'pageModule',
        '*.isActive',
//        'createDateFrom' => [
//            'title' => 'Create Date From',
//            'type' => 'datetime',
//            'comparison' => 'ge',
//            'dbField' => 'create_date',
//        ],
//        'createDateTo' => [
//            'title' => 'Create Date To',
//            'type' => 'datetime',
//            'comparison' => 'le',
//            'dbField' => 'create_date',
//        ],
        '*.menuTitle',
        '*.pageTitle',
        '*.contentTitle',
    ];

    public $list = [
        'name',
        'pageModule',
        '*.isActive',
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

    public function __construct($entityManager, $siteConfig, $sessionContainer, $adminService) {
        $this->entityManager = $entityManager;
        $this->siteConfig = $siteConfig;
        $this->sessionContainer = $sessionContainer;
        $this->adminService = $adminService;

        $this->model['pageModule']['options'] = $this->siteConfig['pageModules'];
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
                } elseif (isset($action['addItemsPerPage'])) {
                    SessionHelper::addItemsPerPage($this->sessionContainer, static::class, $action['addItemsPerPage']);
                } elseif (isset($action['delete']) && !empty($data['checked'])) {
                    $deleteIds = implode(',', $data['checked']);
                    return $this->redirect()->toRoute('admin/menu', ['action' => 'delete', 'id' => $deleteIds]);
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

//        echo '<pre>' . print_r(SessionHelper::getSearchData($this->sessionContainer, static::class), true) . '</pre>';

        //TODO doctrine 2 partial!!!!!
        //TODO sled tova menu da go prevarna v asociativen masiv!!!!!

        $repo = $this->entityManager->getRepository(Menu::class);
        $adapter = new Adapter($repo, SessionHelper::getSearchData($this->sessionContainer, static::class), $this->siteConfig['languages'], $this->search);
        $paginator = new Paginator($adapter);
        $page = $this->params()->fromQuery('page', 1);
        $ipp = SessionHelper::getItemsPerPage($this->sessionContainer, static::class);
        $paginator->setCurrentPageNumber($page)->setItemCountPerPage($ipp);

//        $listTable = new ListTable($thead, $orders, $result, 'menu', '', '');
        $listTable = new ListTable($this->model, $this->list, SessionHelper::getOrdersData($this->sessionContainer, static::class), $paginator, $this->siteConfig['languages'], 'admin/menu', '', '', 25, $this->url()->fromRoute('admin/menu'));

        $view = new ViewModel([
            'listTable' => $listTable,
            'searchForm' => $searchForm,
            'searchModel' => $this->searchModel,
            'searchList' => $this->search,
            'languages' => $this->siteConfig['languages'],
            'paginator' => $paginator,
            'routeName' => 'admin/menu',
        ]);
        return $view->setTemplate('admin/admin/index');
    }

    public function addAction() {
        $editForm = new EditForm($this->model, $this->edit, $this->siteConfig['languages']);

        if ($this->getRequest()->isPost()) {

            // Get POST data.
            $data = $this->params()->fromPost();

            // Fill form with data.
            $editForm->setData($data);
            if ($editForm->isValid()) {

                // Get validated form data.
                $data = $editForm->getData();
                unset($data['save']);

                $recordId = $this->adminService->insert($data, Menu::class);

                $q = $this->entityManager->getConnection()->prepare('SET @ord := 0;UPDATE menu SET ord = (@ord := @ord+1) ORDER BY ord;', $rsm);
                $q->execute();

                // Redirect the user to "admin" page.
                return $this->redirect()->toRoute('admin/menu', ['action' => 'edit', 'id' => $recordId]);
            }
        }


        $addAfter = $this->params()->fromQuery('addAfter', -1);
        $addChild = $this->params()->fromQuery('addChild', -1);
        if($addAfter > 0) {
            $menu = $this->entityManager->getRepository(Menu::class)->findOneBy(['id'=>$addAfter]);
            $editForm->setData([
                't_pid' => $menu->getPid(),
                't_ord' => $menu->getOrd() + 0.1,
            ]);
        } elseif($addChild > 0) {
            $editForm->setData([
                't_pid' => $addChild,
                't_ord' => 0.1,
            ]);
        }
//        echo '<pre>'.print_r($menu,true).'</pre>';
//        die();

        $view = new ViewModel([
            'editForm' => $editForm,
            'edit' => $this->edit,
            'languages' => $this->siteConfig['languages'],
        ]);
        return $view->setTemplate('admin/admin/add');
    }

    public function editAction() {
        $editForm = new EditForm($this->model, $this->edit, $this->siteConfig['languages']);

        $recordId = $this->params()->fromRoute('id', -1);

        $editData = $this->entityManager->getRepository(Menu::class)->getMenuById($recordId);

        if ($editData == null) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        if ($this->getRequest()->isPost()) {

            // Get POST data.
            $data = $this->params()->fromPost();

            // Fill form with data.
            $editForm->setData($data);
            if ($editForm->isValid()) {

                // Get validated form data.
                $data = $editForm->getData();
                unset($data['save']);

                $recordId = $this->adminService->update($recordId, $data, Menu::class);

                $q = $this->entityManager->getConnection()->prepare('SET @ord := 0;UPDATE menu SET ord = (@ord := @ord+1) ORDER BY ord;', $rsm);
                $q->execute();

                // Redirect the user to "admin" page.
//                return $this->redirect()->toRoute('menu', ['action' => 'edit', 'id' => $recordId]);
                return $this->redirect()->toRoute('admin/menu');
            }
        }

        $editForm->setData($editData[0]);

        $view = new ViewModel([
            'editForm' => $editForm,
            'edit' => $this->edit,
            'languages' => $this->siteConfig['languages'],
        ]);
        return $view->setTemplate('admin/admin/add');
    }

    public function deleteAction() {
        $ids = $postId = $this->params()->fromRoute('id', -1);
        if ($ids != -1) {
            $this->adminService->delete($ids, Menu::class);
        }
        return $this->redirect()->toRoute('admin/menu');
    }

    public function treeAction() {

        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();

            $source = $data['source'];
            $destination = !empty($data['destination']) ? $data['destination'] : 0;

            $item = $this->entityManager->getRepository(Menu::class)->findOneBy(['id' => $source]);

            $item->setPid($destination);
            $this->entityManager->persist($item);
            $this->entityManager->flush();

            $ordering = '';
            if (isset($data['order'])) {
                $ordering = json_decode($data['order'], true);
            } else {
                $ordering = json_decode($data['rootOrder'], true);
            }

            if (!empty($ordering)) {
                foreach ($ordering as $order => $itemId) {
                    if ($itemToOrder = $item = $this->entityManager->getRepository(Menu::class)->findOneBy(['id' => $itemId])) {
                        $itemToOrder->setOrd($order);
                        $this->entityManager->persist($itemToOrder);
                        $this->entityManager->flush();
                    }
                }
            }
//TODO da napravq da ne vrashta HTML view!!!
            return 'ok';
        }

        $menu = $this->entityManager->getRepository(Menu::class)->getMenuTree();
        $menuHtml = $this->buildMenu($menu);
        return new ViewModel([
            'menu' => $menuHtml,
        ]);
    }

    public function buildMenu($menu, $parentid = 0) {
        $result = null;
        foreach ($menu as $item)
            if ($item->getPid() == $parentid) {
                $result .= "<li class='dd-item nested-list-item' data-order='{$item->getOrd()}' data-id='{$item->getId()}'>
	      <div class='dd-handle nested-list-handle'>
	        <span class='glyphicon glyphicon-move'></span>
	      </div>
	      <div class='nested-list-content'>{$item->getName()}
	        <div class='pull-right'>
	          <a href='" . $this->url()->fromRoute('admin/menu', ['action'=>'edit', 'id'=>$item->getId()]) . "'>Edit</a> |
	          <a href='" . $this->url()->fromRoute('admin/menu', ['action'=>'delete', 'id'=>$item->getId()]) . "'>Delete</a> |
	          <a class='selected' data-id='{$item->getId()}' href='selected'>Seleted</a>
	        </div>
	      </div>" . $this->buildMenu($menu, $item->getId()) . "</li>";
            }
        return $result ? "\n<ol class=\"dd-list\">\n$result</ol>\n" : null;
    }

}
