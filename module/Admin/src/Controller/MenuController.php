<?php
namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Admin\Entity\Menu;

class MenuController extends AbstractActionController {

    private $entityManager;

    public function __construct($entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function indexAction() {

//        $menu = $this->entityManager->getRepository(Menu::class)->findAll();

//        $stmt = $conn->prepare("SELECT * FROM menu AS m LEFT JOIN menu_lng as ml ON m.id=ml.menu_id");
        $qb = $this->entityManager->createQueryBuilder();
        $result = $qb->select('*')->from('\Admin\Entity\Menu', 'm')->getArrayResult();
//            $result = $qb->select('*')
//            ->from('menu', 'm')
//            ->leftJoin('menu_lng', 'ml', 'm.id=ml.menu_id')
//            ->getQuery()
//            ->getResult();
        echo '<pre>'.print_r($result,true).'</pre>';
        die();


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
