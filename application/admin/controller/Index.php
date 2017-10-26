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
                            session('info', $info,'admin');
                            session('pic', $res['pic'],'admin');
                            session('name',$res['name'],'admin');
                            $this->redirect(url('main/index'));
                        } else {
                            $this->error('登录失败', 'index/index');
                        }
                    } else {
                        $this->error('验证码输入错误', 'index/index');
                    }
                }else{
                    $this->error('账号或密码错误', 'index/index');
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
        $res=$this->getStatic();
        $date['update_at']=time();
        $date['status']=2;
        $result=model('Admin')->where(['id'=>$res[1],'mobile'=>$res[0]])->setField($date);
        if($result){
            session(null,'admin');
            $this->redirect(url('index/index'));
        }else{
            $this->error('退出失败');
        }
    }

    public function getStatic()
    {
        return self::getUserInfo();
    }

    static private function getUserInfo()
    {
        $res=model('Admin')->getDecrypt(session('info','','admin'));
        if($res){
            $arr=explode('-',$res);
            return $arr;
        }else{
            return false;
        }
    }

    public function rulePage()//没有权限的页面
    {
        return view('main/rulePage');
    }
}
