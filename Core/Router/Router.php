<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Core\Router;

use Core\Router\RouterException;

/**
 * Description of Router
 *
 * @author lucas
 */
class Router {

    //put your code here

    private $url;
    private $routes = [];
    private $namedRoutes = [];

    public function __construct($url = null) {
        $this->url = $url;
    }

    public function get($path, $callable, $name = null) {
        return $this->add($path, $callable, $name, 'GET');
    }

    public function put($path, $callable, $name = null) {
        return $this->add($path, $callable, $name, 'PUT');
    }

    public function delete($path, $callable, $name = null) {
        return $this->add($path, $callable, $name, 'DELETE');
    }

    public function post($path, $callable, $name = null) {
        return $this->add($path, $callable, $name, 'POST');
    }

    public function auto($path, $callable, $name = null) {
        return $this->add($path, $callable, $name, $_SERVER['REQUEST_METHOD']);
    }

    public function descobre() {
        $this->diretorios();
        return $this->url;
    }

    public function diretorios() {
        $path = explode('/', trim($this->url, '/'));
        if (count($path) > 0) {
            $dir = '';
            foreach ($path as $key => $value) {
                if (is_dir('src/Controller' . DIRECTORY_SEPARATOR . $dir . $value)) {
                    $dir .= $value . DIRECTORY_SEPARATOR;
                } else {
                    $this->get($this->url, ['controller' => $dir . $value, 'action' => (!empty($path[$key + 1]) ? $path[$key + 1] : 'index')]);
                    break;
                }
            }
            return '/' . trim($dir, '/');
        } else {
            return '';
        }
    }

    private function add($path, $callable, $name, $method) {
        $route = new Route($path, $callable);
        $this->routes[$method][] = $route;
        if (is_string($callable) AND $name === null) {
            $name = $callable;
        }
        if ($name) {
            $this->namedRoutes[$name] = $route;
        }
        return $route;
    }

    public function run() {
        if (!isset($this->routes[$_SERVER['REQUEST_METHOD']])) {
            throw new RouterException('REQUEST_METHOD does not exist');
        }
        $error = true;
        foreach ($this->routes[$_SERVER['REQUEST_METHOD']] as $route) {
            if ($route->match($this->url)) {
                $route->call();
                $error = false;
            }
        }
        if ($error) {
            throw new RouterException('No matching routes');
        }
    }

    public function url($name, $params = []) {
        if (!isset($this->namedRoutes[$name])) {
            throw new RouterException('No route matches this name');
        }

        return $this->namedRoutes[$name]->getUrl($params);
    }

}
