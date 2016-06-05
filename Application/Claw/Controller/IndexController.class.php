<?php
namespace Claw\Controller;
use Think\Controller;
use Keyword\Model\KeywordModel;
class IndexController extends Controller {
    public function index(){
    	$condition['taskId'] = new \MongoId(I('get.taskId'));
    	$result = D('Claw')->where($condition)->field('Data')->select();
    	$clawArr = array();
    	foreach ($result as $item) {
    		if (count($item['Data']) == 1) {
                $condition['clawId'] = new \MongoId($item['_id']);
                $result = D('Link/Link')->where($condition)->field(array('url','keyword'))->find();
                $item['Data'][0]['url'] = $result['url'];
                $item['Data'][0]['keyword'] = $result['keyword'];
    			array_push($clawArr, $item['Data'][0]);
    		}
    	}
    	$this->assign('list',$clawArr);
    	$this->display();    
    }
}