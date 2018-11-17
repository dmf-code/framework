<?php
/**
 * Created by PhpStorm.
 * User: DMF
 * Date: 2017/9/25
 * Time: 14:06
 */

namespace Rice\Core;


class Route
{
    //检查路由信息
    public static function check()
    {
        self::invoke(Dispatcher::getAction(), Dispatcher::getControllerClassName());
    }

    //反射函数
    public static function invoke($name, $className)
    {
        //创建反射方法对象
        $reflect = new \ReflectionMethod(Core::instance($className), $name);
        //获取当前方法的参数
        $params = $reflect->getParameters();
        $args = array();
        //获取要调用方法的实例对象
        $obj = Core::instance($className);
        //遍历参数
        foreach ($params as $param) {
            $args[] = $param->getDefaultValue();
        }

        if (empty($args)) {
            return $reflect->invoke($obj);
        } else {
            return $reflect->invokeArgs($obj, $args);
        }
    }
}