<?php

/**
 * Created by PhpStorm.
 * User: caozhi
 * Date: 2017/2/6
 * Time: 15:03
 */
class CategoryController extends Controller
{
    public function indexAction(){
        $catModel = new CategoryModel('category');
        $cats = $catModel->getCats();
        require_once CUR_VIEW_PATH."cat_list.html";
    }
    public function addAction(){
        $catModel = new CategoryModel('category');
        $cats = $catModel->getCats();
        require_once CUR_VIEW_PATH."cat_add.html";
    }
    public function insertAction(){
        $data['cat_name'] = trim($_POST['cat_name']);
        $data['unit'] = trim($_POST['unit']);
        $data['sort_order'] = trim($_POST['sort_order']);
        $data['cat_desc'] = trim($_POST['cat_desc']);
        $data['parent_id'] = $_POST['parent_id'];
        $data['is_show'] = $_POST['is_show'];

        if(!$data['cat_name']){
            $this->jump("index.php?p=admin&c=category&a=add",'分类名称不能为空');
        }



        $catModel = new CategoryModel('category');
        if($catModel->insert($data)){
            $this->jump("index.php?p=admin&c=category&a=index",'分类添加成功');
        }else{
            $this->jump("index.php?p=admin&c=category&a=add",'分类添加失败');
        }
    }
    public function editAction(){
        require_once CUR_VIEW_PATH."cat_edit.html";
    }

}

?>


