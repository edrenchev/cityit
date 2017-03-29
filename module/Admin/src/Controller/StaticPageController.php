<?php
/**
 * Created by PhpStorm.
 * User: ervin
 * Date: 29.03.2017
 * Time: 21:22
 */

namespace Admin\Controller;


use Admin\Entity\Menu;
use Admin\Entity\StaticPage;
use Admin\Form\EditForm;
use Admin\Form\SearchForm;
use Admin\Libs\ListTable;
use Admin\Libs\SessionHelper;
use Admin\Paginator\Adapter;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Paginator\Paginator;
use Zend\View\Model\ViewModel;

class StaticPageController extends AbstractActionController {

    private $entityManager;
    private $siteConfig;

    /**
     * Post manager.
     * @var AdminService
     */
    private $adminService;

    /**
     * Session container.
     * @var Container
     */
    private $sessionContainer;

    public $model = [
        'cid' => [
            'title' => 'Cid',
            'type' => 'enum'
        ],
        'images' => [
            'title' => 'Images',
            'type' => 'text'
        ],
        'files' => [
            'title' => 'Files',
            'type' => 'text'
        ],
        '*.body' => [
            'title' => 'Body',
            'type' => 'text'
        ],
    ];

    public $edit = [
        'cid',
        'images',
        'files',
        '*.body',
    ];

    public $search = [
        'cid',
    ];

    public $list = [
        'cid',
    ];

    public function __construct($entityManager, $siteConfig, $sessionContainer, $adminService) {
        $this->entityManager = $entityManager;
        $this->siteConfig = $siteConfig;
        $this->sessionContainer = $sessionContainer;
        $this->adminService = $adminService;

//        $this->model['pageModule']['options'] = $this->siteConfig['pageModules'];

        $staticPages = $this->entityManager->getRepository(Menu::class)->findBy(['pageModule' => 'static'], ['ord' => 'ASC']);
        foreach ($staticPages as $staticPage) {
            $this->model['cid']['options'][$staticPage->getId()] = $staticPage->getName();
        }
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
                    return $this->redirect()->toRoute('admin/static', ['action' => 'delete', 'id' => $deleteIds]);
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

        $repo = $this->entityManager->getRepository(StaticPage::class);
        $adapter = new Adapter($repo, SessionHelper::getSearchData($this->sessionContainer, static::class), $this->siteConfig['languages'], $this->search);
        $paginator = new Paginator($adapter);
        $page = $this->params()->fromQuery('page', 1);
        $ipp = SessionHelper::getItemsPerPage($this->sessionContainer, static::class);
        $paginator->setCurrentPageNumber($page)->setItemCountPerPage($ipp);

        $listTable = new ListTable($this->model, $this->list, SessionHelper::getOrdersData($this->sessionContainer, static::class), $paginator, $this->siteConfig['languages'], 'admin/static', '', '', 25, $this->url()->fromRoute('admin/static'));

        $view = new ViewModel([
            'listTable' => $listTable,
            'searchForm' => $searchForm,
            'searchList' => $this->search,
            'languages' => $this->siteConfig['languages'],
            'paginator' => $paginator,
            'routeName' => 'admin/static',
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

                $recordId = $this->adminService->insert($data, StaticPage::class);

                // Redirect the user to "admin" page.
                return $this->redirect()->toRoute('admin/static', ['action' => 'edit', 'id' => $recordId]);
            }
        }

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

        $editData = $this->entityManager->getRepository(StaticPage::class)->getMenuById($recordId);

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

                $recordId = $this->adminService->update($recordId, $data, StaticPage::class);

                return $this->redirect()->toRoute('admin/static');
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
            $this->adminService->delete($ids, StaticPage::class);
        }
        return $this->redirect()->toRoute('admin/static');
    }
}