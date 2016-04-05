<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Core\Database\Schemas;

use Core\Utilitys\Configure;

/**
 * Description of Schema
 *
 * @author lucas
 */
class Schema
{

    public $tabela = null;
    public $pdo = null;

    /**
     * Função de auto execução ao startar a classe.
     */
    public function __construct($tabela, $pdo)
    {
        $this->tabela = $tabela;
        $this->pdo = $pdo;
    }

    /**
     * 
     * função que limpa a tabela do banco de dados
     * 
     * @return boolean
     */
    public function truncate()
    {
        $this->_cache->deleteAll();
        return (bool) $this->pdo->query('TRUNCATE ' . $this->tabela)->execute();
    }

    /**
     * 
     * função que exclui a tabela do banco de dados
     * 
     * @return boolean
     */
    public function drop()
    {
        $this->_cache->deleteAll();
        return (bool) $this->pdo->query('DROP ' . $this->tabela)->execute();
    }

    /**
     * 
     * função que exclui a tabela do banco de dados
     * 
     * @return boolean
     */
    public function optimize()
    {
        $this->_cache->deleteAll();
        return (bool) $this->pdo->query('OPTIMIZE TABLE ' . $this->tabela)->execute();
    }

    /**
     * 
     * função que exclui a tabela do banco de dados
     * 
     * @return boolean
     */
    public function analyze()
    {
        $this->_cache->deleteAll();
        return (bool) $this->pdo->query('ANALYZE TABLE ' . $this->tabela)->execute();
    }

    /**
     * 
     * função que exclui a tabela do banco de dados
     * 
     * @return boolean
     */
    public function check($options = [])
    {
        $this->_cache->deleteAll();
        return (bool) $this->pdo->query('CHECK TABLE ' . $this->tabela . ' ' . implode(' ', $options))->execute();
    }

    /**
     * 
     * função que exclui a tabela do banco de dados
     * 
     * @return boolean
     */
    public function checksum($options = [])
    {
        $this->_cache->deleteAll();
        return (bool) $this->pdo->query('CHECKSUM TABLE ' . $this->tabela . ' ' . implode(' ', $options))->execute();
    }

    /**
     * 
     * função que exclui a tabela do banco de dados
     * 
     * @return boolean
     */
    public function tables()
    {
        $c = $this->pdo->query('SHOW TABLES FROM ' . Configure::read('database.banco'))->fetchAll(\PDO::FETCH_OBJ);
        if (count($c)) {
            $co = [];
            $chave = 'Tables_in_' . Configure::read('database.banco');
            foreach ($c as $key => $value) {
                $co[$value->{$chave}] = $value->{$chave};
            }
            return $co;
        }
        return null;
    }

    /**
     * 
     * função que exclui a tabela do banco de dados
     * 
     * @return boolean
     */
    public function columns()
    {
        $c = $this->pdo->query('SHOW FULL COLUMNS FROM ' . Configure::read('database.banco') . '.' . $this->tabela)->fetchAll(\PDO::FETCH_OBJ);
        if (count($c)) {
            $co = [];
            foreach ($c as $key => $value) {
                $co[$value->Field] = [];
                foreach ($value as $k => $v) {
                    if ($k != 'Field') {
                        $co[$value->Field][strtolower($k)] = $v;
                    }
                }
            }
            return $co;
        }
        return null;
    }

    /**
     * 
     * função que exclui a tabela do banco de dados
     * 
     * @return boolean
     */
    public function columnsTypes()
    {
        $c = $this->pdo->query('SHOW FULL COLUMNS FROM ' . Configure::read('database.banco') . '.' . $this->tabela)->fetchAll(\PDO::FETCH_OBJ);
        if (count($c)) {
            $co = [];
            foreach ($c as $key => $value) {
                $co[$value->Field] = $value->Type;
            }
            return $co;
        }
        return null;
    }

    /**
     * 
     * função que exclui a tabela do banco de dados
     * 
     * @return boolean
     */
    public function columnsName()
    {
        $c = $this->pdo->query('SHOW FULL COLUMNS FROM ' . Configure::read('database.banco') . '.' . $this->tabela)->fetchAll(\PDO::FETCH_OBJ);
        if (count($c)) {
            $co = [];
            foreach ($c as $key => $value) {
                $co[$value->Field] = $value->Field;
            }
            return $co;
        }
        return null;
    }
}
