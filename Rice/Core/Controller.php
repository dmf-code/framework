<?php
/**
 * Created by PhpStorm.
 * User: DMF
 * Date: 2017/9/18
 * Time: 12:21
 */

namespace Rice\Core;


class Controller{

    public $cache;
    //控制器参数
    protected $config;

    //初始化控制器
    public function __construct()
    {
        $this->cache = Core::get('Cache');
    }

    //封装一个模板显示函数
    public function display($name=null,$page=null){
        if(empty($name)){
            $name = ACTION_NAME;
            $this->cache->display($name,$page);
        }else{
            $this->cache->display($name,$page);
        }
    }

    /*
     * 分配变量值进模板
     */
    public function assign($key,$val){

        $this->cache->set($key, $val);
        $this->cache->{$key} = $val;

    }

    /*
     * 返回数据到模板
     */
    public function getVal($key){
        return $this->cache->get($key);
    }

    /*
     * 方法调度跳转
     *$message  成功的显示信息
     * $error   失败的显示信息
     * $jumpUrl 跳转的url
     * $waitSecond  等待多少秒跳转
     */
    function dispatchJump($message,$error,$jumpUrl,$waitSecond=3){

        require_once ROOT_PATH . '/Tpl/dispatch_jump.tpl';
    }

    /*
     * 获取提交数据
     */
    function getRequest($name){
        $method = $_SERVER['REQUEST_METHOD'];
        $_PUT = null;
        switch($method){
            case 'GET':
                $data = $_GET[$name];
                break;

            case 'POST':
                //字符串第一个字母不是数字与int比较会为0
                $data = $_POST[$name];
                //var_dump($_POST[$name]);
                break;
            case 'PUT':
            default:
                parse_str(file_get_contents('php://input'), $_PUT);
                $data = $_PUT[$name];
                break;
        }
        return $data;
    }

}