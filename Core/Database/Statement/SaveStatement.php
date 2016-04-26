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
        $columns = $this->trataColumns();
        $id = 0;
        if (isset($columns[$this->primary_key])) {
            $columns[\Core\Utilitys\Configure::read('database.columModified')] = date('Y-m-d H:i:s');
            $update = $r->update($columns)->table($this->table)->where($this->primary_key, '=', $columns[$this->primary_key]);
            $update->execute();
            $id = $columns[$this->primary_key];
        } else {
            $columns[\Core\Utilitys\Configure::read('database.columCreated')] = date('Y-m-d H:i:s');
            $insert = $r->insert(array_keys($columns))->into($this->table)->values(array_values($columns));
            $id = $insert->execute($retorno);
        }
        return $r->get($id);
    }

    private function trataColumns() {

        $columns = [];
        foreach ($this->dbh->schema as $key => $value) {
            if (isset($this->_columns[$key])) {
                $columns[$key] = $this->_columns[$key]->__toString();
                if($columns[$key] === ''){
                    $columns[$key] = null;
                }
            }
        }
        debug($columns);
        return $columns;
    }

}
