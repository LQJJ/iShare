<?php

/**
 * Created by PhpStorm.
 * User: caozhi
 * Date: 2017/4/9
 * Time: 16:20
 */
class LoveQModel extends Model
{
    public function __construct()
    {
        parent::__construct('loveq_program');
    }

    public function getProgram($cat=0,$year=0,$mouth=0,$page=99999999,$count=20){
        /**
         *
         * cat 0 (全部) 1(国语) 2(粤语)
         * year
         * mouth
         * page
         *
         */

//        $cat = 0;
//        $year = 0;
//        $mouth = 0;
//        $page = 99999999;

//    die($page);
        if(isset($_REQUEST['cat'])) $cat = $_REQUEST['cat'];
        if(isset($_REQUEST['year'])) $year = $_REQUEST['year'];
        if(isset($_REQUEST['mouth'])) $mouth = $_REQUEST['mouth'];
        if(isset($_REQUEST['page'])) $page = $_REQUEST['page'];
//        $count = 20;
        if($cat == 1) {$cat = 2;}
        else if($cat == 2) {$cat = 1;}




        $year=sprintf ( "%04d",$year);
        $mouth=sprintf ( "%02d",$mouth);



        $sql = "select * from loveq_program WHERE cat_type <> $cat and date_format(addate,'%Y-%m')='$year-$mouth'  AND date_format(addate,'%Y%m%d')<'$page' ORDER BY  addate DESC,cat_type ASC  limit $count";
        if($year == 0 &&  $mouth == 0){
            $sql = "select * from loveq_program WHERE cat_type <> $cat AND date_format(addate,'%Y%m%d')<'$page' ORDER BY  addate DESC ,cat_type ASC limit $count";
        }else if($mouth == 0){
            $sql = "select * from loveq_program WHERE cat_type <> $cat and date_format(addate,'%Y')='$year'AND date_format(addate,'%Y%m%d')<'$page' ORDER BY  addate DESC ,cat_type ASC limit $count";

        }
//    die($sql) ;
//        $link = connectMySQL();
        $array = $this->db->getRows($sql);
//        $array = fetchArrays($sql);
//        mysql_close($link);
//        header("content-type:application/json;charset=utf-8");

//        die(json_encode($array));
        return $array;
    }
}