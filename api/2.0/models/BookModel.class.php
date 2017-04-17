<?php

/**
 * Created by PhpStorm.
 * User: caozhi
 * Date: 2017/3/12
 * Time: 16:32
 */
class BookModel extends Model
{
    private $bookurlModel;
    public function __construct($table)
    {
        //echo 123;

        parent::__construct($table);
        $this->bookurlModel = new Model('bookurl');
    }
    public  function getList(){
        return $this->db->getRows("select book_name,time,author,category,status,book_img  from $this->table");
    }
    public function getStory($book_name){
        $res = $this->db->getRow("select * from $this->table WHERE book_name = '$book_name'");
        $sql = "select url_no,url_name,book_url from fm_bookurl WHERE book_name = '$book_name' order by url_no";
        $urls = $this->bookurlModel->db->getRows($sql);
        $res['book_list'] = $urls;
        return $res;
    }
}