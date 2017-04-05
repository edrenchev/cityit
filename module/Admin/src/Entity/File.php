<?php
/**
 * Created by PhpStorm.
 * User: ervin
 * Date: 29.03.2017
 * Time: 21:23
 */

namespace Admin\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="files")
 */
class File {

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
     * @ORM\Column(name="type")
     */
    protected $type;

    /**
     * @ORM\Column(name="size")
     */
    protected $size;

    /**
     * @ORM\Column(name="thumb")
     */
    protected $thumb;

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
    public function getType() {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type) {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getSize() {
        return $this->size;
    }

    /**
     * @param mixed $size
     */
    public function setSize($size) {
        $this->size = $size;
    }

    /**
     * @return mixed
     */
    public function getThumb() {
        return $this->thumb;
    }

    /**
     * @param mixed $thumb
     */
    public function setThumb($thumb) {
        $this->thumb = $thumb;
    }

}