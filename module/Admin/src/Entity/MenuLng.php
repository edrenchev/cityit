<?php

namespace Admin\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="menu_lng")
 */
class MenuLng {

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="id")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="\Admin\Entity\Menu", inversedBy="translations", fetch="EAGER")
     * @ORM\JoinColumn(name="menu_id", referencedColumnName="id")
     */
    protected $menuId;

    /**
     * @ORM\Column(name="lng")
     */
    protected $lng;

    /**
     * @ORM\Column(name="menu_title")
     */
    protected $menuTitle;

    /**
     * @ORM\Column(name="page_title")
     */
    protected $pageTitle;

    /**
     * @ORM\Column(name="content_title")
     */
    protected $contentTitle;

    /**
     * @return mixed
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id) {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getMenuId() {
        return $this->menuId;
    }

    /**
     * @param mixed $menuId
     */
    public function setMenuId($menuId) {
        $this->menuId = $menuId;
    }

    /**
     * @return mixed
     */
    public function getLng() {
        return $this->lng;
    }

    /**
     * @param mixed $lng
     */
    public function setLng($lng) {
        $this->lng = $lng;
    }

    /**
     * @return mixed
     */
    public function getMenuTitle() {
        return $this->menuTitle;
    }

    /**
     * @param mixed $menuTitle
     */
    public function setMenuTitle($menuTitle) {
        $this->menuTitle = $menuTitle;
    }

    /**
     * @return mixed
     */
    public function getPageTitle() {
        return $this->pageTitle;
    }

    /**
     * @param mixed $pageTitle
     */
    public function setPageTitle($pageTitle) {
        $this->pageTitle = $pageTitle;
    }

    /**
     * @return mixed
     */
    public function getContentTitle() {
        return $this->contentTitle;
    }

    /**
     * @param mixed $contentTitle
     */
    public function setContentTitle($contentTitle) {
        $this->contentTitle = $contentTitle;
    }



}