<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Router;

/**
 * Description of Route
 *
 * @author lucas
 */
class Route {

    private $path;
    private $callable;
    private $matches = [];
    private $params = [];
    private $controller = null;
    private $action = null;

    //put your code here
    public function __construct($path, $callable) {
        $this->path = trim($path, '/');
        $this->callable = $callable;
    }

    public function match($url) {
        $url = trim($url, '/');
        $path = preg_replace_callback('#:([\w]+)#', [$this, 'paramMatch'], $this->path);


        $regex = "#^$path$#i";
        if (!preg_match($regex, $url, $matches)) {
            return false;
        }
        array_shift($matches);
        $this->matches = $matches;
        return true;
    }

    private function paramMatch($match) {
        if (isset($this->params[$match[1]])) {
            return '(' . $this->params[$match[1]] . ')';
        }

        return '([^/]+)';
    }

    public function with($params, $regex) {
        $this->params[$params] = str_replace('(', '(?:', $regex);
        return $this;
    }

    public function call() {
        if (is_string($this->callable)) {
            $this->prepareCallString();
            $controller = new $this->controller();
            return call_user_func_array([$controller, $this->action], $this->matches);
        } else if (is_array($this->callable)) {
            $this->prepareCallArray();
            $controller = new $this->controller();
            return call_user_func_array([$controller, $this->action], $this->matches);
        } else {
            return call_user_func_array($this->callable, $this->matches);
        }
    }

    private function prepareCallString() {
        $params = explode('#', $this->callable);
        $params[0] = str_replace('Controller', '', $params[0]);
        $controller = "App\\Controller\\" . $params[0] . "Controller";
        $this->controller = str_replace('/', '\\', $controller);
        $this->action = (!empty($params[1]) ? $params[1] : 'index');
    }

    private function prepareCallArray() {
        $default = [
            'controller' => null,
            'action' => null,
        ];
        $params = array_merge($default, $this->callable);
        $params['controller'] = str_replace('Controller', '', $params['controller']);
        $controller = "App\\Controller\\" . $params['controller'] . "Controller";
        $this->controller = str_replace('/', '\\', $controller);
        $this->action = (!empty($params['action']) ? $params['action'] : 'index');
        unset($params['controller'], $params['action']);
        if (!empty($params)) {
            $this->matches = array_merge($this->matches, $params);
        }
    }

    public function getUrl($params = []) {
        $path = $this->path;
        foreach ($params as $k => $v) {
            $path = str_replace(":k", $v, $path);
        }
        return $path;
    }

    public function data() {
        return [
            'matches' => $this->matches,
            'controller' => $this->controller,
            'action' => $this->action,
            'path' => $this->path,
            'callable' => $this->callable,
        ];
    }

}
