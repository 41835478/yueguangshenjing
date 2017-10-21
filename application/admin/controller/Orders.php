<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;

class Orders extends Controller
{
    public function index()
    {
        return view('orders/index');
    }
}
