<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Core\Validation;

/**
 * Description of Validation
 *
 * @author lucas
 */
class Validation {

    private $validator;
    private $error = [];
    private $valores = [];

    public function __construct($valores = []) {
        $this->valores = $valores;
    }

    public function add($chave, $valor = null) {
        if (is_null($valor)) {
            if (isset($this->valores[$chave])) {
                $valor = $this->valores[$chave];
                
            }
        }
        $v = $this->validator[$chave] = new Validator($chave, $valor);
        return $v;
    }

    public function run() {
        if (count($this->validator) > 0) {
            foreach ($this->validator as $key => $value) {
                foreach ($value->exec as $k => $v) {
                    if ($v === FALSE) {
                        $this->error[$value->chave][$k] = $value->msg[$k];
                    }
                }
            }
        }
        return !(bool) count($this->error);
    }

    public function error() {
        return $this->error;
    }

}
