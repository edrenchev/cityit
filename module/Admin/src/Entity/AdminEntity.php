<?php
/**
 * Created by PhpStorm.
 * User: ervin
 * Date: 22.03.2017
 * Time: 20:07
 */

namespace Admin\Entity;


class AdminEntity {
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