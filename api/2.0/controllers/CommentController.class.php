<?php

/**
 * Created by PhpStorm.
 * User: caozhi
 * Date: 2017/4/9
 * Time: 16:32
 */
class CommentController extends APIController
{
    public function sendAction(){
        $model = new CommentModel();
        $model->sendComment();
    }
    public function getAction(){
        $model = new CommentModel();
        $model->getComment();
    }
    public function likeAction(){
        $model = new CommentModel();
        $model->likeComment();
    }
}