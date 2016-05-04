<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Core\Validation;

/**
 * Description of Validator
 *
 * @author lucas
 */
class Validator {

    public $chave;
    public $valor;
    public $exec = [];
    public $msg = [];

    public function __construct($chave, $valor) {
        $this->chave = $chave;
        $this->valor = $valor;
    }

    public function email() {
        $this->msg['email'] = 'Email informado não é valido.';
        $this->exec['email'] = (bool) filter_var($this->valor, FILTER_VALIDATE_EMAIL);
        ;
        return $this;
    }

    public function min($qtd = 0) {
        $this->msg['min'] = 'Valor é menor que o informado.';
        $this->exec['min'] = (bool) (strlen($this->valor) < $qtd ? false : true);
        return $this;
    }

    public function max($qtd = 0) {
        $this->msg['max'] = 'Valor é maior que o informado.';
        $this->exec['max'] = (bool) (strlen($this->valor) > $qtd ? false : true);
        return $this;
    }

    public function isEmpty() {
        $this->msg['isEmpty'] = 'Variavel informado está vazia.';
        $this->exec['isEmpty'] = !(bool) empty($this->valor);
        return $this;
    }

    public function isBlank() {
        $this->msg['isBlank'] = 'Variavel informado está vazia.';
        $this->exec['isBlank'] = (bool) strlen(trim($this->valor));
        return $this;
    }

    public function number() {
        $this->msg['number'] = 'não é numerico.';
        $this->exec['number'] = (bool) is_numeric($this->valor);
        return $this;
    }

    public function date() {
        $this->msg['date'] = 'Data invalida.';
        if (stripos($this->valor, '/')) {
            $this->valor = implode('-', array_reverse(explode('/', $this->valor)));
        }
        $date = date_create($this->valor);
        if ($date === FALSE) {
            $this->exec['date'] = false;
            return $this;
        }
        $ex = explode('-', $this->valor);
        $this->exec['date'] = (bool) checkdate($ex[1], $ex[2], $ex[0]);
        return $this;
    }

}
