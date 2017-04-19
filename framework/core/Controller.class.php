<?php

/**
 * Created by PhpStorm.
 * User: caozhi
 * Date: 2017/2/6
 * Time: 10:27
 */
class Controller
{

    public function __construct()
    {
        header("content-type:text/html;charset=utf-8;");
    }

    //跳转方法
    public function jump($url,$message,$wait = 3){
        if ($wait == 0) {
            header("Location:$url");
        } else {
            require_once CUR_VIEW_PATH . "message.html";
        }
        exit(); //一定要退出 die一样
    }

    //引入工具类模型方法
    public function library($lib){
        require_once LIB_PATH . "{$lib}.class.php";
    }

    //引入辅助函数方法
    public function helper($helper){
        require_once HELPER_PATH . "{$helper}.php";
    }

    public function __call($name, $arguments)
    {
        // TODO: Implement __call() method.
        echo CONTROLLER . "Controller"."不存在$name";
    }
}