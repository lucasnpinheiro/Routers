<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of RouterTest
 *
 * @author lucas
 */

namespace Test;

use PHPUnit_Framework_TestCase as PHPUnit;
use Core\Router\Router;

class RouterTest extends PHPUnit {

    //put your code here

    protected $router;

    public function setUp() {
        parent::setUp();
        $this->router = new Router('/Teste/Teste/aaa/bbb');
    }

    public function testRetornaUrlInformada() {
        $router = $this->router->descobre();
        $this->assertEquals('/Teste/Teste/aaa/bbb', $router, 'Foi retornado o esperado.');
    }
    
    public function testRetornaDiretorioDaUrlInformadaUmNivel() {
        $this->router = new Router('/Teste/Teste1/aaa/bbb');
        $router = $this->router->diretorios();
        $this->assertEquals('/Teste', $router, 'Foi retornado o esperado.');
    }
    
    public function testRetornaDiretorioDaUrlInformadaDoisNivel() {
        $router = $this->router->diretorios();
        $this->assertEquals('/Teste/Teste', $router, 'Foi retornado o esperado.');
    }

}
