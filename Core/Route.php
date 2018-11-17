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
    public static function check(){
        self::invoke(ACTION_NAME,MODULE_NAME.'\\Controller\\'.CONTROLLER_NAME);
        //echo MODULE_NAME.'<br/>'.CONTROLLER_NAME.'<br/>'.ACTION_NAME.'<br/>';
        #echo '进入check函数<br/>';
    }

    //反射函数
    public static function invoke($name,$className){
        //echo $name,' ',$className;
        //创建反射方法对象
        $reflect = new \ReflectionMethod(Core::instance($className),$name);
        //获取当前方法的参数
        $params = $reflect->getParameters();
        $args = array();
        //获取要调用方法的实例对象
        $obj = Core::instance($className);
        //遍历参数
        foreach ($params as $param) {
            $args[] = $param->getDefaultValue();
        }

        if(empty($args)){
            return $reflect->invoke($obj);
        }
        else{
            return $reflect->invokeArgs($obj,$args);
        }
    }
}