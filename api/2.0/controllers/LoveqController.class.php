<?php

/**
 * Created by PhpStorm.
 * User: caozhi
 * Date: 2017/4/9
 * Time: 16:19
 */
class LoveQController extends APIController
{
    public function programAction(){
        $model = new LoveQModel();
        $json = $model->getProgram();
        $this->outPut('',1,$json);
    }
}