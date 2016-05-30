<?php
namespace Account\Controller;
use Think\Controller;
use Think\Page;

class IndexController extends Controller {
    public function index(){
    	$p = I('get.p',1);
        $c = 10;

    	$this->assign('list',$this->listAccount($p,$c));

        $pager = new Page(D('Account')->count(),$c);
        $pager->setConfig('prev','上一页');
        $pager->setConfig('next','下一页');
        $this->assign('pager',$pager->show());
    	$this->display();
    }

    private function listAccount($page,$count) {
    	return D('Account')->page($page,$count)->select();
    }

    private function findAccount($taskId) {
    	return D('Account')->find($taskId);
    }

    public function save($userId="") {
    	if ($userId) {
    		$user = D('Account')->find($userId);
    		$this->assign('title',"编辑用户");
    		$this->assign('username',$user["Name"]);
    		$this->assign('id',$user["_id"]);
    	} else {
    		$this->assign('title',"创建用户");
    	}
        $this->display();
    }

    public function saveAccount() {
    	$id = I('post.userid');
    	$name = I('post.name');
        $password = I('post.password');
    	if ($id && D('Account')->find($id)) {
    		$data['Name'] = $name;
    		$data['Password'] = md5($password);
    		$request['_id'] = $id;
    		$account_result = D('Account')->where($request)->save($data);
    		if (!$account_result) {
	            //错误页面的默认跳转页面是返回前一页，通常不需要设置
	            $this->error('编辑失败');
	        }
	        //设置成功后跳转页面的地址，默认的返回页面是$_SERVER['HTTP_REFERER']
	        $this->success('编辑成功', '/Account/index');
    	} else {
    		$account = array(
    			'Name'=>$name,
    			'Password'=>md5($password),
    			'CreateTime'=>time(),
    			'UpdateTime'=>time());
    		$account_result = D('Account')->add($account);
        	if (!$account_result) {
	            //错误页面的默认跳转页面是返回前一页，通常不需要设置
	            $this->error('新增失败');
	        }
	        //设置成功后跳转页面的地址，默认的返回页面是$_SERVER['HTTP_REFERER']
	        $this->success('新增成功', '/Account/index');
    	}
    }
}