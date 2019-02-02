<?php
/**
 * Created by PhpStorm.
 * User: dengminfeng
 * Date: 2018/12/19 0019
 * Time: 11:53
 */

namespace Rice\Core\System;


class System
{
    /**
     * 设置用户自定义的错误处理函数
     * http://php.net/manual/zh/function.set-error-handler.php
     * @param callable $handler
     * @param string $types
     * @return mixed
     */
    public function setErrorHandler(callable $handler, $types = 'use-php-defaults')
    {
        if ($types == 'use-php-defaults') {
            $types = E_ALL | E_STRICT;
        }
        return set_error_handler($handler, $types);
    }

    /**
     * 设置用户自定义的异常处理函数
     * http://php.net/manual/zh/function.set-exception-handler.php
     * @param callable $handler
     * @return callable|null
     */
    public function setExceptionHandler(callable $handler)
    {
        return set_exception_handler($handler);
    }

    /**
     * 恢复之前定义过的异常处理函数
     * http://php.net/manual/zh/function.restore-exception-handler.php
     *
     */
    public function restoreExceptionHandler()
    {
        restore_exception_handler();
    }

    /**
     * 还原之前的错误处理函数
     * http://php.net/manual/zh/function.restore-error-handler.php
     */
    public function restoreErrorHandler()
    {
        restore_error_handler();
    }

    /**
     * 注册一个会在php中止时执行的函数
     * http://php.net/manual/zh/function.register-shutdown-function.php
     * @param callable $function
     */
    public function registerShutdownFunction(callable $function)
    {
        register_shutdown_function($function);
    }

    /**
     * 设置应该报告何种 PHP 错误
     * http://php.net/manual/zh/function.error-reporting.php
     * @return int
     */
    public function getErrorReportingLevel()
    {
        return error_reporting();
    }

    /**
     * 获取最后发生的错误
     * http://php.net/manual/zh/function.error-get-last.php
     * @return array
     */
    public function getLastError()
    {
        return error_get_last();
    }

    /**
     * 获取/设置响应的 HTTP 状态码
     * http://php.net/manual/zh/function.http-response-code.php
     * @param $httpCode
     * @return int
     */
    public function setHttpResponseCode($httpCode)
    {
        return http_response_code($httpCode);
    }

    /**
     * @param $exitStatus
     */
    public function stopExecution($exitStatus)
    {
        exit($exitStatus);
    }
}