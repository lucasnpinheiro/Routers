<?php

namespace App\Model\Table;

use Core\Database\Table;

class ClientesTable extends Table {

    protected $table = 'clientes';

    public function __construct() {
        parent::__construct();
    }

    public function validation(\Core\Validation\Validation $valitador) {
        $valitador->add('nome')->min(3)->max(50)->isEmpty();
        return $valitador;
    }

}
