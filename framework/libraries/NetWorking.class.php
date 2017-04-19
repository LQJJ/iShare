<?php

/**
 * Created by PhpStorm.
 * User: caozhi
 * Date: 2017/3/4
 * Time: 17:17
 */
class NetWorking
{
    /**
     * 根据url,postData获取curl请求对象,这个比较简单,可以看官方文档
     */
    private function getCurlObject($url, $postData = array(), $header = array())
    {
        $options = array();
        $url = trim($url);
        $options[CURLOPT_URL] = $url;
        $options[CURLOPT_TIMEOUT] = 10;
        $options[CURLOPT_USERAGENT] = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/44.0.2403.89 Safari/537.36';
        $options[CURLOPT_RETURNTRANSFER] = true;
        $header = array(
            "X-FORWARDED-FOR:".getenv("REMOTE_ADDR"),
            "CLIENT-IP:".getenv("REMOTE_ADDR")
        );
        $options[CURLOPT_HTTPHEADER] = $header;//IP

        //    $options[CURLOPT_PROXY] = '127.0.0.1:8888';
        foreach ($header as $key => $value) {
            $options[$key] = $value;
        }
        if (!empty($postData) && is_array($postData)) {
            $options[CURLOPT_POST] = true;
            $options[CURLOPT_POSTFIELDS] = http_build_query($postData);
        }
        if (stripos($url, 'https') === 0) {
            $options[CURLOPT_SSL_VERIFYPEER] = false;
        }
        $ch = curl_init();
        curl_setopt_array($ch, $options);

        return $ch;
    }

    private function getChList($urls = array())
    {
        $chlist = array();
        foreach ($urls as $url) {
            $chlist[] = $this->getCurlObject($url);
        }
        return $chlist;
    }

    public function startNetWorking($urls = array(), $func = '',$cls)
    {
        // var_dump($function);
        // 创建三个待请求的url对象
        $chList = $this->getChList($urls);
        // $chList[0] = getCurlObject("http://m.miguvideo.com/wap/resource/migu/detail/Detail_live.jsp?cid=619618278");
        // 创建多请求执行对象
        $downloader = curl_multi_init();

        // 将三个待请求对象放入下载器中
        foreach ($chList as $ch) {
            curl_multi_add_handle($downloader, $ch);
        }

        // 轮询
        do {
            while (($execrun = curl_multi_exec($downloader, $running)) == CURLM_CALL_MULTI_PERFORM) ;
            if ($execrun != CURLM_OK) {
                break;
            }

            // 一旦有一个请求完成，找出来，处理,因为curl底层是select，所以最大受限于1024
            while ($done = curl_multi_info_read($downloader)) {
                // 从请求中获取信息、内容、错误
                $info = curl_getinfo($done['handle']);
                $output = curl_multi_getcontent($done['handle']);
                $error = curl_error($done['handle']);

                if (!is_null($func)) {
//                    $func($output, $info, $error);

                    @call_user_method($func,$cls,array($output,$info,$error));
                }


                // 将请求结果保存,我这里是打印出来
//                 print($info);
                // var_dump($info['url']);

                // print \"一个请求下载完成!\n\";

                // 把请求已经完成了得 curl handle 删除
                curl_multi_remove_handle($downloader, $done['handle']);
            }

            // 当没有数据的时候进行堵塞，把 CPU 使用权交出来，避免上面 do 死循环空跑数据导致 CPU 100%
            if ($running) {
                $rel = curl_multi_select($downloader, 1);
                if ($rel == -1) {
                    usleep(1000);
                }
            }

            if ($running == false) {
                break;
            }
        } while (true);

        // 下载完毕,关闭下载器
        curl_multi_close($downloader);
    }

    public function get($url)
    {

        $ch = curl_init();
        $header = array(
            "X-FORWARDED-FOR:".getenv("REMOTE_ADDR"),
            "CLIENT-IP:".getenv("REMOTE_ADDR")
        );
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);//IP
        curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (iPad; CPU OS 9_1 like Mac OS X) AppleWebKit/601.1.46 (KHTML, like Gecko) Version/9.0 Mobile/13B143 Safari/601.1');
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt ($ch,CURLOPT_REFERER,'http://api.47ks.com');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $output = curl_exec($ch);
        //释放curl句柄
        curl_close($ch);
//    $output = file_get_contents($url);
//    var_dump($output);
//    die();
//        return json_decode($output,true);
        return $output;
    }
    public function post($url,$post_data = array ()){
        $ch = curl_init();
        $header = array(
            "X-FORWARDED-FOR:".getenv("REMOTE_ADDR"),
            "CLIENT-IP:".getenv("REMOTE_ADDR")
        );
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);//IP
        curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (iPad; CPU OS 9_1 like Mac OS X) AppleWebKit/601.1.46 (KHTML, like Gecko) Version/9.0 Mobile/13B143 Safari/601.1');
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt ($ch,CURLOPT_REFERER,'http://api.47ks.com');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // post数据
        curl_setopt($ch, CURLOPT_POST, 1);
        // post的变量
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        $output = curl_exec($ch);
        curl_close($ch);
        //打印获得的数据
//    var_dump($output);
//    die();
//    $json = file_get_contents($url);
//        return json_decode($output,true);
        return $output;
    }
}