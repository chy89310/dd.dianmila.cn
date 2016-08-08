<?php
namespace Keyword\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index() {
    	$condition['TaskId'] = new \MongoId(I('get.taskId'));
    	$this->assign('list',D('Keyword')->where($condition)->select());
    	$this->assign('taskId',I('get.taskId'));
    	$this->display();    
    }

    public function create() {
    	$this->assign('title',"新增关键词");
    	$this->assign('taskId',I('get.taskId'));
    	$this->display();
    }

    public function createKeyword() {
    	$keywords = explode("\n", I('post.keywords'));
        $KeywordModel = new \Keyword\Model\KeywordModel();
        foreach ($keywords as $keyword) {
            $keywordObj = array(
                'TaskId'=>new \MongoId(I('post.taskId')),
                'Keyword'=>$keyword,
                'CreateTime'=>time(),
                'UpdateTime'=>time());
            $result = $KeywordModel->add($keywordObj);
            if (!$result) {
                //错误页面的默认跳转页面是返回前一页，通常不需要设置
                $this->error('新增失败');
            }
        }
        $this->success('新增成功', '/Keyword/index/index?taskId='.I("post.taskId"));
    }

    public function edit($id) {
    	$keyword = D('Keyword')->find($id);
		$this->assign('title',"编辑关键词");
		$this->assign('keyword',$keyword["Keyword"]);
		$this->assign('id',$keyword["_id"]);
		$this->assign('taskId',$keyword["TaskId"]);
		$this->display();
    }

    public function editKeyword() {
    	$data['Keyword'] = I('post.keyword');
		$request['_id'] = I('post.id');
		$result = D('Keyword')->where($request)->save($data);
		if (!$result) {
            //错误页面的默认跳转页面是返回前一页，通常不需要设置
            $this->error('编辑失败');
        }
        //设置成功后跳转页面的地址，默认的返回页面是$_SERVER['HTTP_REFERER']
        $this->success('编辑成功', '/Keyword/index/index?taskId='.I('post.taskId'));
    }

    public function delete($id) {
        $condition['_id'] = $id;
        $result = D('Keyword')->where($condition)->delete();
        if($result){
            //设置成功后跳转页面的地址，默认的返回页面是$_SERVER['HTTP_REFERER']
            $this->success('删除成功', '/Keyword/index/index?taskId='.I("get.taskId"));
        } else {
            //错误页面的默认跳转页面是返回前一页，通常不需要设置
            $this->error('删除失败');
        }
    }
}