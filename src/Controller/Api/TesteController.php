<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Controller\Api;

use Core\Controller\Controller;

/**
 * Description of TesteController
 *
 * @author lucas
 */
class TesteController extends Controller {

    //put your code here
    public function index() {
        $this->loadModel('Clientes');
        $find = $this->Clientes->select()->where('nome', '=', 'aa')->contain(['Contatos'])->debug();
        debug($find);

        //$add = $this->Clientes->save(['id' => 11, 'nome' => '11 Teste Lucas']);
        //debug($add);

        echo 'Index';
    }

    public function add() {
        $this->loadModel('Clientes');
        $this->loadModel('Contatos');
        $find = $this->Clientes->where('id', 1)->contain('Contatos')->find();
        $find->Contatos[0]->valor = 1639191956;
        $this->Contatos->save($find->Contatos[0]);
        echo 'Add';
    }

}
