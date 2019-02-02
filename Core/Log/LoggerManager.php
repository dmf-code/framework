<?php
/**
 * Created by PhpStorm.
 * User: DMF
 * Date: 2018/12/15
 * Time: 23:04
 */

namespace Rice\Core\Log;


use Psr\Log\InvalidArgumentException;
use Psr\Log\LoggerInterface;
use Monolog\Logger as Monolog;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\SyslogHandler;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\ErrorLogHandler;
use Monolog\Handler\HandlerInterface;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Handler\SlackWebhookHandler;
use Rice\Core\Config;
use Rice\Core\Core;

class LoggerManager implements LoggerInterface
{
    protected $config;
    /**
     * The Log levels.
     *
     * @var array
     */
    protected $levels = [
        'debug' => Monolog::DEBUG,
        'info' => Monolog::INFO,
        'notice' => Monolog::NOTICE,
        'warning' => Monolog::WARNING,
        'error' => Monolog::ERROR,
        'critical' => Monolog::CRITICAL,
        'alert' => Monolog::ALERT,
        'emergency' => Monolog::EMERGENCY,
    ];

    public function __construct()
    {
        $this->config = Core::get('Config');
    }

    public function createHandler($config)
    {
        switch ($config['driver'])
        {
            case 'single':
                return new StreamHandler(
                    $config['path'], $this->level($config),
                    $config['bubble'] ?? true, $config['permission'] ?? null, $config['locking'] ?? false
                );
            case 'daily':
                return new RotatingFileHandler(
                    $config['path'], $config['days'] ?? 7, $this->level($config),
                    $config['bubble'] ?? true, $config['permission'] ?? null, $config['locking'] ?? false
                );
        }
    }

    public function level(array $config)
    {
        $level = $config['level'] ?? 'debug';

        if (isset($this->levels[$level])) {
            return $this->levels[$level];
        }

        throw new InvalidArgumentException('Invalid log level.');
    }

    public function driver()
    {
        $logging = $this->config->get('Logging');
        $default = $logging['default'];
        $config = $logging['channels'][$default];
        $stack[] = $this->createHandler($config);
        return new Monolog('default'.$default, $stack);
    }

    public function log($level, $message, array $context = array())
    {
        // TODO: Implement log() method.
        $this->driver()->log($level, $message, $context);
    }

    public function alert($message, array $context = array())
    {
        // TODO: Implement alert() method.
        $this->driver()->alert($message, $context);
    }

    public function critical($message, array $context = array())
    {
        // TODO: Implement critical() method.
        $this->driver()->critical($message, $context);
    }

    public function debug($message, array $context = array())
    {
        // TODO: Implement debug() method.
        $this->driver()->debug($message, $context);
    }

    public function emergency($message, array $context = array())
    {
        // TODO: Implement emergency() method.
        $this->driver()->emergency($message, $context);
    }

    public function error($message, array $context = array())
    {
        // TODO: Implement error() method.
        $this->driver()->error($message, $context);
    }

    public function info($message, array $context = array())
    {
        // TODO: Implement info() method.
        $this->driver()->info($message, $context);
    }

    public function notice($message, array $context = array())
    {
        // TODO: Implement notice() method.
        $this->driver()->notice($message, $context);
    }

    public function warning($message, array $context = array())
    {
        // TODO: Implement warning() method.
        $this->driver()->warning($message, $context);
    }
}