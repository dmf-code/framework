<?php
/**
 * Created by PhpStorm.
 * User: DMF
 * Date: 2017/9/18
 * Time: 19:15
 */

namespace Rice\Core;

class Dispatcher{
    //url映射到控制器
    static public function dispatch(){
        @$path_info = $_SERVER['PATH_INFO'];

        do{
            if(!empty($_SERVER['QUERY_STRING'])){
                if(!empty($_GET['m'])&&!empty($_GET['c']&&!empty($_GET['a']))){
                    $varModule = $_GET['m'];
                    $varController = $_GET['c'];
                    $varAction = $_GET['a'];
                    break;
                }
            }

            //路径判断是否为空
            if(empty($path_info)){
                $varModule = 'test';
                $varController = 'index';
                $varAction = 'index';
                $urlCase = '';

            }else{

                //去除首尾的'/'
                $path_info = trim($path_info,'/');
                //以/为分隔符，分配给数组
                $url = explode('/',$path_info);

                $varModule = $url[0];
                $varController = $url[1];
                $varAction = $url[2];
                //$urlCase = ;
            }

        }while(false);
        //定义全局变量
        define('MODULE_NAME',ucfirst($varModule));
        define('CONTROLLER_NAME',ucfirst($varController));
        define('ACTION_NAME',ucfirst($varAction));
        define('PATH_INFO',$_SERVER['PATH_INFO']);
        //var_dump(MODULE_NAME, CONTROLLER_NAME, ACTION_NAME);
    }
}