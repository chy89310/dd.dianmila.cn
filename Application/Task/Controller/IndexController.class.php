<?php
namespace Task\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index(){
    	$Model =  D("Task");
    	$Model->create();
    	var_dump($Model->count());
    }
}