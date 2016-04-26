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
class DateTime extends Type {

    public function toPHP() {
        $value = $this->getValue();

        if ($value instanceof MyDateTime) {
            $this->setValue($value);
        } else {

            if (trim($value) === '' OR is_null($value)) {
                $value = null;
                $this->setValue($value);
            } else {
                if (stripos($value, '/') !== FALSE) {
                    $value = explode(' ', $value);
                    $value[0] = implode('-', array_reverse(explode('/', $value[0])));
                    $value = trim(implode(' ', $value));
                }
                $this->setValue(new MyDateTime($value));
            }
        }
    }

    public function toSQL() {
        $this->toPHP();
    }

    public function format($format) {
        $value = $this->getValue();
        if ($value instanceof MyDateTime) {
            return $value->format($format);
        } else {
            $value = new MyDateTime($value);
            return $value->format($format);
        }
    }

    public function __toString() {
        $value = $this->getValue();
        if (is_null($value)) {
            return parent::__toString();
        } else {
            return $value->__toString();
        }
    }

}
