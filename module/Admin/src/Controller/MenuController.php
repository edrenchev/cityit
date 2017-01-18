<?php
namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Admin\Entity\Menu;

class MenuController extends AbstractActionController {

	private $entityManager;

	public function __construct($entityManager) {
		$this->entityManager = $entityManager;
	}

	public function indexAction() {

//        $menu = $this->entityManager->getRepository(Menu::class)->findAll();

//        $stmt = $conn->prepare("SELECT * FROM menu AS m LEFT JOIN menu_lng as ml ON m.id=ml.menu_id");

		$queryBuilder = $this->entityManager->createQueryBuilder();
		$queryBuilder->select(array('t', 'tl'))
			->from(\Admin\Entity\Menu::class, 't')
			->leftJoin(\Admin\Entity\MenuLng::class, 'tl', \Doctrine\ORM\Query\Expr\Join::WITH, 't.id=tl.menuId');
//		$result = $queryBuilder->getQuery()->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
		$result = $queryBuilder->getQuery()->getScalarResult();
		$prepareResult = [];
		foreach ($result as $item) {
			$prepareResult[$item['t_id']]['id'] = $item['t_id'];
			$prepareResult[$item['t_id']]['name'] = $item['t_name'];
			$prepareResult[$item['t_id']]["lng_{$item['tl_lng']}"]['id'] = $item['tl_id'];
			$prepareResult[$item['t_id']]["lng_{$item['tl_lng']}"]['menu_title'] = $item['tl_menuTitle'];
			$prepareResult[$item['t_id']]["lng_{$item['tl_lng']}"]['page_title'] = $item['tl_pageTitle'];
			$prepareResult[$item['t_id']]["lng_{$item['tl_lng']}"]['content_title'] = $item['tl_contentTitle'];
		}
//            $result = $qb->select('*')
//            ->from('menu', 'm')
//            ->leftJoin('menu_lng', 'ml', 'm.id=ml.menu_id')
//            ->getQuery()
//            ->getResult();
		echo '<pre>' . print_r($prepareResult, true) . '</pre>';
		die();


		//TODO doctrine 2 partial!!!!!
		//TODO sled tova menu da go prevarna v asociativen masiv!!!!!


		return new ViewModel([
			'menu' => $menu
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
