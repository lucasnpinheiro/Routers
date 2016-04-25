<?php

namespace App\Model\Table;

use Core\Database\Table;

class ContatosTable extends Table {

    protected $table = 'contatos';

    public function __construct() {
        parent::__construct();
    }

    public function beforeSave() {
        
    }

}
