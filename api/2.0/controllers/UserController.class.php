<?php

/**
 * Created by PhpStorm.
 * User: caozhi
 * Date: 2017/4/9
 * Time: 17:11
 */
class UserController extends APIController
{
    public function loginAction(){
        $model = new UserModel();
        $model->login();
    }
    public function registerAction(){
        $model = new UserModel();
        $model->register();
    }
    public function uploadAction(){
        $model = new UserModel();
        $model->upload();
    }
    public function updateAction(){
        $model = new UserModel();
        $model->updateUserinfo();
    }
    public function configAction(){
        $model = new UserModel();
        $model->config();
    }
    public function adAction(){
        $model = new UserModel();
        $model->ad();
    }
}