<?php
namespace Proxy\Controller;
use Think\Controller;

class IndexController extends Controller {
    public function index(){
    	// $this->display();
    	$Model =  D("Proxy");
    	$Model->create();
    	var_dump($Model->count());
    	// $Model->create();
    	// $Model->name = '流年2';
    	// $Model->add();
    	// $a = $Model->select();
    	// var_dump($a);
    }
}