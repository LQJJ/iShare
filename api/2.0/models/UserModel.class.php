<?php

/**
 * Created by PhpStorm.
 * User: caozhi
 * Date: 2017/4/9
 * Time: 17:11
 */
class UserModel extends Model
{
    public function __construct()
    {
        parent::__construct('user');
    }

    public function ad(){
        $data = array(
            array(
                'text' => 'iShare 欢迎大家使用锚李TV 收看电视节目!',
                'circle' => false, //1 循环
                'interval' => 1, // 开始时间
                'number' => 5 // 播放次数
            ),

            array(
                'text' => '更多更精彩的节目,请下载锚李TV,如果你遇到任何有App的问题,请联系我们！',
                'circle' => false,
                'interval' => 1,
                'number' => 2
            )
        );
        $this->outPut('',1,$data);

    }
    public function config(){
        $appVersionKey = '1.0.0';
        $data = array();

        $data[$appVersionKey] = false;// 0 审核 1上线
        $data['appVersion'] = $appVersionKey;
        $data['appInfo'] = array(
            'title' => 'hello',
            'msg' => '版本更新了',
            'url'=>'http://wwww.baidu.com'
        );

        $this->outPut('',1,$data);
    }

    public function login(){
        if(!isset($_POST['platform']))
        {
            $this->outPut('请选择登陆方式',0);
        }
        $platform = $_POST['platform'] ?  $_POST['platform'] : 0 ;

        if($platform == 0){

            if(isset($_POST['phone']) && isset($_POST['password'])){

                $phone = $_POST['phone'];
                $password = md5($_POST['password']) ;

//                $link = connectMySQL();
                $sql = "select uid,gender,birthday,region,des, password ,nickname,icon,token from user WHERE phone = '$phone'";

                $isExct = $this->db->getRows($sql);//fetchArrays($sql);

                if(count($isExct)==0){
                    $this->outPut('账号不存在',0);
                }
                else
                {
                    if($isExct[0]['password'] != $password){
                        $this->outPut('账号或者密码不正确',0);
                    }
                    else{
                        $json = array(
                            'token' => $isExct[0]['token'],
                            'icon' =>  $isExct[0]['icon'],
                            'nickname' =>  $isExct[0]['nickname'],
                            'gender' => $isExct[0]['gender'],
                            'birthday' =>  $isExct[0]['birthday'],
                            'region' =>  $isExct[0]['region'],
                            'des' =>  $isExct[0]['des'],
                            'uid' =>  $isExct[0]['uid']
                        );
                        $this->outPut('登陆成功',1,$json);
                    }
                }


            }else{
                $this->outPut('账号或者密码不能为空',0);
            }

        }
        else
        {
            if(!isset($_POST['platformType']))
            {
                $this->outPut('参数缺失',0);
            }
            $platformType = $_POST['platformType'];
            $uid = uniqid();
            $icon = '';if(isset($_POST['icon'])) $icon = $_POST['icon'];
            $nickname = '';if(isset($_POST['nickname'])) $nickname = $_POST['nickname'];
            $gender = 2;if(isset($_POST['gender'])) $gender = $_POST['gender'];
            $birthday = '1997-00-00';if(isset($_POST['birthday'])) $birthday = $_POST['birthday'];
            $region = '未知 未知';if(isset($_POST['region'])) $region = $_POST['region'];
            $des = '';if(isset($_POST['des'])) $des = $_POST['des'];
            $tid = '';if(isset($_POST['uid'])) $tid = $_POST['uid'];

            if(!$tid){
                $this->outPut('uid缺失',0);
            }
            $token = md5($tid + time());


            $sql = "";
            if($platformType > 0)
            {
                if($platformType == 6){
                    $seleSql = "select uid,gender,birthday,region,des ,nickname,icon,token from user WHERE qid = '$tid'";
                }elseif($platformType == 1){
                    $seleSql = "select uid, gender,birthday,region,des ,nickname,icon,token from user WHERE sid = '$tid'";
                }else{
                    $seleSql = "select uid, gender,birthday,region,des ,nickname,icon,token from user WHERE wid = '$tid'";
                }


                $isExct = $this->db->getRows($seleSql);

                if(count($isExct) != 0){

                    $json = array(
                        'token' => $isExct[0]['token'],
                        'icon' =>  $isExct[0]['icon'],
                        'nickname' =>  $isExct[0]['nickname'],
                        'gender' => $isExct[0]['gender'],
                        'birthday' =>  $isExct[0]['birthday'],
                        'region' =>  $isExct[0]['region'],
                        'des' =>  $isExct[0]['des'],
                        'uid' => $isExct[0]['uid'],
                    );
                    $this->outPut('登陆成功',1,$json);

                }
                else
                {
                    if($platformType == 6){
                        $sql = "INSERT INTO user(gender,birthday,region,des,nickname ,icon,token ,qid,platformType,uid)
                        VALUES ($gender,'$birthday','$region','$des','$nickname','$icon','$token','$tid',$platformType,'$uid')";
                    }elseif($platformType == 1){
                        $sql = "INSERT INTO user(gender,birthday,region,des,nickname ,icon,token ,sid,platformType,uid)
                        VALUES ($gender,'$birthday','$region','$des','$nickname','$icon','$token','$tid',$platformType,'$uid')";
                    }else{
                        $sql = "INSERT INTO user(gender,birthday,region,des,nickname ,icon,token ,wid,platformType,uid)
                        VALUES ($gender,'$birthday','$region','$des','$nickname','$icon','$token','$tid',$platformType,'$uid')";
                    }
                }

            }
            else {
                $this->outPut('不支持该平台',0);
            }
            $res = $this->db->queryBool($sql);
            if($res){
                $json = array(
                    'token' => $token,
                    'icon' => $icon,
                    'nickname' => $nickname,
                    'des' => $des,
                    'gender' => $gender,
                    'birthday' => $birthday,
                    'region' => $region,
                    'uid'=> $uid,
                );
                $this->outPut('登陆成功',1,$json);
            }
            else{
                $this->outPut('登陆失败',0);
            }
        }
    }

    public function register(){
        if(!isset($_POST['platform']))
        {
            $this->outPut('请选择注册方式',0);
        }
        $platform = $_POST['platform'] ?  $_POST['platform'] : 0 ;

        if($platform == 0){
            if(isset($_POST['phone']) && isset($_POST['password'])){

                $phone = $_POST['phone'];
                $password = md5($_POST['password']);
                $token = md5($phone + time());
                $uid = uniqid();

                $isExct = $this->db->getRows("select password from user WHERE phone = '$phone'");
                if(count($isExct) > 0){
                    $this->outPut('账号已存在',0);
                }

                $sql = "INSERT INTO user(uid,  phone, password, token ) VALUES ('$uid','$phone','$password','$token')";
                $res = $this->db->queryBool($sql);
                if($res == 1){
                    $json = array(
                        'token' => $token,
                        'icon' =>  '',
                        'nickname' =>  '',
                        'gender' => 2,
                        'birthday' =>  '1970-00-00',
                        'uid' => $uid,
                        'region' =>  '未知 未知',
                        'des' =>  '',
                    );

                    $this->outPut('注册成功',1,$json);

                }else{
                    $this->outPut('注册失败',0);
                }


            }else{
                $this->outPut('账号或者密码不能为空',0);
            }

        }
        else
        {
            $this->outPut('请选择直接登陆',0);
        }
    }

    public function upload(){

        if( empty($_FILES['file']) || $_FILES['file']['size'] > 0.5 *1024*1024 ){
            $msg = empty($_FILES['file']) ? "文件为空":"文件过大";
            $this->outPut($msg,0);
        }
        $error = $_FILES['file']['error'];
        if ($error == UPLOAD_ERR_OK && is_uploaded_file($_FILES["file"]["tmp_name"])) {
            $name = md5($_FILES["file"]["name"]);
            $tmp_name = $_FILES["file"]["tmp_name"];
            $path = "../public/uploads";
            if(!file_exists($path))
            {
                if (!mkdir($path, 0, true)) {
                    $this->outPut('Failed to create folders....',0);
                }
            }
            $toPath = $path."/"."$name";
            $toPath = iconv("utf-8", "gb2312", $toPath);

            move_uploaded_file($tmp_name, $toPath);
            $data = array();
            $data["url"] = "http://".$_SERVER["HTTP_HOST"]."/public/uploads/$name";
            $data["error"] = 0;
            $this->outPut('上传成功',1,$data);

        }
        else{
            $this->outPut('上传失败',0);
        }
    }

    public function updateUserinfo(){
        if(!isset($_POST['type'])){
            $this->outPut('参数非法',0);
        }
        $type = $_POST['type'];

        if(!isset($_POST['token'])){
            $this->outPut('访问非法',0);
        }
        $token = $_POST['token'];

        if($type == "icon")
        {
            $icon = ""; if(isset($_POST['icon'])) $icon = $_POST['icon'];

            $sql = "UPDATE `user` SET `icon`='$icon' WHERE `token`='$token'";
            $this->updateUser($sql);
        }
        elseif($type == "nickname")
        {
            $nickname = ""; if(isset($_POST['nickname'])) $nickname = $_POST['nickname'];

            $sql = "UPDATE `user` SET `nickname`='$nickname' WHERE `token`='$token'";
            $this->updateUser($sql);
        }
        elseif($type == "des")
        {
            $des = ""; if(isset($_POST['des'])) $des = $_POST['des'];
            $sql = "UPDATE `user` SET `des`='$des' WHERE `token`='$token'";
            updateUser($sql);
        }
        elseif($type == "region")
        {
            $region = ""; if(isset($_POST['region'])) $region = $_POST['region'];
            $sql = "UPDATE `user` SET `region`='$region' WHERE `token`='$token'";
            $this->updateUser($sql);
        }
        elseif($type == "birthday")
        {
            $birthday = ""; if(isset($_POST['birthday'])) $birthday = $_POST['birthday'];
            $sql = "UPDATE `user` SET `birthday`='$birthday' WHERE `token`='$token'";
            $this->updateUser($sql);
        }
        elseif($type == "gender")
        {
            $gender = ""; if(isset($_POST['gender'])) $gender = $_POST['gender'];
            $sql = "UPDATE `user` SET `gender`=$gender WHERE `token`='$token'";
            $this->updateUser($sql);
        }
        else
        {
            $this->outPut('更新字段不存在',0);

        }
    }
    private function updateUser($sql){
        $res = $this->db->queryBool($sql);

        if($res == 1){

            $this->outPut('更新成功',1);

        }else{

            $this->outPut('更新失败',0);
        }
    }
}