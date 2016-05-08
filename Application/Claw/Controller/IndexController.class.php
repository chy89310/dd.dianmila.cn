<?php
namespace Claw\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index(){
    	$condition['taskId'] = new \MongoId(I('get.taskId'));
    	$result = D('Claw')->where($condition)->field('Data')->select();
    	var_dump($result);
    	exit();
    	// $this->assign('list',D('Claw')->where($condition)->select());
    	// $this->display();    
    }
}