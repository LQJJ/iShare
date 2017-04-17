<?php

/**
 * Created by PhpStorm.
 * User: caozhi
 * Date: 2017/4/9
 * Time: 16:32
 */
class CommentModel extends Model
{
    public function __construct()
    {
        parent::__construct('comment');
    }

    private function getUserinfo($uid ){
        $sql = "SELECT `nickname`, `gender`, `birthday`, `region`, `des`, `phone`,icon ,uid FROM `user` WHERE uid = '$uid'";
//        $re_users = fetchArrays($sql);
        $re_users = $this->db->getRows($sql);
        if(count($re_users)>0) return $re_users[0];
        return array();
    }
    public function getComment(){
        $channel = isset($_POST['channel'])? $_POST['channel']:null;
        if(!$channel)  $this->outPut('当前频道不存在',0);//NSLog(array('code'=> 0,'msg'=>'当前频道不存在'));

//        $link = connectMySQL();
        $sql = "SELECT `id`, `channel`, `reviewer_uid`, `at_id`, `addate`, `content`,like_count FROM `comment` WHERE channel = '$channel' ORDER BY addate DESC;";
//        $res = fetchArrays($sql);
        $res = $this->db->getRows($sql);
        $json = array();
//    die($sql);
        foreach($res as $com){
            $re_uid = $com['reviewer_uid'];
            $com['reviewer_uid'] = $this->getUserinfo($re_uid);
            $text = $com['content'];
            $com['content'] = base64_decode($text);
//        var_dump($com);
            $json[] = $com;
        }
//    var_dump($json);
//        mysql_close($link);
//        NSLog($json);
        $this->outPut('',1,$json);
    }

    public function sendComment(){
        $token = isset( $_POST['token'])?$_POST['token']:null;
        $channel = isset($_POST['channel'])?$_POST['channel']:null;
        $reviewer_uid = isset($_POST['reviewer_uid'])?$_POST['reviewer_uid']:null;
        $at_id = isset($_POST['at_id'])?$_POST['at_id']:0;
        $content =base64_encode(isset($_POST['content'])?$_POST['content']:null);
        if(!$channel)  $this->outPut('当前频道不可以评论',0);//NSLog(array('code'=> 0,'msg'=>'当前频道不可以评论'));
        if($token && !$reviewer_uid) $this->outPut('当前账号不可以评论',0);;//NSLog(array('code'=> 0,'msg'=>'当前账号不可以评论'));
//        $link = connectMySQL();
//    if($at_id) $content=$content.' //@'.getCommentContentByid($at_id);
        $sql = "INSERT INTO comment(channel,  reviewer_uid, at_id, content ) VALUES ('$channel','$reviewer_uid',$at_id,'$content')";
//        $res = SQL_DML($sql,$link);
        $res = $this->db->queryBool($sql);
//die($sql);
//        mysql_close($link);
        if($res == 1){
//            NSLog(array('code'=> 1,'msg'=>'评论成功'));
            $this->outPut('评论成功');
        }
        else{
//            NSLog(array('code'=> 0,'msg'=>'评论失败'));
            $this->outPut('评论失败',0);
        }

    }

    public function likeComment(){
        $id = isset( $_POST['id'])?$_POST['id']:null;
        if(!$id) $this->outPut('当前评论不可以like',0);//NSLog(array('code'=> 0,'msg'=>'当前评论不可以like'));

//        $link = connectMySQL();
//        $likes = fetchArrays("select like_count from comment WHERE id = $id");
        $likes = $this->db->getRows("select like_count from comment WHERE id = $id");
//    NSLog($likes);

        if(!count($likes)) $this->outPut('当前评论不可以like',0);//NSLog(array('code'=> 0,'msg'=>'当前评论不可以like'));
        $count = $likes[0]['like_count'] + 1;
//        $sql = "UPDATE `comment` SET like_count=$count WHERE id =$id";
//        $res = SQL_DML($sql,$link);
        $res = $this->update(array('like_count'=>$count,'id'=>$id));
//        mysql_close($link);
//    print_r($sql);
        if($res)$this->outPut('like成功',1);//{ NSLog(array('code'=> 1,'msg'=>'like成功')); }
        else $this->outPut('like失败',0);//{NSLog(array('code'=> 0,'msg'=>'like失败'));}
    }
}