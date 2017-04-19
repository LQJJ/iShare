<?php

/**
 * Created by PhpStorm.
 * User: caozhi
 * Date: 2017/2/6
 * Time: 11:14
 */
class UserModel extends Model
{
    public function getUsers(){
       return $this->pageRows(0,10,1);
    }
}