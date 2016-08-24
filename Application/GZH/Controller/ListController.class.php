<?php
namespace GZH\Controller;
use Think\Controller;
class ListController extends Controller {
    public function index(){
    	$this->assign('title',"公众号列表");
        $this->display();
    }
}