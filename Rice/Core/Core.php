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
    private static $_map = array();
    //实例化对象
    private static $_instance = array();

    public function __construct()
    {
    }

    static public function run(){
        //自动加载注册
        Loader::start();

        //设置全局变量
        self::seting();

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
    static public function set($key, $item) {

        if (!isset(self::$_map[$key])) {
            self::$_map[$key] = $item;
        }
        return self::$_map[$key];
    }

    /*
     * 提取全局类
     */
    static public function get($key) {

        if (isset(self::$_map[$key])) {

            if (is_object(self::$_map[$key])) {
                return self::$_map[$key];
            } else if (is_string(self::$_map[$key])) {
                $reflection = new \ReflectionClass(self::$_map[$key]);
                $ins = $reflection->newInstanceArgs();
                self::$_map[$key] = $ins;
                return self::$_map[$key];
            }
        }
        return null;
    }

    static public function seting(){
        //配置类
        self::set('Config','\Rice\Core\Config');
        //模板变量
        self::set('Cache','\Rice\Core\Cache');
        //信息变量
        self::set('Infos','\Rice\Core\Infos');

        Core::get('Config');
    }
    //创建当前的类
    static public function exec(){
        $class = MODULE_NAME.'\\Controller\\'.CONTROLLER_NAME;
//        echo '<br/>';
//        echo $class;
//        echo '<br/>';
//        echo '准备创建控制器了';
//        echo '<br/>';
//        检测是否存在当前的类
//        var_dump(class_exists($class));
//        echo '<br/>';
        if(class_exists($class)) {

            self::$_instance[md5($class)] = new $class();
            //echo '创建控制器了';
        }
    }

    //取得对象实例
    static public function instance($class,$method=''){

        $identify = md5($class.$method);
        if(!isset(self::$_instance[$identify])){
            //echo '<br/>'.$class.'<br/>';
            //echo '<br/>'.'C:\Users\DMF\Desktop\Rice/'.$class.'<br/>';
            //echo '<br/>'.class_exists('C:\Users\DMF\Desktop\Rice/'.$class).'<br/>';
            if(class_exists($class)){
                $o = new $class();
                if(!empty($method) && method_exists($o,$method)){
                    self::$_instance[$identify] = call_user_func(array(&$o,$method));
                }else{
                    self::$_instance[$identify] = $o;
                }
            }else{
                //错误处理 do something
                die('无法取得实例化对象！');
            }
        }

        return self::$_instance[$identify];
    }
}