<?php

/**
 * Created by PhpStorm.
 * User: caozhi
 * Date: 2017/4/8
 * Time: 21:55
 */

class TvModel extends Model
{


    public function __construct()
    {
        parent::__construct('tv');
    }

    public function deleteTV()
    {

        $tv_id = isset($_REQUEST['tv_id']) ? $_REQUEST['tv_id'] : null;//$_REQUEST('tv_id');
        $sql = "DELETE FROM `tv` WHERE tv_id = $tv_id";
        $res = $this->db->queryBool($sql);
        //    die($sql);
        $array = array();
        if ($res == 0) {
            $this->outPut('操作失败');
        } else {

            $this->outPut('操作成功', 1);
        }
    }
    public function likeTV(){
        $tv_id = isset($_REQUEST['tv_id']) ? $_REQUEST['tv_id'] : null;//$_REQUEST('tv_id');


        //    NSLog($likes);
        $likes = $this->db->getRows("select like_count from tv WHERE tv_id = $tv_id");
        if(!count($likes)) $this->outPut('当前频道不可以like');//NSLog(array('code'=> 0,'msg'=>'当前频道不可以like'));
        $count = $likes[0]['like_count'] + 1;

        $sql = "UPDATE `tv` SET like_count=$count WHERE tv_id = $tv_id";
        $res = $this->db->queryBool($sql);
        if($res == 1){
           // NSLog(array('code'=> 1,'msg'=>'like成功'));
            $this->outPut('like成功',1);
        }
        else{
           // NSLog(array('code'=> 0,'msg'=>'like失败'));
            $this->outPut('like失败');
        }
    }

    public function getChannel(){

        $sql = "select type,type_name from tv group by type";

        $res = $this->db->getRows($sql);

        $json =  array(
            array("type" => 1, "type_name" => "广东"),
            array("type" => 2, "type_name" => "央视"),
            array("type" => 3, "type_name" => "咪咕"),
            array("type" => 77, "type_name" => "香港"),
            array("type" => 76, "type_name" => "澳门"),
            array("type" => 75, "type_name" => "台湾"),
            array("type" => 74, "type_name" => "美剧"),
            array("type" => 73, "type_name" => "海南"),
            array("type" => 79, "type_name" => "CIBN"),
            array("type" => 78, "type_name" => "求索"),
            array("type" => 72, "type_name" => "北京"),
            array("type" => 71, "type_name" => "上海"),
            array("type" => 70, "type_name" => "湖南"),
            array("type" => 69, "type_name" => "湖北"),
            array("type" => 68, "type_name" => "江苏"),
            array("type" => 67, "type_name" => "天津"),
            array("type" => 66, "type_name" => "山东"),
            array("type" => 65, "type_name" => "重庆"),
            array("type" => 64, "type_name" => "广西"),
            array("type" => 63, "type_name" => "安徽"),
            array("type" => 62, "type_name" => "福建"),
            array("type" => 61, "type_name" => "浙江"),
            array("type" => 60, "type_name" => "四川"),
            array("type" => 59, "type_name" => "贵州"),
            array("type" => 58, "type_name" => "河北"),
            array("type" => 57, "type_name" => "河南"),

            array("type" => 55, "type_name" => "江西"),
            array("type" => 54, "type_name" => "吉林"),
            array("type" => 53, "type_name" => "辽宁"),
            array("type" => 52, "type_name" => "宁夏"),
            array("type" => 51, "type_name" => "青海"),
            array("type" => 50, "type_name" => "陕西"),
            array("type" => 49, "type_name" => "山西"),
            array("type" => 48 , "type_name" => "甘肃"),
            array("type" => 47, "type_name" => "新疆"),
            array("type" => 46, "type_name" => "云南"),

            array("type" => 44, "type_name" => "西藏"),
            array("type" => 43, "type_name" => "卫视"),
            array("type" => 45, "type_name" => "内蒙古"),
            array("type" => 56, "type_name" => "黑龙江"),
            array("type" => 42, "type_name" => "海外"),
            array("type" => 41, "type_name" => "虎牙"),
            array("type" => 40, "type_name" => "香港"),
            array("type" => 39, "type_name" => "台湾"),
        );
        foreach($res as $key => $value){
            if($value['type_name'] == '测试'){
                $json[] = $value;
            }
        }
        return $json;
    }
    public function getTvList($id){
        if($id == 0){//地区列表
        }
        elseif($id==2){//央视
            return $this->getCCTV();
        }elseif($id==3){//gumi
            return $this->getGuMi();
        }
        elseif($id >= 73 && $id <=79){
            $sql = "select tv_id , id ,name,m3u8,img from tv where type = $id ORDER BY like_count desc ,tv_id ASC;";

            return $this->db->getRows($sql);
        }
        elseif($id>100){// 测试
            $sql = "select tv_id , id ,name,m3u8,img from tv where type = $id ORDER BY like_count desc ,tv_id ASC;";
            return $this->db->getRows($sql);
        }else{// 91

            $url = 'http://'.$_SERVER['SERVER_NAME'].dirname($_SERVER['SCRIPT_NAME'])."?c=tv&a=channel&debug=9";

            $array = file_get_contents($url);
            $array = json_decode($array,true);


            foreach($array['data'] as $value){
                if($value['type'] == $id){
                    $type = $value['type_name'];
                    return $this->get91($type);
                }
            }
            return array(array(
                "id" => 0,
                "name" => "未知",
                "m3u8" => "未知",
                "img" => "未知"

            ));
        }
    }

    public function getVideo(){
        $manager = new NetWorking();
        $data = array(
            'cip'=> '0',
'csign'=> '04FC43CCE11EE924F31A0486D81B5BAA',
'from'=>'',
'ptype'=>'',
't'=>'',
'token'=> 'LG41Ib3C4Dc3krpIY9W4iHCA5YE54bZhnL%2FSZ2MQv3titiLlBemPth1m8MbKiPJCcMieL%2BA3%2BK%2F7REsgkgZ34g9GxULj%2FFVOEukYkL3CHz8Tt7k%2Bawx720dEG0KzmbKAWKM67jsNkaGd7bsx9uoQHmFylfhbbowNYKF6AGcM%2FpI%3D',
'up'=> '0',
'v'=> 'https://film.sohu.com/album/8388402.html?channeled=1200320002'
        );
        $html = $manager->post('http://api.47ks.com/config/webmain.php',$data);
        file_put_contents('./data.txt',$html);
        var_dump( $html);
    }

    public function getNewVideo(){
        $manager = new NetWorking();
        $html = $manager->get('http://live.ttzx.tv/list.html');
//        file_put_contents('./data.txt',$html);
//        $patt = "/<a href=\'(.+?)\' target=\'_self\'>(第[\d]+?回)/";
        $patt = "/#open\(\'(.+?)\'\).Play\(\'vod:(.+?)\'\).Num/";
        preg_match_all($patt,$html,$out);

        $json = array();
        if(count($out)==3){

            for($i = 0 ; $i < count($out[1]);$i++){
                $json[] = array(
                  'name' => $out[1][$i],
                    'm3u8'=> $out[2][$i]
                );
            }

        }
        return $json;
    }

    private function getCurlObject($url,$postData=array(),$header=array()){
        $options = array();
        $url = trim($url);
        $options[CURLOPT_URL] = $url;
        $options[CURLOPT_TIMEOUT] = 10;
        $options[CURLOPT_USERAGENT] = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/44.0.2403.89 Safari/537.36';
        $options[CURLOPT_RETURNTRANSFER] = true;
        //    $options[CURLOPT_PROXY] = '127.0.0.1:8888';
        foreach($header as $key=>$value){
            $options[$key] =$value;
        }
        if(!empty($postData) && is_array($postData)){
            $options[CURLOPT_POST] = true;
            $options[CURLOPT_POSTFIELDS] = http_build_query($postData);
        }
        if(stripos($url,'https') === 0){
            $options[CURLOPT_SSL_VERIFYPEER] = false;
        }
        $ch = curl_init();
        curl_setopt_array($ch,$options);

        return $ch;
    }
    private function getCCTV(){
        // 创建三个待请求的url对象
        $chList = array();
        $json = array();
        $channel = array();
        for($i = 1;$i <16;$i++)
        {
            $url="http://vdn.live.cntv.cn/api2/live.do?client=androidapp&channel=pa://cctv_p2p_hdcctv{$i}";
            $chList[] =$this->getCurlObject($url);
            $channel[$url] = $i;


        }


// 创建多请求执行对象
        $downloader = curl_multi_init();

// 将三个待请求对象放入下载器中
        foreach ($chList as $ch){
            curl_multi_add_handle($downloader,$ch);
        }



// 轮询
        do {
            while (($execrun = curl_multi_exec($downloader, $running)) == CURLM_CALL_MULTI_PERFORM) ;
            if ($execrun != CURLM_OK) {
                break;
            }

            // 一旦有一个请求完成，找出来，处理,因为curl底层是select，所以最大受限于1024
            while ($done = curl_multi_info_read($downloader))
            {
                // 从请求中获取信息、内容、错误
                $info = curl_getinfo($done['handle']);
                $output = curl_multi_getcontent($done['handle']);
                $error = curl_error($done['handle']);

                // 将请求结果保存,我这里是打印出来
//        print $output;
                $array = json_decode($output);


                if($array->ack == 'yes')
                {
//            echo"<pre>";
//            print_r($info['url']);
//            print_r($array);

                    $index = $channel[$info['url']];

                    $id = 0;
                    $img = "http://d.hiphotos.baidu.com/baike/w%3D268%3Bg%3D0/sign=3a691cd39022720e7bcee5fc43f06d7b/bba1cd11728b47108a8c447dcbcec3fdfd0323db.jpg";
                    $name = "CCTV$index";
                    $m3u8 = "";
                    $flvObj = $array->hls_url;

                    $m3u8 = $flvObj->hls2;
                    $m3u8_2 = $flvObj->hls4;
                    $m3u8_3 = $flvObj->hls5;

                    $temp = array(
                        'id' => $id,
                        'name'=>$name,
                        'img' => ($img),
                        'm3u8' => ($m3u8),
                        'm3u8_2' => ($m3u8_2),
                        'm3u8_3' => ($m3u8_3)
                    );
//            print_r($temp);
//            $json[] = $temp;

//            die("</pre>");
//            $id = $array[0]->id;
//
//            $name = $array[0]->name;
//            $img = $array[0]->snap->host.$array[0]->snap->dir.$array[0]->snap->filepath.$array[0]->snap->filename;
//            $m3u8 = $array[0]->m3u8;
//            $json[] = array(
//                'id' => $id,
//                'name' => $name,
//                'm3u8' => $m3u8,
//                'img' => $img
//
//            );
                    $json[]=$temp;
                }
//        print "一个请求下载完成!\n";

                // 把请求已经完成了得 curl handle 删除
                curl_multi_remove_handle($downloader, $done['handle']);
            }

            // 当没有数据的时候进行堵塞，把 CPU 使用权交出来，避免上面 do 死循环空跑数据导致 CPU 100%
            if ($running) {
                $rel = curl_multi_select($downloader, 1);
                if($rel == -1){
                    usleep(1000);
                }
            }

            if( $running == false){
                break;
            }
        } while (true);

// 下载完毕,关闭下载器
        curl_multi_close($downloader);
//echo "所有请求下载完成!";
//        header("content-type:application/json;charset=utf-8");
//        die(json_encode($json));
        return $json;
    }
    private function getProgramList(){
        global $json;
        $html = file_get_contents("http://m.miguvideo.com/wap/resource/migu/live/live-list.jsp");
        if(!$html) die('无法加载');
        $html_dom = new \HtmlParser\ParserDom($html);//new \HtmlParser\ParserDom($html);
        $a_array = $html_dom->find('li.station-list');//#
        $list = array();
        foreach($a_array as $key => $value){
            $id = $value->getAttr("onclick");
            $id = str_replace("tz(","",$id);
            $id = str_replace(")","",$id);
            $divs = $value->find('div');
            $url =  "http://m.miguvideo.com/wap/resource/migu/detail/Detail_live.jsp?cid=$id";
            $img = 'http://m.miguvideo.com/'.$divs[0]->find('img',0)->getAttr('src');
            $pd = $divs[1]->find('h3',0)->getPlainText();
            $jm = $divs[1]->find('p',0)->getPlainText();
            $list[] = ($url);
            $json[$url] = Array('img'=>$img,'name'=>$pd,'program'=>$jm,'m3u8'=>'');
        }
        return $list;
    }

    public function geturl($p){
//        echo 45;
        $html = $p[0];
        $info = $p[1];
        $error = $p[2];
        if(!$html)
        {
            return;
        }
        global $json,$appjson;
        $html_dom = new \HtmlParser\ParserDom($html);
        $a_array = $html_dom->find('div.videos',0);
        if($a_array) {
            $a_array = $a_array->find('script',0)->node->textContent;//#
        }else{
            return "";
        }
        $a_array = $html_dom->find('div.videos',0)->find('script',0)->node->textContent;//#
        preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i",$a_array,$dl);

        if(count($dl)) {
            $m3u8 = $dl[0];
            $json[$info['url']]['m3u8'] =  $m3u8 ;
            $appjson[] = $json[$info['url']];
//             echo "[手机直播源交流群] <a href='https://jq.qq.com/?_wv=1027&k=43eFDtj'>205287408</a>".$json[$info['url']]['name']."<a href='$m3u8'>$m3u8</a><hr/>";
//               echo "[手机直播源交流群] <a href='https://jq.qq.com/?_wv=1027&k=43eFDtj'>205287408</a>"."<a href='$m3u8'>$m3u8</a><hr/>";

        }
    }
    private function getGuMi(){
        $json = Array();
        $appjson = Array();

        $manager = new NetWorking();
        $manager->startNetWorking($this->getProgramList(),'geturl',$this);
        global $appjson;
        return  $appjson;
    }


    private function unzip_file($filename, $path){
// 实例化对象
        $zip = new ZipArchive() ;

//打开zip文档，如果打开失败返回提示信息
        if ($zip->open($filename) !== TRUE) {
            die ("Could not open archive");
        }
//将压缩文件解压到指定的目录下
        $zip->extractTo($path);
        $file_name = $path.$zip->getNameIndex(0);

//关闭zip文档
        $zip->close();
        unlink($filename);
        return $file_name;

    }
    private function download($url, $dir='', $filename=''){
        if(empty($url)){
            return false;
        }
        $ext = strrchr($url, '.');
//    if($ext != '.gif' && $ext != ".jpg" && $ext != ".bmp"){
//        echo "格式不支持！";
//        return false;
//    }


        //为空就当前目录
        if(empty($dir))$dir = './';

//

        $dir = realpath($dir);
        //目录+文件
        $filename = $dir . (empty($filename) ? '/'.time().$ext : '/'.$filename);
        //开始捕捉
        ob_start();
        readfile($url);
        $img = ob_get_contents();
        ob_end_clean();
        $size = strlen($img);
//    echo $filename;;
        $fp2 = fopen($filename , "a");
        fwrite($fp2, $img);
        fclose($fp2);
        return $filename;
    }
    private function get91($type){

        if(file_exists('../public/date.txt')){
            $lastTime = file_get_contents('../public/date.txt');
            if((time() - (int)$lastTime) > 24 * 3600)
            {

                //下载zip
                $this->download("http://v.91kds.com/lb/channel_list3.zip","../public/" ,"list.zip");
                //解压
                //$file = get_zip_originalsize('pro/list.zip','pro/');
                $file = $this->unzip_file('../public/list.zip','../public/');

                $html = file_get_contents($file);
                $array = json_decode($html,true);

                $json = array();
                foreach($array as $chanel){
                    //    echo $chanel['types'].'----'.$chanel['channel_name']."<br/>";
                    if(array_key_exists("province", $chanel)){
                        //     echo $chanel['province'].'=======';
                        if($chanel['province'] == $type)
                        {
                            $url = $chanel['url'];
                            $url = str_replace("v.91kds.","zb.91kds.",$url);
                            $url = str_replace("&pwd=91kds","&apikey=Y2FhveGluRjaYwNjkzREM3NjYwQjg3QUVGM0M3NzIb1OEUwMTZFN0NFMDE2RTdD",$url);
                            $chanel['url'] = $url;
                            $urls = $chanel['second_url'];
                            $temps = array();
                            foreach($urls as $key => $val){
                                $url = str_replace("v.91kds.","zb.91kds.",$val);
                                $url = str_replace("&pwd=91kds","&apikey=Y2FhveGluRjaYwNjkzREM3NjYwQjg3QUVGM0M3NzIb1OEUwMTZFN0NFMDE2RTdD",$url);
                                $urls[$key] = $url;
                            }
                            $chanel['second_url']=$urls;
                            $ch=array(
                                "id" => 0,
                                "name" => $chanel['channel_name'],
                                "m3u8" => $chanel['url'],
                                "img" => $chanel['icon_url']

                            );
                            $json[]=$ch;
                        }
                    }
                }
                unlink($file);


                file_put_contents('../public/list.txt',$html);
                file_put_contents('../public/date.txt',time());

                return $json;
//                die(json_encode($json));
            }
            else
            {
                $html = file_get_contents('../public/list.txt');
                $array = json_decode($html,true);
                $json = array();
                foreach($array as $chanel){
                    //    echo $chanel['types'].'----'.$chanel['channel_name']."<br/>";
                    if(array_key_exists("province", $chanel)){
                        //     echo $chanel['province'].'=======';
                        if($chanel['province'] == $type)
                        {
                            $url = $chanel['url'];
                            $url = str_replace("v.91kds.","zb.91kds.",$url);
                            $url = str_replace("&pwd=91kds","&apikey=Y2FhveGluRjaYwNjkzREM3NjYwQjg3QUVGM0M3NzIb1OEUwMTZFN0NFMDE2RTdD",$url);
                            $chanel['url'] = $url;
                            $urls = $chanel['second_url'];
                            $temps = array();
                            foreach($urls as $key => $val){
                                $url = str_replace("v.91kds.","zb.91kds.",$val);
                                $url = str_replace("&pwd=91kds","&apikey=Y2FhveGluRjaYwNjkzREM3NjYwQjg3QUVGM0M3NzIb1OEUwMTZFN0NFMDE2RTdD",$url);
                                $urls[$key] = $url;
                            }
                            $chanel['second_url']=$urls;
                            $ch=array(
                                "id" => 0,
                                "name" => $chanel['channel_name'],
                                "m3u8" => $chanel['url'],
                                "img" => $chanel['icon_url']

                            );
                            $json[]=$ch;
                        }
                    }
                }
                return $json;
//                die(json_encode($json));
            }
        }
        else
        {
            //下载zip
            $this->download("http://v.91kds.com/lb/channel_list3.zip","../public/" ,"list.zip");
            //解压
            //$file = get_zip_originalsize('pro/list.zip','pro/');
            $file = $this->unzip_file('../public/list.zip','../public/');

            $html = file_get_contents($file);
            $array = json_decode($html,true);

            $json = array();
            foreach($array as $chanel){
                //    echo $chanel['types'].'----'.$chanel['channel_name']."<br/>";
                if(array_key_exists("province", $chanel)){
                    //     echo $chanel['province'].'=======';
                    if($chanel['province'] == $type)
                    {
                        $url = $chanel['url'];
                        $url = str_replace("v.91kds.","zb.91kds.",$url);
                        $url = str_replace("&pwd=91kds","&apikey=Y2FhveGluRjaYwNjkzREM3NjYwQjg3QUVGM0M3NzIb1OEUwMTZFN0NFMDE2RTdD",$url);
                        $chanel['url'] = $url;
                        $urls = $chanel['second_url'];
                        $temps = array();
                        foreach($urls as $key => $val){
                            $url = str_replace("v.91kds.","zb.91kds.",$val);
                            $url = str_replace("&pwd=91kds","&apikey=Y2FhveGluRjaYwNjkzREM3NjYwQjg3QUVGM0M3NzIb1OEUwMTZFN0NFMDE2RTdD",$url);
                            $urls[$key] = $url;
                        }
                        $chanel['second_url']=$urls;
                        $ch=array(
                            "id" => 0,
                            "name" => $chanel['channel_name'],
                            "m3u8" => $chanel['url'],
                            "img" => $chanel['icon_url']

                        );
                        $json[]=$ch;
                    }
                }
            }
            unlink($file);


            file_put_contents('../public/list.txt',$html);
            file_put_contents('../public/date.txt',time());

            return $json;
//            die(json_encode($json));

        }
    }




}