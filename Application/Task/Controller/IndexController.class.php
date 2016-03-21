<?php
namespace Task\Controller;
use Think\Controller;
use Think\Page;
class IndexController extends Controller {
    public function index(){
        $p = I('get.p',1);
        $c = 10;
    	$this->assign('list',$this->listTask($p,$c));

        $pager = new Page(D('Task')->count(),$c);
        $pager->setConfig('prev','上一页');
        $pager->setConfig('next','下一页');
        $this->assign('pager',$pager->show());
        $this->display();
    }

    public function create() {
        $this->display();
    }

    public function createTask() {
        $name = I('post.name',"名称");
        $pages = I('post.pages',1);
    	$task = array(
    			'Name'=>$name,
    			'Pages'=>$pages,
    			'CreateTime'=>time(),
    			'UpdateTime'=>time(),
    			'TaskStatusEnum'=>0);
    	$result = D('Task')->add($task);
        if($result){
            //设置成功后跳转页面的地址，默认的返回页面是$_SERVER['HTTP_REFERER']
            $this->success('新增成功', '/Task/index');
        } else {
            //错误页面的默认跳转页面是返回前一页，通常不需要设置
            $this->error('新增失败');
        }
    }

    private function listTask($page,$count) {
    	return D('Task')->page($page,$count)->select();
    }

    private function findTask($taskId) {
    	return D('Task')->find($taskId);
    }
}