<?php

namespace App\Model\Entity;

use Core\Database\Entities\Entity;

class ContatosEntity extends Entity {

    protected function _getTipo() {
        $this->tipo_descricao = ($this->tipo === 1 ? 'Telefone' : 'Outros');
    }

}
