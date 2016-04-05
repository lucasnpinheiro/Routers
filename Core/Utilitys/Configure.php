<?php

namespace Core\Utilitys;

/**
 * Classe que gerencia arquivos de configurações
 *
 * @author Lucas Pinheiro
 */
use Core\Utilitys\Obj;

class Configure {

    /**
     *
     * Variavel que mantem os dados que foram carregados.
     * 
     * @var type 
     */
    public static $dados = [];

    /**
     * 
     * Os arquivos a ser carregado na memoria.
     * 
     * @param string $name
     */
    public static function load($name) {
        if (file_exists(ROOT . 'Config' . DS . $name . '.php')) {
            include ROOT . 'Config' . DS . $name . '.php';
            self::$dados[$name] = $config;
        }
    }

    /**
     * 
     * Consulta uma chave do arquivo.
     * 
     * @param string|array $key
     * @return array|string
     */
    public static function read($key = NULL, $default = null) {
        if (empty($key)) {
            return self::$dados;
        }
        $obj = new Obj(self::$dados);
        return $obj->get($key, $default);
    }

    /**
     * 
     * Adiciona um item na chave.
     * 
     * @param string|array $key
     * @param string|array $value
     * @return array|string
     */
    public static function write($key, $value) {
        $obj = new Obj(self::$dados);
        $obj->set($key, $value);
        self::$dados = $obj->get();
        return $obj->get($key);
    }

}
