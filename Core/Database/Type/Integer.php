<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Core\Database\Type;

/**
 * Description of Integer
 *
 * @author lucas
 */
class Integer extends Type {

    //put your code here
    public function toPHP() {
        $value = $this->getValue();
        if (trim($value) == '' or is_null($value)) {
            $value = null;
        }
        if (is_numeric($value)) {
            $value = intval($value);
        } else {
            $value = null;
        }
        $this->setValue($value);
    }

    public function toSQL() {
        $this->toPHP();
    }

}
