<?php

/**
 * @license MIT
 * @license http://opensource.org/licenses/MIT
 */

namespace Core\Database\Statement;

use Slim\PDO\Database;

/**
 * Class UpdateStatement.
 *
 * @author Fabian de Laender <fabian@faapz.nl>
 */
class SaveStatement {

    protected $_columns = [];
    protected $table = null;
    protected $primary_key = 'id';

    /**
     * Constructor.
     *
     * @param Database $dbh
     * @param array    $pairs
     */
    public function __construct(array $columns) {
        $this->_columns = $columns;
    }

    public function table($table) {
        $this->table = $table;
    }

    public function primaryKey($primary_key) {
        $this->primary_key = $primary_key;
    }

    public function execute(\Core\Database\Table $r, $retorno = true) {
        $id = 0;
        if (isset($this->_columns[$this->primary_key])) {
            $update = $r->update($this->_columns)->table($this->table)->where($this->primary_key, '=', $this->_columns[$this->primary_key]);
            $update->execute();
            $id = $this->_columns[$this->primary_key];
        } else {
            $insert = $r->insert(array_keys($this->_columns))->into($this->table)->values(array_values($this->_columns));
            $id = $insert->execute($retorno);
        }
        return $r->get($id);
    }

}
