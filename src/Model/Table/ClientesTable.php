<?php

namespace App\Model\Table;

use Core\Database\Table;

class ClientesTable extends Table {

    public $tabela = 'clientes';

    public function __construct() {
        parent::__construct();
    }

    public function beforeSave() {
        
    }

}
