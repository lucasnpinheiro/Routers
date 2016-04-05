<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Core\Database\Schemas;

use Core\Utilitys\Configure;

/**
 * Description of Dump
 *
 * @author lucas
 */
class Dump
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

    public function down()
    {
        $file = ROOT . 'src' . DS . 'dump' . DS . Configure::read('database.banco') . '_' . date('Y_m_d_H_i_s') . '.sql';
        $q = 'mysqldump -u ' . Configure::read('database.usuario') . ' ' . (Configure::read('database.senha') != '' ? '-p' . Configure::read('database.senha') : '') . ' --order-by-primary=TRUE --allow-keywords=TRUE --default-character-set=utf8 --insert-ignore=TRUE --hex-blob=TRUE --force=TRUE --complete-insert=TRUE --skip-triggers ' . Configure::read('database.banco') . ' > ' . $file;
        $r = exec($q);
        return (bool) (trim($r) == '' ? true : false);
    }

    public function up($file = '')
    {
        if (trim($file) == '') {
            $file = Configure::read('database.banco') . '_' . date('Y_m_d_H_i_s');
        }
        $file = ROOT . 'src' . DS . 'dump' . DS . trim($file, '.sql') . '.sql';
        if (file_exists($file)) {
            $q = 'mysqldump -u ' . Configure::read('database.usuario') . ' ' . (Configure::read('database.senha') != '' ? '-p' . Configure::read('database.senha') : '') . ' ' . Configure::read('database.banco') . ' < ' . $file;
            $r = exec($q);
            return (bool) (stripos($r, '-- Dump completed') !== false ? true : false);
        }

        return null;
    }
}
