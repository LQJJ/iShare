<?php

/**
 * Created by PhpStorm.
 * User: caozhi
 * Date: 2017/2/6
 * Time: 10:31
 */
class IndexController extends Controller
{
    public function indexAction(){


        require_once CUR_VIEW_PATH."index.html";
    }

    public function topAction(){

        require_once CUR_VIEW_PATH."top.html";
    }

    public function menuAction(){

        require_once CUR_VIEW_PATH."menu.html";
    }
    public function dragAction(){

        require_once CUR_VIEW_PATH."drag.html";
    }

    public function mainAction(){
        require_once CUR_VIEW_PATH."main.html";
    }

    public function goAction(){
        $this->jump('?a=index','eee');
    }

    public function codeAction(){
        $this->library('Captcha');
        $c = new Captcha();
        $c->generateCode();
    }
}