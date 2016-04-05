<?php

namespace Core\Database\Conections;

use Core\Utilitys\Configure;

/**
 * Classe de gera a conexão com o banco de dados informado.
 *
 * @author Lucas Pinheiro
 */
class Conection {

    /**
     *
     * Variavel static para manter a conexão aberta com o banco de dados.
     * 
     * @var object 
     */
    public static $instance;

    /**
     * Função de auto execução ao startar a classe.
     */
    public function __construct() {
        Configure::load('database');
    }

    /**
     * 
     * Função de faz a conexão com o banco de dados.
     * 
     * @return object
     */
    public static function db() {
        if (!isset(self::$instance)) {
            try {
                self::$instance = new \PDO(Configure::read('database.drive') . ':host=' . Configure::read('database.host') . ';dbname=' . Configure::read('database.banco'), Configure::read('database.usuario'), Configure::read('database.senha'), [\PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8", \PDO::ATTR_PERSISTENT => true]);
                self::$instance->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            } catch (\PDOException $exception) {
                $ex = new \Core\MyException();
                $ex->show_exception($exception);
            } catch (\Exception $exception) {
                $ex = new \Core\MyException();
                $ex->show_exception($exception);
            }
        }
        return self::$instance;
    }

}
