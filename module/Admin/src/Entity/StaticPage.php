<?php
/**
 * Created by PhpStorm.
 * User: ervin
 * Date: 29.03.2017
 * Time: 21:23
 */

namespace Admin\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="\Admin\Repository\StaticPageRepository")
 * @ORM\Table(name="static_pages")
 */
class StaticPage extends AdminEntity {

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="id")
     */
    protected $id;

    /**
     * @ORM\Column(name="cid")
     */
    protected $cid;

    /**
     * @ORM\Column(name="images")
     */
    protected $images;

    /**
     * @ORM\Column(name="files")
     */
    protected $files;

    /**
     * @ORM\OneToMany(targetEntity="\Admin\Entity\StaticPageLng", fetch="EAGER", mappedBy="mid")
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
    public function getCid() {
        return $this->cid;
    }

    /**
     * @param mixed $cid
     */
    public function setCid($cid) {
        $this->cid = $cid;
    }

    /**
     * @return mixed
     */
    public function getImages() {
        return $this->images;
    }

    /**
     * @param mixed $images
     */
    public function setImages($images) {
        $this->images = $images;
    }

    /**
     * @return mixed
     */
    public function getFiles() {
        return $this->files;
    }

    /**
     * @param mixed $files
     */
    public function setFiles($files) {
        $this->files = $files;
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