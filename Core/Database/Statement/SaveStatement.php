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
    protected $dbh = null;

    /**
     * Constructor.
     *
     * @param Database $dbh
     * @param array    $pairs
     */
    public function __construct($columns = array(), Database $dbh) {
        $this->dbh = $dbh;
        $this->_columns = $columns;
    }

    public function table($table) {
        $this->table = $table;
    }

    public function primaryKey($primary_key) {
        $this->primary_key = $primary_key;
    }

    public function execute(\Core\Database\Table $r, $retorno = true) {
        $columns = $this->_columns;
        $id = 0;
        if (isset($columns[$this->primary_key])) {
            $id = $r->update($columns);
        } else {
            $id = $r->insert($columns);
        }
        return $r->get($id);
    }

}
