<?php

/**
 * Created by PhpStorm.
 * User: caozhi
 * Date: 2017/3/4
 * Time: 17:10
 */
class StoryController extends Controller
{
        private $tps = array();
        private $bookname;

        public function cryptAction(){
            $this->library('Crypt');
            $encryption = new Crypt();
            echo $encryption->encrypt('123456') . "<br/>";
            echo $encryption->decrypt($encryption->encrypt('123456'));

        }

        public function indexAction(){
            $url = "http://m.5tps.com/m_l/12_9.html";
            $this->library('NetWorking');
            $manager = new NetWorking();
            $html = $manager->get($url);
            $html = iconv("gb2312","utf-8//IGNORE",$html);

            $patt = "/<a href=\'(.+?\.html)\' target='_self'>(.+?)<span/";
            preg_match_all($patt,$html,$out);
            foreach($out[1] as $v){
                echo "<a href='?a=tp&c=Story&url=http://m.5tps.com$v'>1111</a><br>";
            }
        }
        public function tpAction(){
            $url = $_GET['url'];//"http://m.5tps.com/m_h/14729.html";
            $this->library('NetWorking');
            $manager = new NetWorking();
            $html = $manager->get($url);
            $html = iconv("gb2312","utf-8//IGNORE",$html);

            $patt = "/class=\"bookimg\"><img src=\"(.+?)\"/";
            preg_match($patt,$html,$out);
            $bookimg = ('http://m.5tps.com'.$out[1]);

            $patt = "/<div>播音：<span>(.+)<\/span><\/div>/";
            preg_match($patt,$html,$out);
            $announcers = ($out[1]);

            $patt = "/<div class=\"book_intro\">([\s\S]+?)<script/";
            preg_match($patt,$html,$out);
            $info = ($out[1]);

            $patt = "/<div>状态：<span>(.+)<\/span><\/div>/";
            preg_match($patt,$html,$out);
            $status = ($out[1]);

            $patt = "/<div>类别：<span>(.+)<\/span><\/div>/";
            preg_match($patt,$html,$out);
            $category = ($out[1]);

            $patt = "/<div>更新时间：<span>(.+)<\/span><\/div>/";
            preg_match($patt,$html,$out);
            $time = ($out[1]);

            $patt = "/class=\"bookname\">(.+)<\/h2>/";
            preg_match($patt,$html,$out);
            $bookname = ($out[1]);
            $this->bookname = $bookname;
            $status = $status=='完结'? 1 : 0;
            $db = new MySQL($GLOBALS['config']);
            $sql = "insert into fm_book(info,book_name,time,author,category,status,book_img)
                    VALUES ('$info','$bookname','$time','$announcers','$category',$status,'$bookimg')";
            $no = $db->insert($sql);
            if($no) echo "book成功<br>";
            else echo "book失败<br>";
            $patt = "/<a href=\'(.+?)\' target=\'_self\'>(第[\d]+?回)/";
            preg_match_all($patt,$html,$out);
            $booklist = ($out[1]);
            echo "一共有".count($booklist)."<br>";
            foreach($booklist as $list){
                $urls[]=('http://m.5tps.com'.$list);
            }
            $manager->startNetWorking($urls,'tpsAction',$this);

//            die(json_encode(array(
//               'bookname' => $bookname,
//                'info' => $info,
//                'time' => $time,
//                'category' => $category,
//                'status' => $status,
//                'author' => $announcers,
//                'bookimg' => $bookimg,
//                'booklist' =>($this->tps),
//            )));
        }
        public function tpsAction($p)
        {

            $this->library('NetWorking');
            $manager = new NetWorking();

            $html = iconv("gb2312","utf-8//IGNORE",$p[0]);

//            if(!$html) return;
            $patt = "/<\/em>(.+)<\/h3>/";
            preg_match_all($patt,$html,$out);
            $name = ($out[1][0]);

            $patt = "/第([\d]+)回/";
            preg_match_all($patt,$name,$out);
            $no = $out[1][0];
            $patt = "/iframe src=\"(.+?)\"/";
            preg_match_all($patt,$html,$out);
            $html = $manager->get('http://m.5tps.com'.$out[1][0]);
            $html = iconv("gb2312","utf-8//IGNORE",$html);

            $patt = "/mp3:\'(http:\/\/.+)\?/";
            preg_match_all($patt,$html,$out);

            $url = ($out[1][0]);

            $this->tps[]=(array('name'=> $name,'url'=>$url));

            $db = new MySQL($GLOBALS['config']);
            $sql = "replace into fm_bookurl(book_name,url_name,url_no,book_url)
                    VALUES ('$this->bookname','$name',$no,'$url')";
            $id = $db->insert($sql);
            if($id) echo "$no---url--成功<br/>";
            else echo "$no---失败-$sql<br/>";

        }
        public function goAction(){

            $this->library('NetWorking');
            $manager = new NetWorking();
//            $html0 = $manager->get('http://yueyu.zgpingshu.com/');
//            $this->getStoryAuthor($html0);

//            $html1 = $manager->get('http://www.zgpingshu.com/mingrentang/linjin/');
//            $this->getStoryList($html1);

            $html2 = $manager->get('http://m.zgpingshu.com/pingshu/yueyu/4236/');
            $this->getStoryUrls($html2);

        }
        private function getStoryUrls($html){
            $html = iconv("gb2312","utf-8//IGNORE",$html);
            $patt = "/<a href=\"(.+)\" data-ajax=\"false\">([\d]+)回<\/a>/";
            preg_match_all($patt,$html,$out);




//            var_dump($out);
            $manager = new NetWorking();
            $urls = array();
            foreach($out[1] as $v){
                $urls[] =  'http://m.zgpingshu.com/pingshu/yueyu/4236'.$v;
            }
            $html = $manager->get('http://m.zgpingshu.com/pingshu/yueyu/4236/play/4236/');
            var_dump($urls[0]);
            header("content-type:text/html;charset=gb2312;");
            file_put_contents('./2111.txt',$html);
//            $manager->startNetWorking($urls,'getStoryUrl',$this);
//            var_dump($urls);
        }
        public function getStoryUrl($output){
            var_dump($output[0]);
            header("content-type:text/html;charset=gb2312;");
            file_put_contents('./111.txt',$output[0]);

            die();
        }
        private function getStoryAuthor($html){
//            $hmtl = iconv("gb2312","utf-8//IGNORE",$html);
            $patt = "/<img alt=\".+\" src=\"(.+)\"><\/a><a href=\"(.+)\" class=\"t-t\">(.+)<\/a>/i";
            file_put_contents('./111.txt',$html);

                preg_match_all($patt,$html,$out);
                if(count($out) == 4){
                    $names = $out[3];
                    $urls = $out[2];
                    $imgs = $out[1];
                    $array = array();
                    for($i = 0 ;$i<count($urls);$i++){
                        $array[] = array(
                            'name' => $names[$i],
                            'url' => $urls[$i],
                            'img' => $imgs[$i],
                        );
                    }
                    var_dump($array);
                }


        }
        private function getStoryList($html){
    $html = iconv("gb2312","utf-8//IGNORE",$html);
    $patt = "/<a href=\"(http:\/\/.+)\" target=\"_blank\"><img src=\"(http:\/\/.+)\" width=\"150\" height=\"200\" alt=\"(.+)\"><\/a>/i";
    preg_match_all($patt,$html,$out);



    $patt = "/<ul>
长度：<font color=\"#0099CC\">([\d]+)回<\/font><br \/>
比特率：<font color=\"#0099CC\">([\d]+)<\/font>kbps<br \/>
大小：<font color=\"#0099CC\">([\d]+.+)<\/font><br \/>
状态：<font color=\"#0099CC\">(.+)<\/font>
<\/ul>
/i";
    preg_match_all($patt,$html,$out1);
    if(count($out1) >0){
        $names = $out[3];
        $urls = $out[1];
        $imgs = $out[2];

        $sizes = $out1[3];
        $jis = $out1[1];
        $bts = $out1[2];
        $status = $out1[4];

        $array = array();
        for($i = 0 ;$i<count($urls);$i++){
            $array[] = array(
                'name' => $names[$i],
                'url' => $urls[$i],
                'img' => $imgs[$i],

                'size' => $sizes[$i],
                'ji' => $jis[$i],
                'bt' => $bts[$i],
                'status' => $status[$i],
            );
        }
        var_dump($array);
    }


}

}