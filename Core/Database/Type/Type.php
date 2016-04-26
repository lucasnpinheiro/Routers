<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Core\Database\Type;

/**
 * Description of Type
 *
 * @author lucas
 */
class Type {

    //put your code here

    private $value = null;

    public function getValue() {
        return $this->value;
    }

    public function setValue($value) {
        $this->value = $value;
    }

    public function __construct($value) {
        $this->setValue($value);
        $this->toPHP();
    }

    public function toSQL() {
        
    }

    public function toPHP() {
        
    }

    public function format() {
        return $this->getValue();
    }

    public function __toString() {
        if (is_null($this->getValue())) {
            return '';
        }
        return $this->getValue();
    }

    public function levenshtein($str) {
        return levenshtein($this->getValue(), $str);
    }

    public function metaphone() {
        return metaphone($this->getValue());
    }

    public function soundex() {
        return soundex($this->getValue());
    }

    public function soundexCompare($str) {
        return soundex($this->getValue()) == soundex($str);
    }

    public function similarText($str) {
        $percent = 0;
        similar_text($this->getValue(), $str, $percent);
        return $percent;
    }

}
