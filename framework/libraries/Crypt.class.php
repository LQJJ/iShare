<?php

/**
 * Created by PhpStorm.
 * User: caozhi
 * Date: 2017/3/11
 * Time: 22:42
 */
class Crypt
{
//    private $hex_iv = '00000000000000000000000000000000'; # converted JAVA byte code in to HEX and placed it here
    private $key = 'ishare.com'; #Same as in JAVA
    private $bkey = 'ishare.com';
    private $iv = '1234567890123456'; # converted JAVA byte code in to HEX and placed it here

    function __construct()
    {
        $bkey = floor(time()/20)-1;
        $key = floor(time()/20);

        echo $bkey.'---';
        echo $key;
        $this->key = md5(hash('sha256', base64_encode($this->key.$key), true));
        $this->bkey = md5(hash('sha256', base64_encode($this->bkey.$bkey), true));

//        echo $this->key.'<br/>';
//        echo $this->bkey.'<br/>';


    }
    public function getUrl($str){

    }
    function encrypt($str)
    {
        echo "加密$this->key<br>";
        $td = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_CBC, '');
        mcrypt_generic_init($td, $this->key, $this->iv);

        $block = mcrypt_get_block_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
        $pad = $block - (strlen($str) % $block);
        $str .= str_repeat(chr($pad), $pad);
        $encrypted = mcrypt_generic($td, $str);
        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);
        return base64_encode($encrypted);
    }

    function decrypt($code)
    {
        echo "   44444jiemi$this->key   ";
        $td = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_CBC, '');
        mcrypt_generic_init($td, $this->key, $this->iv);
        $base64 = base64_decode($code);
        var_dump( $td.'========');
        $str = mdecrypt_generic($td, $base64);
        $block = mcrypt_get_block_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);
        return $this->strippadding($str);
    }

    function bdecrypt($code)
    {
        echo "   6666jiemi$this->bkey   ";

        $td = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_CBC, '');
        mcrypt_generic_init($td, $this->bkey, $this->iv);

        $str = mdecrypt_generic($td, base64_decode($code));
        $block = mcrypt_get_block_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);
        return $this->strippadding($str);
    }
    /*
    For PKCS7 padding
    */
    private function addpadding($string, $blocksize = 16)
    {
        $len = strlen($string);
        $pad = $blocksize - ($len % $blocksize);
        $string .= str_repeat(chr($pad), $pad);
        return $string;
    }

    private function strippadding($string)
    {
        $slast = ord(substr($string, -1));
        $slastc = chr($slast);
        $pcheck = substr($string, -$slast);
        if (preg_match("/$slastc{" . $slast . "}/", $string)) {
            $string = substr($string, 0, strlen($string) - $slast);
            return $string;
        } else {
            return false;
        }
    }

    function hexToStr($hex)
    {
        $string = '';
        for ($i = 0; $i < strlen($hex) - 1; $i += 2) {
            $string .= chr(hexdec($hex[$i] . $hex[$i + 1]));
        }
        return $string;
    }
}

