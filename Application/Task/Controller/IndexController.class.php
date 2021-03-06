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

    public function testserver(){
        $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        if (!$socket) {
            $this->error("socket创建失败: ".socket_strerror(socket_last_error())."\n");
        }
        $connect = socket_connect($socket, "127.0.0.1", "33333");
        if (!$connect) {
            $this->error("socket链接失败: ".socket_strerror(socket_last_error($socket))."\n");
        }
        $request = json_encode(array(
        'Id'=>"000000000000000000000000",
        "TCPCommandEnum"=>"9"));
        if (socket_write($socket, $request, strlen($request))) {
            if (socket_recv($socket, $buf, 2048, MSG_WAITALL)) {
                socket_close($socket);
            $message = iconv("gb2312","utf-8",$buf);
            $this->success($message, '/Task/index', 3);
            } else {
                $this->error("socket读取失败\n");
            }
        } else {
            $this->error("socket写入失败\n");
        }
    }

    public function create() {
        $this->assign('title',"新增任务");
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
        $task_result = D('Task')->add($task);
        if (!$task_result) {
            //错误页面的默认跳转页面是返回前一页，通常不需要设置
            $this->error('新增失败');
        } else {
            //如新增成功，返回结果为新增任务的id
            //存储关键词
            $keywords = explode("\n", I('post.keywords'));
            $KeywordModel = new \Keyword\Model\KeywordModel();
            foreach ($keywords as $keyword) {
                $keywordObj = array(
                    'TaskId'=>$task_result,
                    'Keyword'=>$keyword,
                    'CreateTime'=>time(),
                    'UpdateTime'=>time());
                $result = $KeywordModel->add($keywordObj);
                if (!$result) {
                    //错误页面的默认跳转页面是返回前一页，通常不需要设置
                    $this->error('新增失败');
                }
            }
        }
        //设置成功后跳转页面的地址，默认的返回页面是$_SERVER['HTTP_REFERER']
        $this->success('新增成功', '/Task/index');
    }

    public function edit($id) {
        $task = D('Task')->find($id);
        $this->assign('title',"编辑任务");
        $this->assign('name',$task["Name"]);
        $this->assign('page',$task["Pages"]);
        $this->assign('id',$task["_id"]);
        $this->display();
    }

    public function editTask() {
        $data['Name'] = I('post.name');
        $data['Pages'] = I('post.page');
        $request['_id'] = I('post.id');
        $result = D('Task')->where($request)->save($data);
        if (!$result) {
            //错误页面的默认跳转页面是返回前一页，通常不需要设置
            $this->error('编辑失败');
        }
        //设置成功后跳转页面的地址，默认的返回页面是$_SERVER['HTTP_REFERER']
        $this->success('编辑成功', '/Task/index');
    }

    public function resumeOrPause() {
        $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        if (!$socket) {
            $this->error("socket创建失败: ".socket_strerror(socket_last_error())."\n");
        }
        $connect = socket_connect($socket, "127.0.0.1", "33333");
        if (!$connect) {
            $this->error("socket链接失败: ".socket_strerror(socket_last_error($socket))."\n");
        }
        $condition["_id"] = I('get.taskId');
        $task_arr = D('Task')->where($condition)->select();
        $task = $task_arr[I('get.taskId')];
        if ($task["TaskStatusEnum"] == 1) {
            //已开始
            $request = json_encode(array(
            'Id'=>I('get.taskId'),
            "TCPCommandEnum"=>"6"));
        } else if ($task["TaskStatusEnum"] == 0 || $task["TaskStatusEnum"] == 2 || $task["TaskStatusEnum"] == 3) {
            //未开始，已结束，暂停
            $request = json_encode(array(
            'Id'=>I('get.taskId'),
            "TCPCommandEnum"=>"7"));
        }
        if ($request) {
            socket_write($socket, $request, strlen($request));
            socket_close($socket);
            $this->success('发送成功', '/Task/index');
        }
    }

    public function delete() {
        $task_condition['_id'] = I('get.taskId');
        $keyword_condition['TaskId'] = new \MongoId(I('get.taskId'));
        $KeywordModel = new \Keyword\Model\KeywordModel();
        $task_result = D('Task')->where($task_condition)->delete();
        $keyword_result = $KeywordModel->where($keyword_condition)->delete();
        if($task_result && $keyword_condition){
            //设置成功后跳转页面的地址，默认的返回页面是$_SERVER['HTTP_REFERER']
            $this->success('删除成功', '/Task/index');
        } else {
            //错误页面的默认跳转页面是返回前一页，通常不需要设置
            $this->error('删除失败');
        }
    }

    private function listTask($page,$count) {
    	return D('Task')->page($page,$count)->select();
    }

    private function findTask($taskId) {
    	return D('Task')->find($taskId);
    }
}