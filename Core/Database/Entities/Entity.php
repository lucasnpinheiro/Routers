<?php

namespace Core\Database\Entities;

/**
 * Classe que realiza o Entity dos dados que vem do banco de dados informado.
 *
 * @author Lucas Pinheiro
 */
class Entity {

    use \Core\Loads;

    private $contain = [];
    private $schema = [];

    public function __construct($dados = null, $schema = null) {
        if (!empty($schema)) {
            $this->setSchema($schema);
        }
        if (!empty($dados)) {
            foreach ($dados as $key => $value) {
                $this->set($key, $value);
            }
        }
    }

    public function __destruct() {
        unset($this->schema);
        unset($this->contain);
    }

    public function setSchema($schema) {
        $this->schema = $schema;
    }

    public function __set($name, $value = null) {
        $this->set($name, $value);
    }

    public function set($name, $value = null) {
        if ($value === '' OR is_null($value)) {
            $this->{$name} = null;
        } else {
            switch ($this->_type($name)) {
                case 'int':
                case 'integer':
                    $this->{$name} = (int) $value;
                    break;

                case 'float':
                case 'double':
                case 'decimal':
                    $this->{$name} = (float) $value;
                    break;

                case 'datetime':
                    $date = new \Core\Database\Types\DateTime($value);
                    $this->{$name} = $date->toSQL($value);
                    break;

                case 'date':
                    $date = new \Core\Database\Types\Date($value);
                    $this->{$name} = $date->toSQL($value);
                    break;

                case 'time':
                    $date = new \Core\Database\Types\Time($value);
                    $this->{$name} = $date->toSQL($value);
                    break;

                case 'boolean':
                case 'bit':
                    $this->{$name} = (bool) $value;
                    break;

                default:
                    $this->{$name} = $value;
                    if (is_string($value)) {
                        if (strtolower($value) === 'false') {
                            $this->{$name} = (bool) false;
                        } else if (strtolower($value) === 'true') {
                            $this->{$name} = (bool) true;
                        } else if (strtolower($value) === 'null') {
                            $this->{$name} = NULL;
                        }
                    }
                    break;
            }
        }
    }

    public function __get($name) {
        if (isset($this->{$name})) {
            return $this->{$name};
        }
        return null;
    }

    private function _type($name) {
        if (!empty($this->schema[$name])) {
            $type = explode('(', $this->schema[$name]);
            return $type[0];
        }
        return 'varchar';
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

    private function _relacoes($class, array $options = []) {
        $defautl = [
            'className' => '',
            'foreignKey' => '',
            'from' => '*',
            'where' => [],
            'order' => [],
            'group' => [],
            'contain' => [],
        ];
        $options = array_merge($defautl, $options);
        if (empty($options['className'])) {
            $options['className'] = $class;
        }
        $table = $this->loadModel($options['className'], false);
        if (is_array($this->contain[$class])) {
            $extra = $this->contain[$class];
            if (!empty($extra['contain'])) {
                $table->contain($extra['contain']);
                unset($extra['contain']);
            }
            $options = array_merge($options, $extra);
        }
        unset($this->contain[$class]);
        $table->processa($options);
        return $table;
    }

    public function belongsTo($class, array $options = []) {
        if (!empty($this->contain[$class])) {
            $table = $this->_relacoes($class, $options);
            $table->where($options['foreignKey'], $this->{$table->primary_key});
            $this->{$class} = new \Core\Utilitys\Obj($table->all());
            return $this;
        }
    }

    public function hasOne($class, array $options = []) {
        if (!empty($this->contain[$class])) {
            $table = $this->_relacoes($class, $options);
            $table->where($table->primary_key, $this->{$options['foreignKey']});
            $this->{$class} = $table->find();
            return $this;
        }
    }

    public function hasMany($class, array $options = []) {
        if (!empty($this->contain[$class])) {
            $table = $this->_relacoes($class, $options);
            $table->where($table->primary_key, $this->{$options['foreignKey']});
            $this->{$class} = $table->all();
            return $this;
        }
    }

}
