<?php

namespace Core\Database;

use Core\Database\Database;

//use Core\Request;

/**
 * Classe que realiza o ponte da classe databese para uma classe pré para tratamentos de algumas informações genericas no banco de dados.
 *
 * @author Lucas Pinheiro
 */
class Table extends Database {

    public $filterArgs = [];

    /**
     * Função de auto execução ao startar a classe.
     */
    public function __construct() {
        parent::__construct();
    }

    /*public function search() {
        if (!empty($this->filterArgs)) {
            $r = new Request();
            foreach ($r->query as $key => $value) {
                if (isset($this->filterArgs[$key]) and ! empty($this->filterArgs[$key])) {
                    switch ($this->filterArgs[$key]) {
                        case 'like':
                            $this->where($key, $value, 'like');
                            break;
                        case 'date':
                            $this->where('DATE(' . $key . ')', $value, '=');
                            break;

                        default:
                            $this->where($key, $value, '=');
                            break;
                    }
                }
            }
        }
    }*/

}
