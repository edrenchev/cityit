<?php
/**
 * Created by PhpStorm.
 * User: ervin
 * Date: 12.01.2017
 * Time: 20:44
 */

namespace Admin\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="\Admin\Repository\MenuRepository")
 * @ORM\Table(name="menu")
 */
class Menu {

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="id")
     */
    protected $id;

    /**
     * @ORM\Column(name="name")
     */
    protected $name;

    /**
     * @ORM\Column(name="is_active")
     */
    protected $isActive;

    /**
     * @return mixed
     */

    /**
     * @ORM\OneToMany(targetEntity="\Admin\Entity\MenuLng", fetch="EAGER", mappedBy="menuId")
     */
    private $translations;

    public function __construct() {
        $this->translations = new ArrayCollection();
    }


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