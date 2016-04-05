<?php

namespace App\Model\Entity;

use Core\Database\Entities\Entity;

class ClientesEntity extends Entity {

    public function relacoes() {
        $this->belongsTo('Contatos', [
            'foreignKey' => 'cliente_id'
        ]);
    }

}
