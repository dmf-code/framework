<?php
/**
 * Created by PhpStorm.
 * User: DMF
 * Date: 2018/9/2
 * Time: 18:12
 */

require './Func/Common.php';
require './Rice/Core/Loader.php';
require './Rice/Core/Core.php';

//执行路由转发，对象创建
\Rice\Core\Core::run();