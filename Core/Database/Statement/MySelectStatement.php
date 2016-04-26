<?php

/**
 * @license MIT
 * @license http://opensource.org/licenses/MIT
 */

namespace Core\Database\Statement;

use Slim\PDO\Database;

/**
 * Class SelectStatement.
 *
 * @author Fabian de Laender <fabian@faapz.nl>
 */
class MySelectStatement extends \Slim\PDO\Statement\SelectStatement {

    private $_contain = [];

    /**
     * Constructor.
     *
     * @param Database $dbh
     * @param array    $columns
     */
    public function __construct(Database $dbh, array $columns) {
        if (empty($columns)) {
            $columns = array_keys($dbh->schema);
        }
        parent::__construct($dbh, $columns);
    }

    public function all() {
        $stmt = $this->execute();
        $return = $stmt->fetchAll();
        if (count($return) === 0) {
            return new \Core\Utilitys\Obj(array());
        }
        foreach ($return as $key => $value) {
            $return[$key] = new \Core\Utilitys\Obj($this->trataResult($value));
        }
        return new \Core\Utilitys\Obj($return);
    }

    public function first() {
        $stmt = $this->execute();
        $return = $stmt->fetch();
        if (count($return) === 0) {
            return new \Core\Utilitys\Obj(array());
        }
        return new \Core\Utilitys\Obj($this->trataResult($return));
    }

    private function trataResult($result) {
        $c = new $this->dbh->classe($this->dbh->schema, $this->dbh->primary_key);
        foreach ($result as $k => $v) {
            $c->$k = $v;
        }
        $c->populaGet();
        $c->contain($this->_contain);
        $c->relacoes();
        return $c->itens();
    }

    public function contain($class) {
        if (is_array($class)) {
            foreach ($class as $key => $value) {
                if (is_array($value)) {
                    $this->_contain[$key] = $value;
                } else {
                    $this->_contain[$value] = $value;
                }
            }
        } else {
            $this->_contain[$class] = $class;
        }
        return $this;
    }

    public function prepare(array $options = []) {
        $default = [
            'conditions' => [],
            'fields' => [],
            'limit' => [],
            'order' => [],
            'group' => [],
        ];
        $options = array_merge($default, $options);

        $this->setColumns($options['fields']);

        if (!empty($options['conditions'])) {
            $this->prepareWhere($options['conditions']);
        }
        if (!empty($options['order'])) {
            foreach ($options['order'] as $key => $value) {
                if (is_array($value)) {
                    foreach ($value as $k => $v) {
                        if (is_numeric($k)) {
                            $this->orderBy($v);
                        } else {
                            $this->orderBy($k, $v);
                        }
                    }
                } else {
                    if (is_numeric($key)) {
                        $this->orderBy($value);
                    } else {
                        $this->orderBy($key, $value);
                    }
                }
            }
        }
        if (!empty($options['group'])) {
            foreach ($options['group'] as $key => $value) {
                if (is_array($value)) {
                    foreach ($value as $k => $v) {
                        $this->groupBy($v);
                    }
                } else {
                    $this->groupBy($value);
                }
            }
        }
        if (!empty($options['limit'])) {
            if (is_array($options['limit'])) {
                $k = array_keys($options['limit']);
                $this->limit($k[0], $options['limit'][0]);
            } else {
                $this->limit($options['limit']);
            }
        }
        return $this;
    }

    private function prepareWhere($conditions) {
        foreach ($conditions as $key => $value) {
            $key = trim($key);
            $ex = explode(' ', $key);
            $ex[1] = strtolower(isset($ex[1]) ? $ex[1] : '=');
            switch ($ex[1]) {
                
                case 'in':
                    $value = (!is_array($value) ? [$value] : $value);
                    $this->whereIn($ex[0], $value);
                    break;
                
                case 'like':
                    $value = (!is_array($value) ? [$value] : $value);
                    $this->whereLike($ex[0], '%' . $value . '%');
                    break;

                default:
                    $this->where($ex[0], $ex[1], $value);
                    break;
            }
        }
        
        return $this;
    }

    public function debug() {
        debug($this->__toString());
    }

}
