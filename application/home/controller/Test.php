<?php

namespace app\home\controller;

use app\home\service\SocketService;
use think\Controller;
use think\log\driver\Socket;
use think\Request;
use Redis;

class Test extends Controller
{
    public function index()
    {
//        $socket=socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
//        socket_set_option($socket, SOL_SOCKET, SO_REUSEADDR, TRUE);
//        socket_bind($socket, $host, $port);
//        socket_listen($this->socket, $max);
//        halt($socket);
        die;
        $redis = new Redis();
        $bool = $redis->connect('127.0.0.1');
        if ($bool) {
//            $redis->set(1,'test1');
//            $redis->set(2,'test2');
//            $redis->set(3,'test3');
//            $redis->set(4,'test4');
//            $redis->set(5,'test5');
//            $redis->set(5,'test6');

//            $redis->del(4);
        }

        halt($redis->get(4));
//            $redis->del(4);

    }

    public function index2()
    {
        $arr = $result = $array = array();
        while (true) {
            $i = mt_rand(1, 6);
            if (!in_array($i, $arr)) {
                $arr[] = $i;
                if (count($arr) == 4) {
                    if (count($result) <= 4320) {
                        $str = implode(',', $arr);
                        $string = str_replace(',', '', $str);
                        $num = intval($string);
                        $result[] = $num;
                        $arr = array();
                    } else {
                        break;
                    }
                }
            }
        }
        foreach (array_unique($result) as $v) {
            if ($v % 4 == 0) {
                $array[] = $v;
            }
        }
        halt(array_unique($array));
    }

    public function index1()
    {
        return view('test/index');
    }
}
