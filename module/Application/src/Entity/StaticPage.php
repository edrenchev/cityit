<?php

namespace Application\Entity;

/**
 * @ORM\Entity
 * @ORM\Table(name="static_pages")
 */
class StaticPage {

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="id")
     */
    protected $id;

    /**
     * @ORM\Column(name="title")
     */
    protected $title;
}