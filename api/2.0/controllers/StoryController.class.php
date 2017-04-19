<?php

/**
 * Created by PhpStorm.
 * User: caozhi
 * Date: 2017/3/12
 * Time: 11:55
 */
class StoryController extends APIController
{
    public function storyAction(){
        $bModel = new BookModel('book');

        $json = $bModel->getStory('粤语_楚留香(叶伟版)');
        $this->outPut(1,'请求成功',$json);
    }

    public function listAction(){
        $bModel = new BookModel('book');
        $json = $bModel->getList();
        $this->outPut(1,'请求成功',$json);

    }
}