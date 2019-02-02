<?php
/**
 * Created by PhpStorm.
 * User: DMF
 * Date: 2017/12/20
 * Time: 13:50
 */

/**
 * 异常函数
 */
if (! function_exists('baseExceptionHandler')) {
    function baseExceptionHandler($exception)
    {
        echo '<div class="alert alert-danger">';
        echo '<b>Fatal error</b>:  Uncaught exception \'' . get_class($exception) . '\' with message ';
        echo $exception->getMessage() . '<br>';
        echo 'Stack trace:<pre>' . $exception->getTraceAsString() . '</pre>';
        echo 'thrown in <b>' . $exception->getFile() . '</b> on line <b>' . $exception->getLine() . '</b><br>';
        echo '</div>';
    }
}

/*
 * 因为php5无法接受E_ERROR错误，所以需要以下函数
 */
if (!function_exists('baseShutdownHandler')) {
    function baseShutdownHandler()
    {
        define('E_FATAL', E_ERROR | E_USER_ERROR | E_CORE_ERROR |
            E_COMPILE_ERROR | E_RECOVERABLE_ERROR | E_PARSE);
        $error = error_get_last();
        if ($error && ($error["type"] === ($error["type"] & E_FATAL))) {
            $errno = $error["type"];
            $errfile = $error["file"];
            $errline = $error["line"];
            $errstr = $error["message"];
            myErrorHandler($errno, $errstr, $errfile, $errline);
        }
    }
}


/**
 * 是否是AJAx提交的
 * @return bool
 */
if (function_exists('isAjax')) {
    function isAjax()
    {
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            return true;
        } else {
            return false;
        }
    }
}

/**
 * url函数
 * @return string
 *
 */
if (!function_exists('url')) {
    function url()
    {
        $Config = \Rice\Core\Core::get('Config');
        if ($Config['Rice']['show_url_index_php'] == false) {
            //return
        }
    }
}
