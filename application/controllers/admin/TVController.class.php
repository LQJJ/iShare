<?php

/**
 * Created by PhpStorm.
 * User: caozhi
 * Date: 2017/3/4
 * Time: 21:02
 */
class TVController extends Controller
{
    public function indexAction(){
        $this->library('NetWorking');
        $manager = new NetWorking();
        $name = !empty($_GET['name']) ? $_GET['name']:'广东';
        $html = $manager->get('http://m.91kds.com/');
        $list =  $this->proList($html);

        $html = $manager->get($list[$name]);
        $lvList = $this->tvList($html);

        foreach($lvList as $v){
            echo $v['name'];
            $html = $manager->get($v['url']);
            $this->urlList($html);
        }

    }

    private function proList($html){
//            $hmtl = iconv("gb2312","utf-8//IGNORE",$html);
        $patt = "/<li><a href=\"(.*)\" data-ajax=\"false\">(.*)<\/a><\/li>/i";
//        file_put_contents('./111.txt',$html);

        preg_match_all($patt,$html,$out);
        if(count($out) >0){
            $names = $out[2];
            $urls = $out[1];

            $array = array();
            for($i = 0 ;$i<count($urls);$i++){
                $array[$names[$i]] =  'http://m.91kds.com/'.$urls[$i];

            }
           return $array;
        }


    }
    private function tvList($html){
//            $hmtl = iconv("gb2312","utf-8//IGNORE",$html);
        $patt = "/<li><a href=\"([^h].+?)\" data-ajax=\"false\">(.+?)<\/a><\/li>/i";
//        file_put_contents('./111.txt',$html);

        preg_match_all($patt,$html,$out);
        if(count($out) >0){
            $names = $out[2];
            $urls = $out[1];

            $array = array();
            for($i = 0 ;$i<count($urls);$i++){
                $array[] = array(
                    'name' => $names[$i],
                    'url' => 'http://m.91kds.com/'.$urls[$i],
                );
            }
            return ($array);
        }


    }
    private function urlList($html){
        $patt = "/<option value=\"(.+?)\">(.+?)<\/option>/i";

        preg_match_all($patt,$html,$out);
        if(count($out[1]) >0){
            $max = (count($out[1]) > 1) ? 1 : count($out[1]);
            for($i=0;$i<$max;$i++){
                $this->getLiveKey($out[1][$i]);
            }


        }


    }


    private function getLiveKey($url) {
        if (substr_count($url,"kds1://") > 0 || substr_count($url,"kds2://") > 0)
        {
            if (substr_count($url,"kds1://") > 0) {
                $url = str_replace("kds1://","http://zb.91kds.com/b/",$url);
            } else if (substr_count($url,"kds2://") > 0) {
                $url = str_replace("kds2://","http://zb.91kds.com/c/",$url);
            }
            $url = str_replace("@@",".m3u8?",$url);

            $manager = new NetWorking();
            $obj = json_decode($manager->post('http://m.91kds.com/key.php?t='.rand()),true);
            if (!$obj) return;
            $this->startPlay($url, $obj['livekey'], $obj['token']);

        } else if (substr_count($url,"letvhtml") > 0)
        {
            $chid = substr($url,11);
            $manager = new NetWorking();
            $obj = json_decode($manager->get("http://m.91kds.com/key.php?t=" . rand() . "&id=" . $chid),true);
            if (!$obj) return;
            $this->startPlay($url, $obj['livekey'], $obj['token']);
        } else {
            $this->startPlay($url, "", "");
        }
    }
    private function startPlay($url, $k, $token)
    {

        if (substr_count($url,"zb.91kds.com") > 0 || substr_count($url,"v.91kds.com") > 0 || substr_count($url,"t.91kds.com") > 0)
        {
            $src = $url . "&" . $k;
        } else if (substr_count($url,"letvhtml") > 0)
        {
            $chid = substr($url,11);
            $jsurl = "http://live.gslb.letv.com/gslb?stream_id=" . $chid . "&tag=live&ext=m3u8&sign=live_photerne&p1=0&p2=00&p3=001&splatid=1004&ostype=andriod&hwtype=un&platid=10&playid=1&termid=2&pay=0&expect=3&format=1&" . $token . "&jsonp=?";

            $manager = new NetWorking();
            $cc = json_decode($manager->get($jsurl),true);

//            $cc = get($jsurl);
            $src = $cc['location'];

        } else {
            $src = $url;
        }
        echo '<br>&nbsp;&nbsp;&nbsp;&nbsp;';
        echo $src;
    }


}