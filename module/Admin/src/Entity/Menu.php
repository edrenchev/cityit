<?php
/**
 * Created by PhpStorm.
 * User: ervin
 * Date: 12.01.2017
 * Time: 20:44
 */

namespace Admin\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="\Admin\Repository\MenuRepository")
 * @ORM\Table(name="menu")
 */
class Menu extends AdminEntity {

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="id")
     */
    protected $id;

    /**
     * @ORM\Column(name="pid")
     */
    protected $pid;

    /**
     * @ORM\Column(name="ord")
     */
    protected $ord;

    /**
     * @ORM\Column(name="tags")
     */
    protected $tags;

    /**
     * @ORM\Column(name="url")
     */
    protected $url;

    /**
     * @ORM\Column(name="name")
     */
    protected $name;

    /**
     * @ORM\Column(name="skin_id")
     */
    protected $skinId;

    /**
     * @ORM\Column(name="template_id")
     */
    protected $templateId;

    /**
     * @ORM\Column(name="params")
     */
    protected $params;

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
     * @ORM\Column(name="json_data")
     */
    protected $jsonData;

    /**
     * @ORM\OneToMany(targetEntity="\Admin\Entity\MenuLng", fetch="EAGER", mappedBy="mid")
     */
    private $translations;

    public function __construct() {
        $this->translations = new ArrayCollection();
    }

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
    public function getPid() {
        return $this->pid;
    }

    /**
     * @param mixed $pid
     */
    public function setPid($pid) {
        $this->pid = $pid;
    }

    /**
     * @return mixed
     */
    public function getOrd() {
        return $this->ord;
    }

    /**
     * @param mixed $ord
     */
    public function setOrd($ord) {
        $this->ord = $ord;
    }

    /**
     * @return mixed
     */
    public function getTags() {
        return $this->tags;
    }

    /**
     * @param mixed $tags
     */
    public function setTags($tags) {
        $this->tags = $tags;
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
    public function getName() {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name) {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getSkinId() {
        return $this->skinId;
    }

    /**
     * @param mixed $skinId
     */
    public function setSkinId($skinId) {
        $this->skinId = $skinId;
    }

    /**
     * @return mixed
     */
    public function getTemplateId() {
        return $this->templateId;
    }

    /**
     * @param mixed $templateId
     */
    public function setTemplateId($templateId) {
        $this->templateId = $templateId;
    }

    /**
     * @return mixed
     */
    public function getParams() {
        return $this->params;
    }

    /**
     * @param mixed $params
     */
    public function setParams($params) {
        $this->params = $params;
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

    /**
     * @return mixed
     */
    public function getJsonData() {
        return $this->jsonData;
    }

    /**
     * @param mixed $jsonData
     */
    public function setJsonData($jsonData) {
        $this->jsonData = $jsonData;
    }

    /**
     * @return mixed
     */
    public function getTranslations() {
        return $this->translations;
    }

    /**
     * @param mixed $translations
     */
    public function setTranslations($translations) {
        $this->translations = $translations;
    }


}