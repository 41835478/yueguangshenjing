<?php
namespace app\home\controller;

use think\Controller;

class Index extends controller
{
    public function index()
    {
    	return $this->fetch("index/index");  
    }
}
