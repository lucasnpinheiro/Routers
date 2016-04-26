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
class String extends Type {

    //put your code here
    public function toPHP() {
        $value = $this->getValue();
        if (trim($value) == '' OR is_null($value) OR is_array($value) OR is_object($value)) {
            $value = null;
        } else {
            $value = (string) $value;
        }
        $this->setValue($value);
    }

    public function toSQL() {
        $this->toPHP();
    }

    public function length() {
        return strlen($this->getValue());
    }

    public function upper() {
        return strtoupper($this->getValue());
    }

    public function lower() {
        return strtolower($this->getValue());
    }

    public function stripTags() {
        return strip_tags($this->getValue());
    }

    public function pad($pad_length, $pad_string = ' ', $pad_type = STR_PAD_RIGHT) {
        return str_pad($this->getValue(), $pad_length, $pad_string, $pad_type);
    }

}
