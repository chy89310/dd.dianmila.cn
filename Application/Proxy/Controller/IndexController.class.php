<?php
namespace Proxy\Controller;
use Think\Controller;

class IndexController extends Controller {
    public function index(){
    	$this->display();
    	$Model =  D("Proxy");
    	$Model->create();
    	$p = 1;
    	dump($Model->getRecordsofPage($p));
    }
}