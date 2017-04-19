<?php

/**
 * Created by PhpStorm.
 * User: caozhi
 * Date: 2017/2/6
 * Time: 10:31
 */
class IndexController extends APIController
{


    public function codeAction(){
//        echo 123;
        $this->library('Captcha');
        $c = new Captcha();
        $c->generateCode();
    }
}