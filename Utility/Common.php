<?php
/**
 * Created by PhpStorm.
 * User: DMF
 * Date: 2017/12/20
 * Time: 13:50
 */

//ini_set('display_errors',1);            //错误信息
//ini_set('display_startup_errors',1);    //php启动错误信息
//error_reporting(-1);                    //打印出所有的 错误信息
//直接屏幕输出错误信息
//--------------------------------------------------
//如果要输出到文件就加这一句
//ini_set('error_log', dirname(dirname(__FILE__)) . '/error_log.txt'); //将出错信息输出到一个文本文件

date_default_timezone_set('Asia/Shanghai');

/*
 * 因为php5无法接受E_ERROR错误，所以需要以下函数
 */
register_shutdown_function("shutdown_handler");
function shutdown_handler() {
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
// error handler function
function myErrorHandler($errno, $errstr, $errfile, $errline)
{
    if (!(error_reporting() & $errno)) {
        // This error code is not included in error_reporting, so let it fall
        // through to the standard PHP error handler
        return false;
    }
    $str = date('Y-m-d H:i:s',time()).' ';
    switch ($errno) {
        case E_USER_ERROR:
            $str .= "<b>My ERROR</b> [$errno] $errstr<br />\n"
            ."  Fatal error on line $errline in file $errfile"
            .", PHP " . PHP_VERSION . " (" . PHP_OS . ")<br />\n"
            ."Aborting...<br />\n";
            file_put_contents(dirname(dirname(__FILE__)).'/error_log.txt',$str,FILE_APPEND | LOCK_EX);
            exit(1);
            break;

        case E_USER_WARNING:
            $str .= "<b>My WARNING</b> [$errno] $errstr<br />\n";
            break;

        case E_USER_NOTICE:
            $str .= "<b>My NOTICE</b> [$errno] $errstr<br />\n";
            break;

        default:
            $str .= "Unknown error type: [$errno] $errstr<br />\n"."  Fatal error on line $errline in file $errfile"
                .", PHP " . PHP_VERSION . " (" . PHP_OS . ")<br />\n";
            break;
    }
    file_put_contents(dirname(dirname(__FILE__)).'/error_log.txt',$str,FILE_APPEND | LOCK_EX);
    /* Don't execute PHP internal error handler */
    return true;
}

// set to the user defined error handler
$error_handler = set_error_handler("myErrorHandler");


/**
 * 是否是AJAx提交的
 * @return bool
 */
function isAjax(){
    if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
        return true;
    }else{
        return false;
    }
}

/**
 * url函数
 * @return string
 *
 */

function url() {
    $Config = \Rice\Core\Core::get('Config');
    if ($Config['Rice']['show_url_index_php'] == false) {
        //return
    }
}