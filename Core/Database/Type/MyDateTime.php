<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Core\Database\Type;

/**
 * Description of DateTime
 *
 * @author lucas
 */
class MyDateTime extends \DateTime {

    //put your code here
    private $_format = 'Y-m-d H:i:s';

    public function __toString() {
        return $this->format($this->_format);
    }

}
