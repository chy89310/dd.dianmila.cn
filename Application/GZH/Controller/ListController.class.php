<?php
namespace GZH\Controller;
use Think\Controller;
use Think\Page;

class ListController extends Controller {
    public function index(){
    	$p = I('get.p',1);
        $c = 10;
        $pager = new Page(D('GZH')->count(),$c);
        $pager->setConfig('prev','上一页');
        $pager->setConfig('next','下一页');
        $this->assign('pager',$pager->show());
    	$this->assign('list',$this->listGZH($p,$c));
    	$this->assign('title',"公众号列表");
        $this->display();
    }
    private function listGZH($page,$count) {
    	return D('GZH')->page($page,$count)->select();
    }
}