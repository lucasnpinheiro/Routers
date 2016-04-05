<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Core\Database;

/**
 * Description of Conditions
 *
 * @author lucas
 */
class Conditions {

    /**
     *
     * Table informada
     * 
     * @var string 
     */
    public $alias = null;

    /**
     *
     * Table informada
     * 
     * @var string 
     */
    public $table = null;

    /**
     *
     * variavel que contem um array de argumentos para uso nos selects.
     * 
     * @var array 
     */
    protected $_where = [];

    /**
     *
     * variavel que contem um array de ordenação para uso nos selects.
     * 
     * @var array 
     */
    protected $_order = [];

    /**
     *
     * variavel que contem um array de agrupamento para uso nos selects.
     * 
     * @var array 
     */
    protected $_group = [];

    /**
     *
     * variavel que contem uma string de limit para uso nos selects.
     * 
     * @var string 
     */
    protected $_limit = null;

    /**
     *
     * variavel que contem uma string de campos que serão retornados para uso nos selects.
     * 
     * @var string 
     */
    protected $_from = '*';

    /**
     *
     * variavel que contem uma string de campos que serão retornados para uso nos selects.
     * 
     * @var string 
     */
    protected $schema = null;

    public function __construct($table, $alias, $schema) {
        $this->table = $table;
        $this->alias = $alias;
        $this->schema = $schema;
    }

    /**
     * 
     * função que faz o tratamento dos dados para consulta no banco de dados.
     * 
     * @param string $key
     * @param string|int|array $value
     * @param string $type
     * @param string $condition
     * @return \Core\Database\Database
     */
    public function where($key, $value = '', $type = '=', $condition = 'AND') {

        $type = strtoupper($type);
        switch ($type) {
            case '=':
                $value = $this->quote($value);
                if (is_array($value)) {
                    $this->_where[][$condition] = $key . ' IN("' . implode('", "', $value) . '")';
                } else {
                    $this->_where[][$condition] = $key . ' = "' . $value . '"';
                }
                break;

            case '!=':
            case '<>':
                $value = $this->quote($value);
                if (is_array($value)) {
                    $this->_where[][$condition] = $key . ' NOT IN("' . implode('", "', $value) . '")';
                } else {
                    $this->_where[][$condition] = $key . ' != "' . $value . '"';
                }
                break;
            case 'SUBSELECT':
                $this->_where[][$condition] = $key . ' NOT IN(' . $value . ')';

                break;

            case 'LIKE':
                $value = $this->quote($value, '%', '%');
                if (is_array($value)) {
                    foreach ($value as $k => $v) {
                        $this->_where[][$condition] = $key . ' LIKE "' . $v . '"';
                    }
                } else {
                    $this->_where[][$condition] = $key . ' LIKE "' . $value . '"';
                }
                break;

            default:
                if (trim($value) == '' AND trim($type) == '') {
                    $value = $this->quote($value);
                    $this->_where[][$condition] = $key;
                } else {
                    $value = $this->quote($value);
                    $this->_where[][$condition] = $key . ' ' . $type . ' "' . $value . '"';
                }

                break;
        }
        return $this;
    }

    /**
     * 
     * função que faz o tratamento dos dados para consulta no banco de dados.
     * 
     * @param string $key
     * @param string|int|array $value
     * @param string $type
     * @return \Core\Database\Database
     */
    public function orWhere($key, $value, $type = '=') {
        $this->where($key, $value, $type, 'OR');
        return $this;
    }

    /**
     * 
     * função que faz o tratamento dos dados para consulta no banco de dados.
     * 
     * @param string $key
     * @param string|int|array $value
     * @return \Core\Database\Database
     */
    public function likeWhere($key, $value) {
        $this->where($key, $value, 'LIKE', 'AND');
        return $this;
    }

    /**
     * 
     * função que faz o tratamento dos dados para consulta no banco de dados.
     * 
     * @param string $key
     * @param string|int|array $value
     * @return \Core\Database\Database
     */
    public function orLikeWhere($key, $value) {
        $this->where($key, $value, 'LIKE', 'OR');
        return $this;
    }

    /**
     * 
     * função que faz a junção dos tipos de ordenação do resultado no banco de dados.
     * 
     * @param string $key
     * @param string $order
     * @return \Core\Database\Database
     */
    public function order($key, $order = 'ASC') {
        $this->_order[] = $key . ' ' . strtoupper($order);
        return $this;
    }

    /**
     * 
     * função que faz a junção dos tipos de agrupamento de dados do resultado no banco de dados.
     * 
     * @param string $key
     * @return \Core\Database\Database
     */
    public function group($key) {
        $this->_group[] = $key;
        return $this;
    }

    /**
     * 
     * função que faz o limit de registro no banco de dados.
     * 
     * @param int $inicio
     * @param int $fim
     * @return \Core\Database\Database
     */
    public function limit($inicio = 1, $fim = null) {
        $this->_limit = trim($inicio . ' ' . (!is_null($fim) ? ', ' . $fim : ''));
        return $this;
    }

    /**
     * 
     * função que faz a junção dos dados para campos de pesquisa.
     * 
     * @param string|array $from
     * @return \Core\Database\Database
     */
    public function from($from = '*') {
        if (is_string($from) AND trim($from) === '*') {
            $from = $this->schema->columnsName();
            if (is_null($from)) {
                $from = '*';
            }
            $this->_from = trim((is_array($from) ? $this->alias . '.' . implode(', ' . $this->alias . '.', $from) : $from), ',');
        } else {
            $this->_from = trim((is_array($from) ? implode(', ', $from) : $from), ',');
        }
        return $this;
    }

    /**
     * 
     * função que faz a tratamento de todos os dados para gerar parte do sql para consulta no banco de dados.
     * 
     * @return array
     */
    public function _getWhere() {
        $where = [];
        if (count($this->_where) > 0) {
            foreach ($this->_where as $key => $value) {
                foreach ($value as $k => $v) {
                    $where[] = $k . ' ' . $v;
                }
            }
        }
        $return = [
            'where' => '',
            'order' => '',
            'group' => '',
            'limit' => '',
        ];
        $return['where'] = trim(trim(trim(implode(' ', $where), 'AND'), 'OR'));
        if (count($this->_order) > 0) {
            $return['order'] = implode(', ', $this->_order);
        }
        if (count($this->_group) > 0) {
            $return['group'] = implode(', ', $this->_group);
        }
        $return['limit'] = $this->_limit;
        if ($this->_from === '*') {
            $this->from($this->_from);
        }
        $return['from'] = $this->_from;
        $this->_where = [];
        $this->_order = [];
        $this->_group = [];
        $this->_limit = null;
        $this->_from = '*';
        return $return;
    }

    /**
     * 
     * @param type $value
     * @param type $before
     * @param type $after
     * @return type
     */
    private function quote($value, $before = '', $after = '') {
        if (is_array($value)) {
            foreach ($value as $k => $v) {
                $value[$k] = $before . $this->__defineTypes(trim($v)) . $after;
            }
            return $value;
        }
        return $before . $this->__defineTypes(trim($value)) . $after;
    }

    private function __defineTypes($value) {
        $value = (trim($value) == '' ? null : $value);
        switch (gettype($value)) {
            case 'int':
            case 'integer':
                return (int) $value;

                break;

            case 'float':
            case 'double':
                return (float) $value;

                break;

            case 'NULL':
                return null;

                break;

            case 'boolean':
                return (int) $value;

                break;

            case 'array':
            case 'object':
            case 'resource':
                return $value;

                break;

            default:
                if (strtolower(trim($value)) === 'false') {
                    return 0;
                } else if (strtolower(trim($value)) === 'true') {
                    return 1;
                }
                return (string) $value;
                break;
        }
    }

    public function processa(array $options = []) {
        $defautl = [
            'where' => [],
            'order' => [],
            'group' => [],
            'from' => [],
            'limit' => null,
        ];
        $options = array_merge($defautl, $options);
        if (!empty($options['where'])) {
            foreach ($options['where'] as $key => $value) {
                switch (count($value)) {
                    case 4:
                        $this->where($value[0], $value[1], $value[2], $value[3]);
                        break;
                    case 3:
                        $this->where($value[0], $value[1], $value[2]);
                        break;

                    default:
                        $this->where($value[0], $value[1]);
                        break;
                }
            }
        }
        if (!empty($options['order'])) {
            foreach ($options['order'] as $key => $value) {
                switch (count($value)) {
                    case 2:
                        $this->order($value[0], $value[1]);
                        break;

                    default:
                        $this->order($value[0]);
                        break;
                }
            }
        }
        if (!empty($options['group'])) {
            foreach ($options['group'] as $key => $value) {
                switch (count($value)) {
                    case 2:
                        $this->group($value[0], $value[1]);
                        break;

                    default:
                        $this->group($value[0]);
                        break;
                }
            }
        }
        if (!empty($options['from'])) {
            if (!is_array($options['from'])) {
                $options['from'] = explode(', ', $options['from']);
            }
            $this->from($options['from']);
        }
        if (!empty($options['limit'])) {
            if (is_array($options['limit'])) {
                if (count($options['limit']) === 2) {
                    $this->limit($options['limit'][0], $options['limit'][1]);
                } else {
                    $this->limit($options['limit'][0]);
                }
            } else {
                $this->limit($options['limit']);
            }
        }
        return $this;
    }

}
