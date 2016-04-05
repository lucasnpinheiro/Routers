<?php

namespace Core;

/**
 * Description of Loads
 *
 * @author lucas
 */
trait Loads {

    //put your code here

    public function loadModel($name, $obj = true) {
        $table = str_replace('Table', '', $name) . 'Table';
        $name = str_replace('Table', '', $name);
        $table = '\App\Model\Table\\' . $table;
        $table = str_replace('/', '\\', $table);
        if ($obj) {
            $this->{$name} = new $table();
        }
        return new $table();
    }

    public function loadComponent() {
        
    }

}
