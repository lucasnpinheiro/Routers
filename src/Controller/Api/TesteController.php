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
        //$find = $this->Clientes->get(1);
        //$find->nome = 'Teste AAAA BBBB';
        $find = [];
        $find['id'] = '20';
        $find['nome'] = 'Novo teste de 20';
        debug($find = $this->Clientes->save($find));
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
