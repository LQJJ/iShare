<?php

/**
 * Created by PhpStorm.
 * User: caozhi
 * Date: 2017/2/7
 * Time: 13:50
 */





class API
{


    public static function run(){
        self::checkSign();
        self::init();
        self::autoload();
        self::dispatch();
    }
    private static function init(){
        //定义路径常量
        define("DS", DIRECTORY_SEPARATOR); //  /
        define("ROOT", dirname(dirname(dirname(__FILE__))) . DS ); //  根目录
        define("API_PATH", getcwd() . DS ); // api
        define("FRAMEWORK_PATH", ROOT . "framework" .DS);
        define("PUBLIC_PATH", ROOT . "public" .DS);
        define("APP_PATH", ROOT . 'application' . DS);
        define("CONFIG_PATH", FRAMEWORK_PATH . "config" .DS);
        //获取参数p、c、a,index.php?p=admin&c=goods&a=add GoodsController中的addAction
        define('VERSION_PATH',API_PATH.self::getVersion().DS);
        define("CONTROLLER_PATH", VERSION_PATH . "controllers" .DS);
        define("MODEL_PATH", VERSION_PATH . "models" .DS);
        define("VIEW_PATH", VERSION_PATH . "views" .DS);
        define("CORE_PATH", FRAMEWORK_PATH . "core" .DS);
        define("DB_PATH", FRAMEWORK_PATH . "databases" .DS);
        define("LIB_PATH", FRAMEWORK_PATH . "libraries" .DS);
        define("HELPER_PATH", FRAMEWORK_PATH . "helpers" .DS);
        define("UPLOAD_PATH", PUBLIC_PATH . "uploads" .DS);
        define('VERSION',self::getVersion());
        define('CONTROLLER',self::getController());
        define('ACTION',self::getAction());

        //载入配置文件
        $GLOBALS['config'] = require_once CONFIG_PATH . "config.php";

        //载入核心类
        require_once DB_PATH . "MySQL.class.php";
        require_once CORE_PATH . "APIController.class.php";
        require_once CORE_PATH . "Model.class.php";
    }

    private static function getVersion(){
        $dirs = self::getCurDir(API_PATH);

        $version = isset($_REQUEST['v'])? $_REQUEST['v'] : max($dirs);
        if(!in_array($version,$dirs)){
            $data = array(
                'code'=>0,
                'msg'=>'请正确选择版本号',
                'data'=>array(),
                'ver'=>$version
            );
            die(json_encode($data));
        }
        return $version;
    }
    private static function getController()
    {
        $files = self::getCurFile(CONTROLLER_PATH);
        $controller = (isset($_REQUEST['c'])? ucfirst(strtolower($_REQUEST['c'])) : '').'Controller';
//        var_dump($controller);
        if(!in_array($controller.'.class.php',$files)){
            $data = array(
                'code'=>0,
                'msg'=>'请正确选择参数c',
                'data'=>array(),
                'ver'=>VERSION
            );
            die(json_encode($data));
        }

        return $controller;
    }
    private static function getAction()
    {
        $action = (isset($_REQUEST['a'])? strtolower($_REQUEST['a']) : '').'Action';
        return $action;
    }

    private static function dispatch(){

        //获取控制器名称
        $controllerName = CONTROLLER;
        //获取方法名
        $actionName = ACTION;
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
            include CONTROLLER_PATH . "{$className}.class.php";
        } elseif (substr($className, -5) == 'Model') {
            //载入数据库模型
            include MODEL_PATH . "{$className}.class.php";
        } else {
            //暂略
        }

    }


    private static function checkSign(){

        if(isset($_REQUEST['debug']) && $_REQUEST['debug']) return;
        date_default_timezone_set ("Asia/Chongqing");
//        date_default_timezone_set("Etc/GMT");
        $time=date("s")+date("i")*60;
        $p1  =  floor($time/10);
        $p2  = floor($time/10 - 1);
        $p3  = floor($time/10 - 2);

        $token = isset( $_REQUEST['sign'] )? $_REQUEST['sign']:'';
        $newToken = md5($_SERVER['HTTP_USER_AGENT'].'-'.$p1);
        $midToken = md5($_SERVER['HTTP_USER_AGENT'].'-'.$p2);
        $oldToken = md5($_SERVER['HTTP_USER_AGENT'].'-'.$p3);

//        echo  $token."<br/>";
//        echo $newToken."<br/>";
//        echo $oldToken."<br/>";
//        die(json_encode( array(
//            'ios'=>$token,
//            'old'=>$oldToken,
//            'mid'=>$midToken,
//            'new'=>$newToken,
//        )));
        if ( !(($token == $newToken) || ($token == $midToken) || ($token == $oldToken)) )
        {
            $data = array(
                'code'=> 0,
                'msg'=>'apiKey has expired',
                'data'=>array('date'=>date('Y-m-d H:i:s')),
            );
            die(json_encode($data));
        }
    }
    private static function getCurDir($path)
    {
        $handle = opendir($path);
        while (false !== ($file = readdir($handle))) {
            if ($file == '.' || $file == '..' ||  !strlen(preg_replace('/\D/s', '', $file)) ) continue;
            if (is_dir($path .'/'. $file)) $dirs[] = $file;
        }
        closedir($handle);
        return $dirs;
    }
    private static function getCurFile($path)
    {
        $handle = opendir($path);
        while (false !== ($file = readdir($handle))) {
            if (!is_dir($path .'/'. $file)) $files[] = $file;
        }
        closedir($handle);
        return $files;
    }
}