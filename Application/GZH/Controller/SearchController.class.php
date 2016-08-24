<?php
namespace GZH\Controller;
use Think\Controller;
class SearchController extends Controller {
    public function index(){
    	$this->assign('title',"搜寻公众号");
        $this->display();
    }
}