<?php
namespace app\admin\controller;

use think\Controller;
use think\Request;

class Index extends Controller
{
    protected $beforeActionList=[
        'setToken'=>['except'=>'logon']
    ];

    final protected function setToken()
    {
        model('Admin')->setToken();
    }

    public function index()
    {
        $token=model('Admin')->getToken();
        return view('login/index',['token'=>$token]);
    }

    public function logon(Request $request)//执行后台登录
    {
        $date=$request->post();
        $mod=model('Admin');
        if($date['token']==session('token')){
            $validate=validate('Login');
            if($validate->check($date)){
                $res=$mod->where(['mobile'=>$date['mobile'],'pwd'=>md5($date['pwd'])])->find();
                if($res) {
                    if (captcha_check($date['captcha'])) {
                        $current_time=time();
                        $updateRes = $mod->updateAdmin(request()->ip(), $res['id'],$current_time);
                        if ($updateRes) {
                            $str = $date['mobile'] . '-' . $res['id'] . '-' . request()->header('user-agent') . '-' . uniqid() . time();
                            $info = $mod->getEncrypt($str);
                            session('info', $info);
                            session('pic', $res['pic']);
                            $this->redirect(url('main/index'));
                        } else {
                            $this->error('登录失败', 'index/index');
                        }
                    } else {
                        $this->error('验证码输入错误', 'index/index');
                    }
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
        $res=action('member/getStatic');
        $date['update_at']=time();
        $date['status']=2;
        $result=model('Admin')->where(['id'=>$res[1],'mobile'=>$res[0]])->setField($date);
        if($result){
            session(null);
            $this->redirect(url('index/index'));
        }else{
            $this->error('退出失败');
        }
    }
}
