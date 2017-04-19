<?php

/**
 * Created by PhpStorm.
 * User: caozhi
 * Date: 2017/2/6
 * Time: 15:13
 */
class CategoryModel extends Model
{
    public function getCats(){
        $sql = "select * from {$this->table}" ;
        $cats = $this->db->getRows($sql);
        return $this->tree($cats,0,0);
    }

    public function tree($arr,$pid = 0,$level = 0){
        static $res = array();
        foreach ($arr as $v){
            if ($v['parent_id'] == $pid) {
                //说明找到，先保存
                $v['level'] = $level;
                $res[] = $v;
                //改变条件，递归查找
                $this->tree($arr,$v['cat_id'],$level+1);
            }
        }
        return $res;
    }

}