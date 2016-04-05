<?php

namespace Core\Http;

use Core\Utilitys\Configure;
use Core\Utilitys\Inflector;

/**
 * Classe responsavel pela requisições que recebe o Sistema.
 *
 * @author Lucas Pinheiro
 */
class Request {

    /**
     *
     * Variavel que recebe todos os dados que vem do $_GET
     * 
     * @var array 
     */
    public $query = [];

    /**
     *
     * Variavel que recebe todos os dados que vem da navegação dos diretorios do sistema.
     * 
     * @var array 
     */
    public $path = [];

    /**
     *
     * Variavel que recebe todos os dados que vem da navegação após a identidicação dos diretorios.
     * 
     * @var array 
     */
    public $uri = [];

    /**
     *
     * Variavel que recebe todos os dados que vem da navegação após a identidicação dos diretorios.
     * 
     * @var array 
     */
    private $_url = '';

    /**
     *
     * Variavel que recebe todos os dados que vem do $_POST
     * 
     * @var array 
     */
    public $data = [];

    /**
     *
     * Recebe os dados de parametros do sistema
     * 
     * @var array 
     */
    public $params = [];

    /**
     *
     * informa qual o controller que deve ser chamado
     * 
     * @var string 
     */
    public $schema = 'http://';

    /**
     *
     * informa qual o controller que deve ser chamado
     * 
     * @var string 
     */
    public $controller = 'Home';

    /**
     *
     * informa qual o action que deve ser chamado
     * 
     * @var string 
     */
    public $action = 'index';

    /**
     * Função de auto execução ao startar a classe.
     */
    public function __construct() {
        $this->schema = $this->isHttps();
        $this->_url = $this->schema . $_SERVER["HTTP_HOST"] . '/' . implode('/', array_slice(explode('/', trim($_SERVER["SCRIPT_NAME"], '/')), 0, -2)) . '/';
        $ex = explode('/', trim($_SERVER['SCRIPT_NAME'], '/'));

        $this->path = array_slice($ex, 0, -2);
        $ex = explode('?', $_SERVER['REQUEST_URI']);
        $ex = explode('/', trim($ex[0], '/'));
        $ex = array_diff($ex, $this->path);
        $this->path = [];
        $this->uri = $ex;
        $this->match(implode('/', $this->uri));

        if (count($this->uri) > 0) {
            $diretorio_name = ROOT . 'src' . DS . 'Controller';
            $count_indice = 0;
            foreach ($this->uri as $key => $value) {
                $diretorio_name .= DS . Inflector::camelize($value);
                if (is_dir($diretorio_name)) {
                    $count_indice++;
                    $this->path[] = $value;
                }
            }
            $this->uri = array_slice($this->uri, $count_indice);
        }

        if (isset($this->uri[0])) {
            $this->controller = Inflector::camelize($this->uri[0]);
        }
        if (isset($this->uri[1])) {
            $this->action = Inflector::underscore($this->uri[1]);
        }
        if (isset($this->uri[2])) {
            $this->params = array_slice($this->uri, 2);
        }

        unset($ex);
        $this->data = $_POST;
        $this->query = $_GET;
    }

    /**
     * 
     * @param string Chave de navegação
     * @param string Resutado default caso não for achado nenhum resultado referente a navegação
     * @return array|string|null
     */
    public function data($key = null, $default = null) {
        if (is_null($key)) {
            return $this->data;
        }
        $this->data = json_encode($this->data);
        $this->data = json_decode($this->data, true);
        $s = Hash::get($this->data, $key);
        if (is_null($s)) {
            return $default;
        }
       
        return $this->forceBollean($s);
    }

    /**
     * 
     * @param string Chave de navegação
     * @param string Resutado default caso não for achado nenhum resultado referente a navegação
     * @return array|string|null
     */
    public function params($key = null, $default = null) {
        if (is_null($key)) {
            return $this->params;
        }
        $s = Hash::get($this->params, $key);
        if (is_null($s)) {
            return $default;
        }
        return $this->forceBollean($s);
    }

    /**
     * 
     * @param string Chave de navegação
     * @param string Resutado default caso não for achado nenhum resultado referente a navegação
     * @return array|string|null
     */
    public function query($key = null, $default = null) {
        if (is_null($key)) {
            return $this->query;
        }
        $s = Hash::get($this->query, $key);
        if (is_null($s)) {
            return $default;
        }
        return $this->forceBollean($s);
    }

    /**
     * 
     * Retorna uma url formatada
     * 
     * @param string $url
     * @return string
     */
    public function url($url = null) {
        if (is_array($url)) {
            $url = '/' . implode('/', $this->prepareUrl($url));
        }
        $find = preg_match("/(http|https|ftp):\/\/(.*?)$/i", $url, $matches);
        if ($find === 0) {
            if (substr($url, 0, 4) == 'tel:') {
                return $url;
            }
            return trim($this->_url, '/') . '/' . trim($url, '/');
        }
        return $url;
    }

    public function prepareUrl($url) {
        $defautl = [
            'action' => $this->action,
            'controller' => $this->controller,
            'path' => $this->path,
            'params' => '',
            'query' => [],
        ];
        $url = array_merge($defautl, $url);
        if (!empty($url['path'])) {
            if (is_array($url['path'])) {
                foreach ($url['path'] as $key => $value) {
                    $url['path'][$key] = Inflector::underscore(Inflector::camelize($value));
                }
            } else {
                $url['path'] = Inflector::underscore(Inflector::camelize($url['path']));
            }
        }

        if (!empty($url['params'])) {
            foreach ($url['params'] as $key => $value) {
                $url['params'][$key] = Inflector::slug($value);
            }
        }
        $oldUrl = $url;
        unset($oldUrl['action'], $oldUrl['controller'], $oldUrl['path'], $oldUrl['params'], $oldUrl['query'], $oldUrl['?']);
        if (!empty($oldUrl)) {
            //$url['params'] = array_merge($url['params'], $oldUrl);
            foreach ($oldUrl as $key => $value) {
                unset($url[$key]);
                $url['params'][$key] = $value;
            }
        }
        if (!empty($url['?'])) {
            $url['query'] = Hash::merge($url['query'], $url['?']);
            unset($url['?']);
        }
        $_url = [];
        if (!empty($url['path'])) {
            if (is_array($url['path'])) {
                $_url['path'] = implode('/', $url['path']);
            } else {
                $_url['path'] = $url['path'];
            }
        }
        if (!empty($url['controller'])) {
            $_url['controller'] = Inflector::underscore($url['controller']);
        }
        if (!empty($url['action'])) {
            $_url['action'] = Inflector::underscore($url['action']);
        }
        if (!empty($url['params']) and is_array($url['params'])) {
            $_url['params'] = implode('/', $url['params']);
        }
        if (!empty($url['query']) and is_array($url['query'])) {
            $_url['query'] = '?' . http_build_query($url['query']);
        }
        return $_url;
    }

    public function isPut() {
        return $this->isMethod('PUT');
    }

    public function isDelete() {
        return $this->isMethod('DELETE');
    }

    public function isPost() {
        return $this->isMethod('POST');
    }

    public function isGet() {
        return $this->isMethod('GET');
    }

    public function isMethod($method) {
        if (!is_array($method)) {
            $method = [$method];
        }
        foreach ($method as $key => $value) {
            if (strtoupper(trim($value)) === $_SERVER['REQUEST_METHOD']) {
                return true;
            }
        }
        return false;
    }

    /**
     * 
     * Faz o tratamento das url para definar os dados de rotas a ser usados.
     * 
     * @param string $uriPath
     */
    public function match($uriPath) {
        $uriPath = '/' . trim($uriPath, '/');
        Configure::load('rotas');
        $rotas = Configure::read('rotas');
        if (count($rotas) > 0) {
            foreach ($rotas as $route => $actualPage) {
                $route_regex = preg_replace('@:[^/]+@', '([^/]+)', $route);
                if (!preg_match('@' . $route_regex . '@', $uriPath, $matches)) {
                    continue;
                }
                $r = preg_match('@' . $route_regex . '@', $route, $identifiers);
                if ($r > 0) {
                    if ($identifiers[0] === $uriPath) {
                        $this->uri = [];
                        foreach (explode('.', $actualPage) as $k => $v) {
                            $this->uri[$k] = $v;
                        }
                    }
                }
            }
        }
    }

    public function setData($dados) {
        $dados = (array) $dados;
        //debug((array) $dados);
        //$dados = (json_decode(json_encode($dados), true));
        //$this->data = Hash::merge($this->data, json_decode(json_encode($dados), true));
        $this->data = Hash::merge($this->data, $dados);
        $_POST = Hash::merge($_POST, $this->data);
    }

    public function setQuery($dados) {
        $dados = (array) $dados;
        //$this->query = Hash::merge($this->query, json_decode(json_encode($dados), true));
        $this->query = Hash::merge($this->query, $dados);
        $_GET = Hash::merge($_GET, $this->query);
    }

    public function referer() {
        return $_SERVER['HTTP_REFERER'];
    }

    public function redirect($url) {
        $url = $this->url($url);
        header('location:' . $url);
        exit;
    }

    public function isHttps() {
        if (isset($_SERVER['HTTPS']) AND $_SERVER['HTTPS'] == "on") {
            return 'https://';
        }
        return 'http://';
    }

}
