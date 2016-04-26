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

    public function add($value = 0) {
        $this->setValue($this->getValue() + $value);
    }

    public function sub($value = 0) {
        $this->setValue($this->getValue() - $value);
    }

    public function div($value = 0) {
        $this->setValue($this->getValue() / $value);
    }

    public function mult($value = 0) {
        $this->setValue($this->getValue() * $value);
    }

    public function module($value = 0) {
        return $this->getValue() % $value;
    }

    public function pow($exp = 0) {
        return (pow($this->getValue(), $exp));
    }

    public function exp() {
        return (exp($this->getValue()));
    }

    public function log() {
        return (log($this->getValue()));
    }

    public function sqrt() {
        return (sqrt($this->getValue()));
    }

    public function ceil() {
        return (ceil($this->getValue()));
    }

    public function floor() {
        return (floor($this->getValue()));
    }

    public function round($precision = 0) {
        return round($this->getValue(), $precision);
    }

    public function pi() {
        return pi();
    }

}
