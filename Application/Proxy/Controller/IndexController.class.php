<?php
namespace Proxy\Controller;
use Think\Controller;
use Think\Page;

class IndexController extends Controller {
    public function index(){
    	$p = I('get.p',1);
        $c = 10;
    	$this->assign('list',$this->listTask($p,$c));

        $pager = new Page(D('Proxy')->count(),$c);
        $pager->setConfig('prev','上一页');
        $pager->setConfig('next','下一页');
        $this->assign('pager',$pager->show());
    	$this->display();
    }

    private function listTask($page,$count) {
    	return D('Proxy')->page($page,$count)->select();
    }

    private function findTask($taskId) {
    	return D('Proxy')->find($taskId);
    }
}