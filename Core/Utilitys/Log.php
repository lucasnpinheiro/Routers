<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Core\Utilitys;

/**
 * Description of Log
 *
 * @author lucas
 */
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);

define('FOPEN_READ', 'rb');
define('FOPEN_READ_WRITE', 'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE', 'ab');
define('FOPEN_READ_WRITE_CREATE', 'a+b');
define('FOPEN_WRITE_CREATE_STRICT', 'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

use Core\Request;

class Log {

    public static function write($msg, $level = 'error') {
        $level = strtoupper(Inflector::camelize($level));
        $diretorio = ROOT . 'src/tmp/logs/';
        $filepath = $diretorio . 'log-' . $level . '-' . date('Y-m-d') . '.log';

        $r = new \Core\Http\Request();
        $msg = var_export($msg, true);
        $message = [
            'Nivel: ' . $level,
            'Time: ' . date('H:i:s'),
            'Path: ' . implode('/', $r->path),
            'Uri: ' . implode('/', $r->uri),
            'Data: ' . print_r($r->data, true),
            'Params: ' . implode('/', $r->params),
            'Query: ' . print_r($r->query, true),
            $msg,
        ];

        $message = implode("\n", $message) . "\n\n";
        if (!$fp = @fopen($filepath, FOPEN_WRITE_CREATE)) {
            return FALSE;
        }
        flock($fp, LOCK_EX);
        fwrite($fp, $message);
        flock($fp, LOCK_UN);
        fclose($fp);
        @chmod($filepath, FILE_WRITE_MODE);
        return TRUE;
    }

}
