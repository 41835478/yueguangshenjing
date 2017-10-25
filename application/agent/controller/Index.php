<?php
namespace app\agent\controller;

use think\Controller;
use think\Request;

class Index extends Controller
{
    protected $beforeActionList=[
        'setToken'=>['except'=>'logon']
    ];

    final protected function setToken()
    {
        model('User')->setToken();
    }

    public function index()
    {
        $token=model('User')->getToken();
        return view('index/index',['token'=>$token]);
    }

    public function logon(Request $request)//执行后台登录
    {
        $date=$request->post();
        $mod=model('User');
        if($date['token']==session('token')){
            $validate=validate('Login');
            if($validate->check($date)){
                $res=$mod->where(['mobile'=>$date['mobile']])->where('level','in',[3,4,5,6])->find();
                if($res) {
                    if($res->login_pwd==md5($date['login_pwd'].$res->unique)){
                        if (captcha_check($date['code'])) {
                            $str = $date['mobile'] . '-' . $res['id'] . '-' . request()->header('user-agent') . '-' . uniqid() . time();
                            $info = $mod->getEncrypt($str);
                            session('info', $info,'agent');
                            session('pic', $res['user_pic'],'agent');
                            session('name',$res->name,'agent');
                            session('id',$res->id,'agent');
                            $this->redirect(url('main/index'));
                        } else {
                            $this->error('验证码输入错误', 'index/index');
                        }
                    }else{
                        $this->error('账号或密码错误', 'index/index');
                    }
                }else{
                    $this->error('您不是代理商或账号、密码错误', 'index/index');
                }
            }else{
                $this->error($validate->getError(),url('index/index'));
            }
        }else{
            $this->redirect(url('index/index'));
        }
    }

    public function layout()//退出后台
    {
        session(null,'agent');
        $this->redirect(url('index/index'));
    }
}
