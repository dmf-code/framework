<?php
/**
 * Created by PhpStorm.
 * User: DMF
 * Date: 2017/12/18
 * Time: 16:23
 */

namespace Rice\Core;


class Cache
{
    private $vars=array();
    private $module;
    private $controller;
    private $action;
    
    public function __construct()
    {
        $this->module = Dispatcher::getModule();
        $this->controller = Dispatcher::getController();
        $this->action = Dispatcher::getAction();
    }

    /*
     * 显示模板
     */
    public function display($filename,$page=null){

        $tpl_file = Dispatcher::getTemplateFileName($filename);

        $url_html_suffix = Core::get('Config')->get('Rice')['url_html_suffix'];
        $url_html_suffix = explode('|', $url_html_suffix)[0];

        if (!$page) {
            $cache_file = Dispatcher::getCacheFileName($filename, $url_html_suffix);
        } else {
            $cache_file = Dispatcher::getCacheFileName($filename, $url_html_suffix, $page);
        }

        //判断是否存在缓存目录
        if (!file_exists(dirname($cache_file))) {
            mkdir(ROOT_PATH.'/Caches/'.$this->module.'/');
            chmod(ROOT_PATH.'/Caches/'.$this->module.'/', 0777);
            mkdir(ROOT_PATH.'/Caches/'.$this->module.'/'.$this->controller.'/');
            chmod(ROOT_PATH.'/Caches/'.$this->module.'/'.$this->controller.'/', 0777);
        }
//        判断文件是否被修改，未修改不用重新编译
        if (!$this->cache($tpl_file, $cache_file)) {
            $this->compile($tpl_file, $cache_file);
        }
        /*规定网站图标*/
//        echo '<link rel="shortcut icon" href="/fruitShop/favicon.ico">';

        require_once($cache_file);
    }

    //判断模板是否缓存
    private function cache($tpl_file, $cache_file)
    {
        //如果debug模式，那么每次编译文件,模板文件有更改则重新编译
        if (DEBUG && file_exists($cache_file)) {
            unlink($cache_file);
        }
        if (DEBUG || !file_exists($cache_file) || filemtime($tpl_file) > filemtime($cache_file)) {
            return false;
        }

        return true;
    }
    /*
     * 编译静态文件
     */
    public function compile($tpl_file, $cache_file)
    {
        ob_start(); //开启缓存区
        require_once($tpl_file);
        file_put_contents($cache_file, ob_get_contents());
        ob_clean();//清空缓冲区
    }

    /*
     * 设置数据
     */
    public function set($key, $val)
    {
        $this->vars[$key] = $val;
    }

    /*
    * 返回数据到模板
    */
    public function get($key)
    {
        return $this->vars[$key];
    }

}
