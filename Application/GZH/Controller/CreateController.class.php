<?php
namespace GZH\Controller;
use Think\Controller;
class CreateController extends Controller {
    public function index($keyword=""){
    	if ($keyword) {
    		$url = "http://weixin.sogou.com/weixin?type=1&query=".urlencode($keyword);
    		$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$result = curl_exec($ch);
			curl_close($ch);
			//请求服务器爬去当前搜寻公众号结果
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
	        	'TCPCommandEnum'=>"10",
	        	'url'=>$url));
	        if (socket_write($socket, $request, strlen($request))) {
	            if (socket_recv($socket, $buf, 8192, MSG_WAITALL)) {
	                socket_close($socket);
	            	$result = json_decode($buf);
	            	$this->assign('list', $result);
	            } else {
	                $this->error("socket读取失败\n");
	            }
	        } else {
	            $this->error("socket写入失败\n");
	        }
	        $this->assign('title',"搜寻结果：".$keyword);
        	$this->display();
    	} else {
    		$this->redirect('/GZH/Search/index');
    	}
    }
    public function save($name, $url, $desc) {
    	$gzh = array(
    		'Name'=>$name,
    		'Url'=>$url,
    		'Description'=>$desc,
    		'LastUpdate'=>0,
    		'CreateTime'=>time(),
    		'UpdateTime'=>time());
    	D('GZH')->add($gzh);
    }
}