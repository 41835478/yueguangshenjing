<?php
namespace app\home\controller;

use think\Controller;

class Index extends controller
{
    public function index()
    {
    	dump(123);
    	return $this->fetch("index/index");  
    }
}
