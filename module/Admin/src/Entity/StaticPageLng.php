<?php
/**
 * Created by PhpStorm.
 * User: ervin
 * Date: 29.03.2017
 * Time: 21:40
 */

namespace Admin\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="static_pages_lng")
 */
class StaticPageLng extends AdminEntity {

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="id")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="\Admin\Entity\StaticPage", inversedBy="translations", fetch="EAGER")
     * @ORM\JoinColumn(name="mid", referencedColumnName="id")
     */
    protected $mid;

    /**
     * @ORM\Column(name="lng")
     */
    protected $lng;

    /**
     * @ORM\Column(name="body")
     */
    protected $body;

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
    public function getBody() {
        return $this->body;
    }

    /**
     * @param mixed $body
     */
    public function setBody($body) {
        $this->body = $body;
    }



}