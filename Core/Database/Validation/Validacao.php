<?php

namespace Core\Database\Validation;

/**
 * Classe para criar tabela em HTML
 *
 * @author Lucas Pinheiro
 */
class Validacao {

    /**
     *
     * Variavel que salva a classe que esta sendo validada
     * 
     * @var object 
     */
    private $classe = null;

    /**
     *
     * Variavel que salva as regras de validação
     * 
     * @var array 
     */
    private $dados = [];

    /**
     *
     * Variavel que salva os erros gerados
     * 
     * @var array 
     */
    private $errors = [];

    /**
     *
     * Variavel que salva as mensagem de erros
     * 
     * @var array 
     */
    private $msg = [
        'numero' => 'Somente numeros.',
        'data' => 'Data Invalida.',
        'hora' => 'Hora Invalida.',
        'moeda' => 'Moeda invalida.',
        'email' => 'E-mail invalido.',
        'required' => 'Campo obrigatorio.',
        'min' => 'Quantidade minima de "%s" caracteres.',
        'max' => 'Quantidade maxima de "%s" caracteres.',
        'extensao' => 'Extensão "%s" não é valida.',
        'contem' => 'Valor "%s" não localizado.',
        'unique' => 'Registro com este valor já cadastrado na tabela "%s".',
    ];

    /**
     *
     * Variavel que salva os dados a serem validados
     * 
     * @var array 
     */
    private $campos;

    /**
     * 
     * Função de auto execução ao startar a classe.
     * 
     * @param array $campo
     * @param object $classe
     */
    public function __construct($campo, &$classe) {
        $this->campos = $campo;
        $this->classe = $classe;
    }

    /**
     * 
     * Adiciona uma nova regra
     * 
     * @param string $campo
     * @param string $regra
     * @param string $adicionais
     */
    public function add($campo, $regra, $adicionais = null) {
        $this->dados[$campo][$regra] = $adicionais;
    }

    /**
     * 
     * executa as validações
     * 
     */
    public function run() {
        foreach ($this->dados as $key => $value) {
            if (is_array($value)) {
                foreach ($value as $k => $v) {
                    if (is_numeric($k)) {
                        $k = $v;
                    }
                    if (!$this->$k($key, $v)) {
                        $this->errors[$key][$k] = sprintf($this->msg[$k], $v);
                    }
                }
            } else {
                if (!$this->$k($key)) {
                    $this->errors[$key] = sprintf($this->msg[$value], $value);
                }
            }
        }
    }

    /**
     * 
     * Retorna os erros que foram localizados
     * 
     * @return array
     */
    public function error() {
        return $this->errors;
    }

    /**
     * 
     * Valida se o valor é numerico
     * 
     * @param string $campo
     * @return bollean
     */
    public function numero($campo) {
        return (bool) is_numeric($this->campos[$campo]);
    }

    /**
     * 
     * Valida se o valor é monetario
     * 
     * @param string $campo
     * @return bollean
     */
    public function moeda($campo) {
        $this->campos[$campo] = str_replace('.', '', $this->campos[$campo]);
        $this->campos[$campo] = str_replace(',', '.', $this->campos[$campo]);
        return (bool) is_float($this->campos[$campo]);
    }

    /**
     * 
     * Valida se o valor é data
     * 
     * @param string $campo
     * @return bollean
     */
    public function data($campo) {
        $data = explode('-', $this->campos[$campo]);
        return (bool) checkdate($data[1], $data[2], $data[0]);
    }

    /**
     * 
     * Valida se o valor é hora
     * 
     * @param string $campo
     * @return bollean
     */
    public function hora($campo) {
        $hora = explode(':', $this->campos[$campo]);
        $count = count($hora);
        switch ($count) {
            case 2: // Hora e minuto
                if (($hora[0] >= 0 and $hora[0] < 24) and ( $hora[1] >= 0 and $hora[1] < 60)) {
                    return true;
                }
                break;
            case 1: // Hora
                if (($hora[0] >= 0 and $hora[0] < 24)) {
                    return true;
                }
                break;

            default: // Hora, minuto e segundo
                if (($hora[0] >= 0 and $hora[0] < 24) and ( $hora[1] >= 0 and $hora[1] < 60) and ( $hora[1] >= 0 and $hora[1] < 60)) {
                    return true;
                }
                break;
        }
        return false;
    }

    /**
     * 
     * Valida se o valor é obrigatorio
     * 
     * @param string $campo
     * @return bollean
     */
    public function required($campo) {
        if (isset($this->campos[$campo]) and trim($this->campos[$campo]) != '') {
            return true;
        }
        return false;
    }

    /**
     * 
     * Valida se o valor contém no minimo X caracteres
     * 
     * @param string $campo
     * @param int $qtd
     * @return bollean
     */
    public function min($campo, $qtd) {
        if (strlen($this->campos[$campo]) >= $qtd) {
            return true;
        }
        return false;
    }

    /**
     * 
     * Valida se o valor contém no maximo X caracteres
     * 
     * @param string $campo
     * @param int $qtd
     * @return bollean
     */
    public function max($campo, $qtd) {
        if (strlen($this->campos[$campo]) <= $qtd) {
            return true;
        }
        return false;
    }

    /**
     * 
     * Valida a extensão do arquivo
     * 
     * @param string $campo
     * @param string $extensao
     * @return bollean
     */
    public function extensao($campo, $extensao) {
        if ((new \SplFileInfo($this->campos[$campo]))->getExtension() === trim($extensao, '.')) {
            return true;
        }
        return false;
    }

    /**
     * 
     * Valida se o valor é email
     * 
     * @param string $campo
     * @return bollean
     */
    public function email($campo) {
        debug($campo);
        return (bool) filter_var($this->campos[$campo], FILTER_VALIDATE_EMAIL);
    }

    /**
     * 
     * Valida se o valor contem em uma lista
     * 
     * @param string $campo
     * @param array $dados
     * @return bollean
     */
    public function contem($campo, $dados = []) {
        return (bool) in_array($this->campos[$campo], $dados);
    }

    /**
     * 
     * Valida se o valor contem em uma lista
     * 
     * @param string $campo
     * @return bollean
     */
    public function unique($campo, $where = []) {
        $find = $this->classe->where($campo, $this->campos[$campo]);
        if (!empty($where)) {
            foreach ($where as $key => $value) {
                switch (count($value)) {
                    case 4:
                        $find->where($value[0], $value[1], $value[2], $value[3]);
                        break;
                    case 3:
                        $find->where($value[0], $value[1], $value[2]);
                        break;

                    default:
                        $find->where($value[0], $value[1]);
                        break;
                }
            }
        }
        $find = $find->first();
        if (!empty($find)) {
            if (!empty($this->campos[$this->classe->primary_key])) {
                if ($find->{$this->classe->primary_key} != $this->campos[$this->classe->primary_key]) {
                    $this->msg['unique'] = sprintf($this->msg['unique'], $this->classe->alias);
                    return false;
                }
            } else {
                return false;
            }
        }
        return true;
    }

}
