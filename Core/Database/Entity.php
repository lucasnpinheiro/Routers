<?php

namespace Core\Database;

/**
 * Classe que realiza o Entity dos dados que vem do banco de dados informado.
 *
 * @author Lucas Pinheiro
 */
class Entity {

    use \Core\Loads;

    private $contain = [];
    private $itens = [];
    private $schema = [];
    private $primary_key = null;

    public function __construct($schema, $primary_key = null) {
        $this->schema = $schema;
        $this->primary_key = $primary_key;
    }

    private function _preparaValues($name, $value) {
        $type = strtolower(isset($this->schema[$name]) ? $this->schema[$name] : 'string');
        if ($type === 'tinyint(1)') {
            $type = 'boolean';
        } else {
            $type = explode('(', $type);
            $type = $type[0];
        }
        switch ($type) {
            case 'byte':
            case 'integer':
            case 'tinyint':
            case 'smallint':
            case 'mediumint':
            case 'bigint':
                return new Type\Integer($value);
                break;

            case 'double':
            case 'float':
            case 'decimal':
                return new Type\Float($value);
                break;

            case 'bool':
            case 'boolean':
                return new Type\Boolean($value);
                break;

            case 'date':
            case 'datetime':
            case 'timestamp':
            case 'time':
            case 'year':
                return new Type\DateTime($value);
                break;

            default:
                return new Type\String($value);
                break;
        }
    }

    public function __set($name, $value) {
        if(!isset($this->{$name})){
        $this->itens[$name] = $this->_preparaValues($name, $value);
    } else {
        $this->{$name} = $value;
    }
    }

    public function __get($name) {
        if (array_key_exists($name, $this->itens)) {
            return $this->itens[$name];
        }
        return null;
    }

    public function itens() {
        return (array) $this->itens;
    }

    public function populaGet() {
        $m = get_class_methods($this);
        if (!empty($m)) {
            foreach ($m as $key => $value) {
                if (substr($value, 0, 2) != '__') {
                    if (substr($value, 0, 4) === '_get') {
                        $this->{$value}();
                    }
                }
            }
        }
    }

    public function populaSet() {
        $m = get_class_methods($this);
        if (!empty($m)) {
            foreach ($m as $key => $value) {
                if (substr($value, 0, 2) != '__') {
                    if (substr($value, 0, 4) === '_set') {
                        $v = substr($value, 4, -1);
                        $this->{$value}($this->{$v});
                    }
                }
            }
        }
    }

    public function contain($str) {
        if (is_array($str)) {
            foreach ($str as $key => $value) {
                if (is_array($value)) {
                    $this->contain[$key] = $value;
                } else {
                    $this->contain[$value] = $value;
                }
            }
        } else {
            $this->contain[$str] = $str;
        }
    }

    public function relacoes() {
        
    }

    private function prepare($table, $class, array $options = []) {
        $table = $table->select();
        $table->prepare($options);
        $contain = $this->contain[$class];
        unset($this->contain[$class]);
        if (is_array($contain)) {
            $table->contain($contain);
        }
        return $table;
    }

    public function belongsTo($class, array $options = []) {
        if (!empty($this->contain[$class])) {
            $table = $this->loadModel($class, false);
            $table = $this->prepare($table, $class, $options);
            $this->itens[$class] = $table->where($options['foreignKey'], '=', $this->itens[$this->primary_key])->all();
            return $this;
        }
    }

    public function hasOne($class, array $options = []) {
        if (!empty($this->contain[$class])) {
            $table = $this->loadModel($class, false);
            $table = $this->prepare($table, $class, $options);
            $this->itens[$class] = $table->where($this->itens[$this->primary_key], '=', $options['foreignKey'])->first();
            return $this;
        }
    }

    public function hasMany($class, array $options = []) {
        if (!empty($this->contain[$class])) {
            $table = $this->loadModel($class, false);
            $table = $this->prepare($table, $class, $options);
            $this->itens[$class] = $table->where($this->itens[$this->primary_key], '=', $options['foreignKey'])->all();
            return $this;
        }
    }

}
