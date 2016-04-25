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

    public function __set($name, $value) {
        $this->itens[$name] = $value;
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
