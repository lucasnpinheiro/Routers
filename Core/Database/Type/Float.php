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
class Float extends Integer {

    //put your code here
    public function toPHP() {
        $value = $this->getValue();
        if (trim($value) == '' or is_null($value)) {
            $value = null;
        }
        if (is_numeric($value)) {
            $value = floatval($value);
        } else {
            $value = null;
        }
        $this->setValue($value);
    }

    public function format($decimals = 0, $dec_point = '.', $thousands_sep = ',') {
        return number_format($this->getValue(), $decimals, $dec_point, $thousands_sep);
    }

}
