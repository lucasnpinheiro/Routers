<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Core\Database\Types;

/**
 * Description of DateTime
 *
 * @author lucas
 */
class Time extends DateTime {

    //put your code here
    private $_format = 'H:i:s';

    public function __toString() {
        return $this->format($this->_format);
    }

    public function toPHP($date = null) {
        $date = parent::toPHP($date);
        return new Date($date);
    }

    public function toSQL($date = null) {
        $date = parent::toSQL($date);
        return new Date($date);
    }

}
