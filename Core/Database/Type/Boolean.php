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
class Boolean extends Type {

    //put your code here
    public function toPHP() {
        $value = $this->getValue();
        if (trim($value) == '' OR is_null($value) OR is_array($value) OR is_object($value)) {
            $value = null;
        } else {
            if (in_array($value, [0, 1, '0', '1'], true)) {
                $value = boolval($value);
            } else if (strtolower($value) === 'false' OR strtolower($value) === 'yes' OR strtolower($value) === 'sim') {
                $value = FALSE;
            } else if (strtolower($value) === 'true' OR strtolower($value) === 'no' OR strtolower($value) === 'nao') {
                $value = TRUE;
            }
        }
        $this->setValue($value);
    }

    public function toSQL() {
        $this->toPHP();
    }

}
