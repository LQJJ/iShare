<?php

/**
 * Created by PhpStorm.
 * User: caozhi
 * Date: 2017/2/6
 * Time: 10:09
 */
class Framework
{
    public static function run(){
        self::init();
        self::autoload();
        self::dispatch();

    }

    private static function init(){
        //定义路径常量
        define("DS", DIRECTORY_SEPARATOR); //  /
        define("ROOT", getcwd() . DS ); //根目录
        define("APP_PATH", ROOT . 'application' . DS);
        define("FRAMEWORK_PATH", ROOT . "framework" .DS);
        define("PUBLIC_PATH", ROOT . "public" .DS);
//        define("CONFIG_PATH", APP_PATH . "config" .DS);
        define("CONFIG_PATH", FRAMEWORK_PATH . "config" .DS);
        define("CONTROLLER_PATH", APP_PATH . "controllers" .DS);
        define("MODEL_PATH", APP_PATH . "models" .DS);
        define("VIEW_PATH", APP_PATH . "views" .DS);
        define("CORE_PATH", FRAMEWORK_PATH . "core" .DS);
        define("DB_PATH", FRAMEWORK_PATH . "databases" .DS);
        define("LIB_PATH", FRAMEWORK_PATH . "libraries" .DS);
        define("HELPER_PATH", FRAMEWORK_PATH . "helpers" .DS);
        define("UPLOAD_PATH", PUBLIC_PATH . "uploads" .DS);
        //获取参数p、c、a,index.php?p=admin&c=goods&a=add GoodsController中的addAction
        define('PLATFORM',isset($_GET['p']) ? strtolower($_GET['p']) : "admin");
        define('CONTROLLER',isset($_GET['c']) ?  ucfirst(strtolower($_GET['c'])) : "Index");
        define('ACTION',isset($_GET['a']) ?  strtolower($_GET['a']) : "index");
        //设置当前控制器和视图目录 CUR-- current
        define("CUR_CONTROLLER_PATH", CONTROLLER_PATH . PLATFORM . DS);
        define("CUR_VIEW_PATH", VIEW_PATH . PLATFORM . DS);

        //载入配置文件
        $GLOBALS['config'] = require_once CONFIG_PATH . "config.php";
        //载入核心类
        require_once CORE_PATH . "Controller.class.php";
        require_once CORE_PATH . "Model.class.php";
        require_once DB_PATH . "MySQL.class.php";
    }

    private static function dispatch(){
        //获取控制器名称
        $controllerName = CONTROLLER . "Controller";
        //获取方法名
        $actionName = ACTION . "Action";
        //实例化控制器对象
        $controller = new $controllerName();
        //调用方法
        $controller->$actionName();
    }

    private static function autoload(){
        // $arr = array(__CLASS__,'load');
        spl_autoload_register('self::load');
    }

    private static function load($className){
            if (substr($className, -10) == 'Controller') {
                //载入控制器
                include CUR_CONTROLLER_PATH . "{$className}.class.php";
            } elseif (substr($className, -5) == 'Model') {
                //载入数据库模型
                include MODEL_PATH . "{$className}.class.php";
            } else {
                //暂略
            }

    }

}