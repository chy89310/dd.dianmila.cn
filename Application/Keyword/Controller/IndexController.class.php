<?php
namespace Keyword\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index(){
    	$condition['TaskId'] = new \MongoId(I('get.taskId'));
    	$this->assign('list',D('Keyword')->where($condition)->select());
    	$this->display();    
    }
}