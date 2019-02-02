<?php
/**
 * Created by PhpStorm.
 * User: DMF
 * Date: 2017/9/23
 * Time: 15:55
 */

namespace Rice\Core;

class Db
{
    static private $instances = array();     //数据库连接实例
    static private $instance = null;       //当前数据库实例
    //获取数据库实例
    public static function getInstance($config = array())
    {
        $md5 = md5(serialize($config));
        //判断是否存在数据库实例对象
        if (!isset(self::$instances[$md5])) {
            $class = '\\Rice\\Core\\Database\\Driver';
            self::$instances[$md5] = new $class();
        }

        return self::$instances[$md5];
    }



}