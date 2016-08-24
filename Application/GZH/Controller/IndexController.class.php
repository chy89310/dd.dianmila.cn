<?php
namespace GZH\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index(){
    	$this->assign('title',"公众号任务");
        $this->display();
    }
}