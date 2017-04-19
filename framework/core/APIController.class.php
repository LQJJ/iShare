<?php

/**
 * Created by PhpStorm
 * Date: 2017/2/7
 * Time: 14:37
 */
class APIController
{
    public function __construct()
    {
        header("content-type:text/html;charset=utf-8;");
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
        $this->outPut('请正确选择参数a',0);
    }

    public function outPut($msg='',$code=0,$data=array()){
        $txt = array(
            'ver'=>VERSION,
            'code'=>$code,
            'msg'=>$msg,
            'data'=>$data,
        );
        die(json_encode($txt));
    }
}