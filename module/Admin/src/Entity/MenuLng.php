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
     * @ORM\JoinColumn(name="mid", referencedColumnName="id")
     */
    protected $mid;

    /**
     * @ORM\Column(name="lng")
     */
    protected $lng;

    /**
     * @ORM\Column(name="is_active")
     */
    protected $isActive;

    /**
     * @ORM\Column(name="url")
     */
    protected $url;

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
     * @ORM\Column(name="meta_description")
     */
    protected $metaDescription;

    /**
     * @ORM\Column(name="meta_keywords")
     */
    protected $metaKeywords;

    /**
     * @ORM\Column(name="head_html")
     */
    protected $headHtml;

    /**
     * @ORM\Column(name="pre_body_html")
     */
    protected $preBodyHtml;

    /**
     * @ORM\Column(name="pos_body_html")
     */
    protected $posBodyHtml;

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
    public function getMid() {
        return $this->mid;
    }

    /**
     * @param mixed $mid
     */
    public function setMid($mid) {
        $this->mid = $mid;
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
    public function getIsActive() {
        return $this->isActive;
    }

    /**
     * @param mixed $isActive
     */
    public function setIsActive($isActive) {
        $this->isActive = $isActive;
    }

    /**
     * @return mixed
     */
    public function getUrl() {
        return $this->url;
    }

    /**
     * @param mixed $url
     */
    public function setUrl($url) {
        $this->url = $url;
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

    /**
     * @return mixed
     */
    public function getMetaDescription() {
        return $this->metaDescription;
    }

    /**
     * @param mixed $metaDescription
     */
    public function setMetaDescription($metaDescription) {
        $this->metaDescription = $metaDescription;
    }

    /**
     * @return mixed
     */
    public function getMetaKeywords() {
        return $this->metaKeywords;
    }

    /**
     * @param mixed $metaKeywords
     */
    public function setMetaKeywords($metaKeywords) {
        $this->metaKeywords = $metaKeywords;
    }

    /**
     * @return mixed
     */
    public function getHeadHtml() {
        return $this->headHtml;
    }

    /**
     * @param mixed $headHtml
     */
    public function setHeadHtml($headHtml) {
        $this->headHtml = $headHtml;
    }

    /**
     * @return mixed
     */
    public function getPreBodyHtml() {
        return $this->preBodyHtml;
    }

    /**
     * @param mixed $preBodyHtml
     */
    public function setPreBodyHtml($preBodyHtml) {
        $this->preBodyHtml = $preBodyHtml;
    }

    /**
     * @return mixed
     */
    public function getPosBodyHtml() {
        return $this->posBodyHtml;
    }

    /**
     * @param mixed $posBodyHtml
     */
    public function setPosBodyHtml($posBodyHtml) {
        $this->posBodyHtml = $posBodyHtml;
    }

    public function setOptions(array $options) {
        $_classMethods = get_class_methods($this);
        foreach ($options as $key => $value) {
            $method = 'set' . ucfirst($key);
            if (in_array($method, $_classMethods)) {
                $this->$method($value);
            } else {
                throw new \Exception('Invalid method name: ' . $method);
            }
        }
        return $this;
    }


}