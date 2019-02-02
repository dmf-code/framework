<?php
/**
 * Created by PhpStorm.
 * User: DMF
 * Date: 2017/12/20
 * Time: 13:08
 */

namespace Rice\Core;


use Rice\Core\Log\Logger;
use Rice\Core\System\IssueHandlers;

class Core
{
    //类映射
    private static $map = [];
    //实例化对象
    private static $instances = [];

    public function __construct()
    {

    }

    public static function run()
    {
        //绑定容器
        self::setAlias();
        //设置异常处理
        self::setHandlers();
        //路由转发
        Dispatcher::dispatch();

        //创建控制器对象
        self::setInstance();

        Controller::invoke();
    }

    /**
     * 设置错误和异常处理
     */
    public function setHandlers()
    {
        $system = self::get('System');
        $issueHandlers = new IssueHandlers();
        $system->setErrorHandler([$issueHandlers, 'error']);
        $system->setExceptionHandler([$issueHandlers, 'exception']);
        $system->registerShutdownFunction([$issueHandlers, 'shutdown']);
    }
    /*
     * 设置全局类
     */
    public static function set($key, $item)
    {

        if (!isset(self::$map[$key])) {
            self::$map[$key] = $item;
        }
        return self::$map[$key];
    }

    /*
     * 提取全局类
     */
    public static function get($key)
    {
        if (isset(self::$map[$key])) {
            if (is_object(self::$map[$key])) {
                return self::$map[$key];
            } elseif (is_string(self::$map[$key])) {
                $instance = new self::$map[$key];
                self::$map[$key] = $instance;
                return self::$map[$key];
            }
        }
        return null;
    }

    public static function setAlias()
    {
        //异常捕获
        self::set('System', System\System::class);
        //配置类
        self::set('Config', Config::class);
        //模板变量
        self::set('Cache', Cache::class);
        //信息变量
        self::set('Infos', Infos::class);
        //日志
        self::set('Logger', Logger::class);
    }
    //创建当前的类
    public static function setInstance()
    {
        $class = Dispatcher::getControllerClassName();

        if (class_exists($class)) {
            self::$instances[md5($class)] = new $class();
        }
    }

    //取得对象实例
    public static function instance($class, $method = '')
    {
        $identify = md5($class.$method);
        if (!isset(self::$instances[$identify])) {
            var_dump($class);
            var_dump(class_exists($class));
            if (class_exists($class)) {
                $o = new $class();
                if (!empty($method) && method_exists($o, $method)) {
                    self::$instances[$identify] = call_user_func(array(&$o,$method));
                } else {
                    self::$instances[$identify] = $o;
                }
            } else {
                var_dump(class_exists(Config::class));
                var_dump(Config::class);
                var_dump($class);
                //错误处理 do something
                die('无法取得实例化对象！');
            }
        }

        return self::$instances[$identify];
    }
}