<?php
/**
 * Created by PhpStorm.
 * User: DMF
 * Date: 2017/9/18
 * Time: 19:15
 */

namespace Rice\Core;

class Dispatcher
{

    private static $args = [];

    public static function getModule()
    {
        return self::$args['module'];
    }

    public static function getController()
    {
        return self::$args['controller'];
    }

    public static function getAction()
    {
        return self::$args['action'];
    }

    public static function getPathInfo()
    {
        return self::$args['path_info'];
    }

    public static function getControllerClassName()
    {
        return sprintf('App/%s/Controller/%s', self::$args['module'], self::$args['controller']);
    }

    public static function getTemplateFileName($filename)
    {

        return sprintf(
            '%s/App/%s/Tpl/%s/%s.php',
            ROOT_PATH,
            self::$args['module'],
            self::$args['controller'],
            $filename ?? self::getAction()
        );
    }

    public static function getCacheFileName($filename, $suffix, $page = 0)
    {
        return sprintf(
            '%s/Caches/%s/%s/%s_%s.%s',
            ROOT_PATH,
            self::$args['module'],
            self::$args['controller'],
            $filename ?? self::getAction(),
            $page,
            $suffix
        );
    }

    //url映射到控制器
    public static function dispatch()
    {
        @$path_info = $_SERVER['PATH_INFO'];

        do {
            if (!empty($_SERVER['QUERY_STRING'])) {
                if (!empty($_GET['m'])&&!empty($_GET['c']&&!empty($_GET['a']))) {
                    $varModule = $_GET['m'];
                    $varController = $_GET['c'];
                    $varAction = $_GET['a'];
                    break;
                }
            }

            //路径判断是否为空
            if (empty($path_info)) {
                $varModule = 'test';
                $varController = 'index';
                $varAction = 'index';
                $urlCase = '';

            } else {
                //去除首尾的'/'
                $path_info = trim($path_info, '/');
                //以/为分隔符，分配给数组
                $url = explode('/', $path_info);

                $varModule = $url[0];
                $varController = $url[1];
                $varAction = $url[2];
                //$urlCase = ;
            }

        } while (false);
        //保存变量
        self::$args['module'] = ucfirst($varModule);
        self::$args['controller'] = ucfirst($varController);
        self::$args['action'] = ucfirst($varAction);
        self::$args['path_info'] = $_SERVER['PATH_INFO'];
    }
}