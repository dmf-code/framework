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
    static private $instance = array();     //数据库连接实例
    static private $_instance = null;       //当前数据库实例
    //获取数据库实例
    public static function getInstance($config=array()){

        $md5 = md5(serialize($config));
        //判断是否存在数据库实例对象
        if(!isset(self::$instance[$md5])){
            $class = '\\Rice\\Core\\Database\\Driver';
            self::$instance[$md5] = new $class();
        }

        return self::$instance[$md5];
    }



}