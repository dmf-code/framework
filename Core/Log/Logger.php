<?php
/**
 * Created by PhpStorm.
 * User: DMF
 * Date: 2018/12/15
 * Time: 1:03
 */

namespace Rice\Core\Log;


class Logger
{
    protected $logger;
    public function __construct()
    {
        $this->logger = new LoggerManager();
    }

    public function writeLog($level, $message, $context)
    {
        if (! is_array($context)) {
            $context = [$context];
        }
        $this->logger->{$level}($message, $context);
    }
}