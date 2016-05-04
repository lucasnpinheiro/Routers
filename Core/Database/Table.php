<?php

namespace Core\Database;

use Core\Utilitys\Configure;
use Slim\PDO\Statement\SelectStatement;
use Slim\PDO\Statement\InsertStatement;
use Slim\PDO\Statement\UpdateStatement;
use Slim\PDO\Statement\DeleteStatement;

/**
 * Classe que realiza o ponte da classe databese para uma classe pré para tratamentos de algumas informações genericas no banco de dados.
 *
 * @author Lucas Pinheiro
 */
class Table extends \Slim\PDO\Database {

    protected $table = null;
    public $primary_key = 'id';
    public $classe = null;
    public $alias = null;
    public $schema = [];
    public $error = null;
    private $valid = true;

    public function __construct() {
        Configure::load('database');
        parent::__construct(Configure::read('database.drive') . ':host=' . Configure::read('database.host') . ';dbname=' . Configure::read('database.banco') . ';charset=utf8', Configure::read('database.usuario'), Configure::read('database.senha'));
        $class = explode('\\', get_class($this));
        $class = end($class);
        $this->classe = substr($class, 0, -5) . 'Entity';
        $this->alias = substr($class, 0, -5);
        $this->classe = '\\App\\Model\\Entity\\' . $this->classe;
        $this->schema = $this->columnsTypes();
    }

    /**
     * @param array $id
     *
     * @return SelectStatement
     */
    public function get($id) {
        $r = $this->select()->where($this->primary_key, '=', $id);
        return $r->first();
    }

    /**
     * @param array $columns
     *
     * @return SelectStatement
     */
    public function select(array $columns = array()) {
        $r = new Statement\MySelectStatement($this, $columns);
        $r->from($this->table);
        return $r;
    }

    /**
     * @param array $columns
     *
     * @return InsertStatement
     */
    public function insert($columns = array()) {
        $columns = $this->trataColumns($columns);
        if (array_key_exists(\Core\Utilitys\Configure::read('database.columCreated'), $this->schema)) {
            if (!array_key_exists(\Core\Utilitys\Configure::read('database.columCreated'), $columns)) {
                $columns[\Core\Utilitys\Configure::read('database.columCreated')] = date('Y-m-d H:i:s');
            }
        }
        $r = new Statement\MyInsertStatement($this, array_keys($columns));
        $r->into($this->table);
        $insert = $r->values(array_values($columns));
        return $insert->execute(true);
    }

    /**
     * @param array $pairs
     *
     * @return UpdateStatement
     */
    public function update($pairs = array()) {
        $pairs = $this->trataColumns($pairs);
        if (array_key_exists(\Core\Utilitys\Configure::read('database.columModified'), $this->schema)) {
            if (!array_key_exists(\Core\Utilitys\Configure::read('database.columModified'), $pairs)) {
                $pairs[\Core\Utilitys\Configure::read('database.columModified')] = date('Y-m-d H:i:s');
            }
        }
        $r = new Statement\MyUpdateStatement($this, $pairs);
        $r->table($this->table);
        $update = $r->where($this->primary_key, '=', $pairs[$this->primary_key]);
        $update->execute();
        return $pairs[$this->primary_key];
    }

    /**
     * @param array $columns
     *
     * @return SaveStatement
     */
    public function save($columns = array()) {
        $columns = $this->beforeSave($columns);
        $v = $this->_validation($columns);
        if ($v === true) {
            $r = new Statement\SaveStatement($columns, $this);
            $r->table($this->table);
            $r->primaryKey($this->primary_key);
            $id = $r->execute($this, true);
            return $this->afterSave($id, $columns, (bool) isset($columns[$this->primary_key]));
        } else {
            return false;
        }
    }

    /**
     * @param null $id
     *
     * @return DeleteStatement
     */
    public function delete($id) {
        $columns = $this->get($id);
        $columns = $this->beforeDelete($id, $columns);
        $r = new DeleteStatement($this, $this->table);
        $r->from($this->table);
        $r->where($this->primary_key, '=', $id);
        $exec = $r->execute();
        return $this->afterDelete($id, $columns, (bool) $exec);
    }

    /**
     *
     * @return DeleteStatement
     */
    public function deleteAll() {
        $r = new DeleteStatement($this, $this->table);
        $r->from($this->table);
        return $r;
    }

    public function beforeSave($columns = array()) {
        return $columns;
    }

    public function afterSave($id, $columns = array(), $new = false) {
        return $id;
    }

    public function beforeDelete($id, $columns = array()) {
        return $columns;
    }

    public function afterDelete($id, $columns = array(), $success = false) {
        return $success;
    }

    public function q($sql) {
        $r = new Statement\MyQueryStatement($this, $sql);
        $stmt = $r->execute();
        return $stmt;
    }

    /**
     * 
     * função que exclui a tabela do banco de dados
     * 
     * @return boolean
     */
    public function columns() {
        $c = $this->q('SHOW FULL COLUMNS FROM ' . Configure::read('database.banco') . '.' . $this->table)->fetchAll(\PDO::FETCH_OBJ);
        if (count($c)) {
            $co = [];
            foreach ($c as $key => $value) {
                $co[$value->Field] = [];
                foreach ($value as $k => $v) {
                    if ($k != 'Field') {
                        $co[$value->Field][strtolower($k)] = $v;
                    }
                }
            }
            return $co;
        }
        return null;
    }

    /**
     * 
     * função que exclui a tabela do banco de dados
     * 
     * @return boolean
     */
    public function columnsTypes() {
        $c = $this->q('SHOW FULL COLUMNS FROM ' . Configure::read('database.banco') . '.' . $this->table)->fetchAll(\PDO::FETCH_OBJ);
        if (count($c)) {
            $co = [];
            foreach ($c as $key => $value) {
                $co[$value->Field] = $value->Type;
            }
            return $co;
        }
        return null;
    }

    public function newEntity($dados = []) {
        $c = new $this->classe($this->schema);
        foreach ($this->schema as $k => $v) {
            $c->$k = '';
        }
        if (count($dados) > 0) {
            foreach ($dados as $key => $value) {
                $c->$key = $value;
            }
        }
        $c->populaSet();
        $retorno = $c->itens();
        return new \Core\Utilitys\Obj($retorno);
    }

    private function trataColumns($_columns) {
        $columns = [];
        if (count($_columns) > 0) {
            if (is_array($this->schema)) {
                foreach ($this->schema as $key => $value) {
                    if (isset($_columns[$key])) {
                        if (method_exists($_columns[$key], '__toString')) {
                            $columns[$key] = $_columns[$key]->__toString();
                        } else {
                            $columns[$key] = $_columns[$key];
                        }
                        if ($columns[$key] === '') {
                            $columns[$key] = null;
                        }
                    }
                }
            }
        }
        return $columns;
    }

    private function _validation($columns) {
        $v = $this->validation(new \Core\Validation\Validation($columns));
        $r = $v->run();
        if ($r === false) {
            $this->error = $v->error();
            $this->valid = false;
            return false;
        }
        return true;
    }

    public function validation(\Core\Validation\Validation $valitador) {
        return $valitador;
    }

    public function valid() {
        return $this->valid;
    }

}
