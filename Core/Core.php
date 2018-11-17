<?php
/**
 * Created by PhpStorm.
 * User: DMF
 * Date: 2017/12/20
 * Time: 13:08
 */

namespace Rice\Core;


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

        //设置别名
        self::setAlias();

        //路由转发
        Dispatcher::dispatch();

        //创建控制器对象
        self::exec();

        //路由检测和反射
        Route::check();
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
        //配置类
        self::set('Config', '\Rice\Core\Config');
        //模板变量
        self::set('Cache', '\Rice\Core\Cache');
        //信息变量
        self::set('Infos', '\Rice\Core\Infos');

        Core::get('Config');
    }
    //创建当前的类
    public static function exec()
    {
        $class = Dispatcher::getClassName();

        if (class_exists($class)) {
            self::$instances[md5($class)] = new $class();
        }
    }

    //取得对象实例
    public static function instance($class, $method = '')
    {
        $identify = md5($class.$method);
        if (!isset(self::$instances[$identify])) {
            if (class_exists($class)) {
                $o = new $class();
                if (!empty($method) && method_exists($o, $method)) {
                    self::$instances[$identify] = call_user_func(array(&$o,$method));
                } else {
                    self::$instances[$identify] = $o;
                }
            } else {
                //错误处理 do something
                die('无法取得实例化对象！');
            }
        }

        return self::$instances[$identify];
    }
}