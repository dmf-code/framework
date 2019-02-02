<?php
/**
 * Created by PhpStorm.
 * User: dengminfeng
 * Date: 2018/12/19 0019
 * Time: 14:47
 */

namespace Rice\Core\System;


class IssueHandlers
{
    /**
     *
     * @param $errno
     * @param $errstr
     * @param $errfile
     * @param $errline
     * @return bool
     */
    function error($errno, $errstr, $errfile, $errline)
    {
        if (!(error_reporting() & $errno)) {
            // This error code is not included in error_reporting, so let it fall
            // through to the standard PHP error handler
            return false;
        }
        $str = date('Y-m-d H:i:s', time()).' ';
        switch ($errno) {
            case E_USER_ERROR:
                $str .= "<b>My ERROR</b> [$errno] $errstr<br />\n"
                    ."  Fatal error on line $errline in file $errfile"
                    .", PHP " . PHP_VERSION . " (" . PHP_OS . ")<br />\n"
                    ."Aborting...<br />\n";
                # var_dump($str);
                #file_put_contents(dirname(dirname(__FILE__)).'/error_log.txt', $str, FILE_APPEND | LOCK_EX);
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
        var_dump($str);
        #file_put_contents(dirname(dirname(__FILE__)).'/error_log.txt', $str, FILE_APPEND | LOCK_EX);
        /* Don't execute PHP internal error handler */
        return true;
    }

    /**
     * @param $exception
     */
    function exception($exception)
    {
        echo '<div class="alert alert-danger">';
        echo '<b>Fatal error</b>:  Uncaught exception \'' . get_class($exception) . '\' with message ';
        echo $exception->getMessage() . '<br>';
        echo 'Stack trace:<pre>' . $exception->getTraceAsString() . '</pre>';
        echo 'thrown in <b>' . $exception->getFile() . '</b> on line <b>' . $exception->getLine() . '</b><br>';
        echo '</div>';
    }

    function shutdown()
    {
        $error = error_get_last();
        if ($error && ($error["type"] === $this->isFatal($error["type"]))) {
            $errno = $error["type"];
            $errfile = $error["file"];
            $errline = $error["line"];
            $errstr = $error["message"];
            $this->error($errno, $errstr, $errfile, $errline);
        }
    }

    protected function isFatal($type)
    {
        return in_array($type, [E_ERROR | E_CORE_ERROR | E_COMPILE_ERROR | E_PARSE]);
    }
}