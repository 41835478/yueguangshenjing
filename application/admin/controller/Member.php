<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;
use think\Session;
use think\Validate;

class Member extends Base
{
    public function index()//修改管理员信息
    {
        $res=$this->getStatic();
        $find=model('Admin')->field(['name','pic'])->where(['mobile'=>$res[0],'id'=>$res[1]])->find();
        $mobile = substr($res[0], 3, 4);
        $find['mobile'] = str_replace($mobile, '●●●●', $res[0]);
        $find['id']=$res[1];
        $this->assign('res',$find);
        return view('member/index');
    }

    public function editAdmin()//修改管理员信息
    {
        $data=request()->except('id');
        $id=input('post.id');
        $mod=model('Admin');
        if(!empty($data['name'])){
            $date['name']=$data['name'];
        }
        if(!empty($data['mobile'])){
            $date['mobile']=$data['mobile'];
        }
        $flag=2;
        if(input('?file.pic')){
            $img=$mod->field(['pic'])->where(['id'=>$id])->find();
            $file = request()->file('pic');
            $info = $file->move(ROOT_PATH . 'public' .DS . 'resource'. DS . 'admin'.DS.'headPic');
            if ($info) {
                $flag=1;
                $saveName = $info->getSaveName();
                $arr = explode("\\", $saveName);
                $str = implode('/', $arr);
                $date['pic']='/admin/headPic/'.$str;
                session('pic',$date['pic'],'admin');
            } else {
                $this->error($file->getError());
            }
        }
        $date['update_at']=time();
        $res=$mod->where(['id'=>$id])->update($date);
        if($res){
            if(!empty($data['mobile'])){
                $arr=self::getStatic();
                $arr[0]=$data['mobile'];
                $str=implode('-',$arr);
                $info=$mod->getEncrypt($str);
                session('info',$info,'admin');
            }
            if($flag==1){
                if(file_exists('static'.$img['pic'])){
                    unlink('static'.$img['pic']);
                }
            }
            $Url=popBox('success','修改管理员信息成功');
            $this->redirect($Url);
        }else{
            $Url=popBox('error','修改管理员信息失败');
            $this->redirect($Url);
        }
    }

    public function pwd()//修改管理员密码
    {
        $res=$this->getStatic();
        $find=model('Admin')->field(['name','mobile'])->where(['mobile'=>$res[0],'id'=>$res[1]])->find();
        return view('pwd',['name'=>$find['name'],'mobile'=>$find['mobile'],'id'=>$res[1]]);
    }

    public function editPwd()//加载修改管理员密码操作
    {
        $date=request()->except(['id'],'post');
        $id=input('post.id','','intval');
        $rule=array(
            ['oldPwd','require','旧密码不能为空'],
            ['newPwd','require|length:6,16','新密码不能为空|新密码长度必须在6-16之间'],
            ['rePwd','require|confirm:newPwd','确认密码不能为空|两次密码不一致']
        );
        $validate=new Validate($rule);
        if(!$validate->check($date)){
            $url=popBox('warning',$validate->getError());
            $this->redirect($url);
        }else{
            $find=model('Admin')->get($id);
            if($find->pwd==md5($date['oldPwd'])){
                $data['pwd']=md5($date['newPwd']);
                $data['update_at']=time();
                $res=model('Admin')->where(['id'=>$id])->setField($data);
                if($res){
                    $url=popBox('success','修改密码成功');
                    $this->redirect($url);
                }else{
                    $url=popBox('error','修改密码失败');
                    $this->redirect($url);
                }
            }else{
                $url=popBox('error','旧密码输入错误');
                $this->redirect($url);
            }
        }
    }

    public function validatePwd()//jquery验证旧密码的正确性
    {
        $date=input('post.');
        $pwd=md5($date['oldPwd']);
        $mod=model('Admin')->get($date['id']);
        if($pwd==$mod->pwd){
            return json(['status'=>true,'error_message'=>'旧密码输入正确']);
        }
        return json(['status'=>false,'error_message'=>'旧密码输入错误']);
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
}
