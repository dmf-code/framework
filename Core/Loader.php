<?php

namespace Rice\Core;

class Loader{

    public static function start(){

        spl_autoload_register('\Rice\Core\Loader::autoload');
    }

    //类自动加载
    public static function autoload($class){
        #echo $class;
        //注意要置换这里面的反斜杠，因为linux上面只接受斜杠
        //echo '<br/>'.$class.'<br/>';
        $fileUrl = ROOT_PATH.'/'.str_replace('\\','/',$class).'.php';
        //echo '<br/>'.$fileUrl.'<br/>';
        if(file_exists($fileUrl)){
            require_once $fileUrl;
        }else{
            throw new \Exception('不存在'.$fileUrl.'类文件。');
        }

    }


}