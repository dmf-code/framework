<?php
/**
 * Created by PhpStorm.
 * User: DMF
 * Date: 2018/9/3
 * Time: 13:04
 */

namespace Rice\Core;


class Config
{

    private $_var = array();
    public function __construct()
    {
        $path = ROOT_PATH.'/Conf';
        $handle = opendir($path);

        while (false !== ($item = readdir($handle))) {
            if ($item != "." && $item != "..") {
                $fileName = explode('.',$item)[0];
                $this->_var[$fileName] = require_once("$path/$item");
            }
        }
    }

    public function set($key, $item) {

        if (isset($this->_var[$key])) {
            throw new \Exception("配置设置不能存在重复键");
        }
        return $this->_var[$key] = $item;
    }

    public function get($key) {
        if (isset($this->_var[$key])) {
            return $this->_var[$key];
        }
        return null;
    }
}