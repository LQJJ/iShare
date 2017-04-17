<?php

/**
 * Created by PhpStorm.
 * User: czljcb
 * Date: 2017/4/8
 * Time: 下午7:54
 */
class TvController extends APIController
{
    public function channelAction(){
        $model = new TvModel();
        $json = $model->getChannel();
        $this->outPut('',1,$json);
    }

    public function listAction(){
        $model = new TvModel();
        $this->library('NetWorking');
        $this->library('ParserDom');

        $json = $model->getTvList($_GET['id']);
        $this->outPut('',1,$json);
    }

    public function videoAction(){
        $this->library('NetWorking');
        $model = new TvModel();
        $json = $model->getNewVideo();
        $this->outPut('',1,$json);
    }
    public function movieAction(){
        $this->library('NetWorking');
        $model = new TvModel();
        $json = $model->getVideo();
    }
    public function likeAction(){
        $model = new TvModel();
        $model->likeTV();
    }

    public function deleteAction(){
        $model = new TvModel();
        $model->deleteTV();
    }

}