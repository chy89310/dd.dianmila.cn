<?php
namespace Claw\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index(){
    	$condition['taskId'] = new \MongoId(I('get.taskId'));
    	$result = D('Claw')->where($condition)->field('Data')->select();
    	$clawArr = array();
    	foreach ($result as $item) {
    		if (count($item['Data']) == 1) {
    			array_push($clawArr, $item['Data'][0]);
    		}
    	}
    	$this->assign('list',$clawArr);
    	$this->display();    
    }
}