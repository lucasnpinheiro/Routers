<?php

namespace App\Model\Table;

use Core\Database\Table;

class ClientesTable extends Table {

    protected $table = 'clientes';

    public function __construct() {
        parent::__construct();
    }

}
