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
class DateTime extends \DateTime {

    //put your code here
    private $_format = 'Y-m-d H:i:s';

    public function __toString() {
        return $this->format($this->_format);
    }

    public function toPHP($dateTime = null) {
        if (is_null($dateTime)) {
            return NULL;
        }

        if ($dateTime === '0000-00-00 00:00:00') {
            return NULL;
        }

        if ($dateTime === '0000-00-00') {
            return NULL;
        }

        if ($dateTime === '00:00:00') {
            return NULL;
        }
        return new DateTime($dateTime);
    }

    public function toSQL($dateTime = null) {
        if (is_null($dateTime)) {
            return NULL;
        }

        if ($dateTime === '0000-00-00 00:00:00') {
            return NULL;
        }

        if ($dateTime === '0000-00-00') {
            return NULL;
        }

        if ($dateTime === '00:00:00') {
            return NULL;
        }
        if (stripos($dateTime, '/') !== FALSE) {
            $explode = explode(' ', $dateTime);
            $explode[0] = explode('/', $dateTime[0]);
            $explode[0] = implode('-', array_reverse($explode[0]));
            $dateTime = implode(' ', $explode);
        }
        return new DateTime($dateTime);
    }

}
