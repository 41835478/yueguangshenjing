<?php

namespace app\admin\controller;

use think\Controller;
use think\Db;
use think\Exception;
use think\Request;

class Video extends Base
{
    public function index()//加载添加视频视图
    {
        return view('video/index');
    }

    public function getToken()//获取token
    {
        $token=model('Service')->getToken();
        return json(['status'=>true,'message'=>'获取token','data'=>$token]);
    }

    public function addVideo(Request $request)//添加视频
    {
        $date=input('post.');
        $validate=validate('Video');
        if(!$validate->check($date)){
            $url=popBox('warning',$validate->getError());
            $this->redirect($url);
        }else{
            if(input('?file.pic')){
                $file = $request->file('pic');
                $info = $file->move(ROOT_PATH . 'public' . DS . 'resource'.DS.'admin'.DS.'video');
                if ($info) {
                    $saveName = $info->getSaveName();
                    $arr = explode("\\", $saveName);
                    $str = implode('/', $arr);
                    $date['pic']='/admin/video/'.$str;
                } else {
                    $url=popBox('warning',$file->getError());
                    $this->redirect($url);
                }
            }else{
                $url=popBox('error','请上传视频图片');
                $this->redirect($url);
            }
        }
        $date['video_path']='http://'.$date['video_path'];
        $res=model('Video')->data($date)->save();
        if($res){
            $url=popBox('success','上传成功');
        }else{
            $url=popBox('error','上传失败');
        }
        $this->redirect($url);
    }

    public function videoList()//视频列表
    {
        $data=model('Video')->paginate(config('page'));
        $page=$this->getPage($data);
        $this->assign('currentPage',$page['currentPage']);
        $this->assign('total',$page['total']);
        $this->assign('data', $data);
        $this->assign('render', $page['page']);
        return view('video/videoList');
    }

    public function editVideo(Request $request)//修改视频信息
    {
        $id=input('post.id');
        $video=model('Video')->get($id);
        $img=$video->pic;
        $flag=1;
        $video->title=input('post.title');
        if(input('?file.pic')){
            $files = $request->file('pic');
            $info = $files->move(ROOT_PATH . 'public' . DS . 'resource'.DS.'admin'.DS.'video');
            if ($info) {
                $flag=2;
                $saveName = $info->getSaveName();
                $arr = explode("\\", $saveName);
                $str = implode('/', $arr);
                $video->pic='/admin/video/'.$str;
            } else {
                $url=popBox('warning',$files->getError());
                $this->redirect($url);
            }
        }
        $res=$video->save();
        if($res){
            if($flag==2){
                unlink('./resource'.$img);
            }
            $url=popBox('success','修改视频图片成功');
        }else{
            $url=popBox('error','修改视频图片失败');
        }
        $this->redirect($url);
    }

    public function delVideo()//删除视频
    {
        $id=input('post.id');
        $video=model('Video')->get($id);
        $key=$video->video_key;
        $pic=$video->pic;
        Db::startTrans();
        try{
            $res=model('Video')->where(['id'=>$id])->delete();
            if($res){
                $res2=model('Service')->delFile($key);
                if($res2){
                    unlink('./resource'.$pic);
                    Db::commit();
                    return json(['status'=>true,'message'=>'删除成功']);
                }else{
                    throw new Exception();
                }
            }else{
                throw new Exception();
            }
        }catch(Exception $e){
            Db::rollback();
            return json(['status'=>false,'message'=>'删除失败']);
        }
    }

    public function configVideo()//视频设置
    {
        $date=input('post.');
        $video=model('Video')->get($date['id']);
        if($date['mark']==1){//说明是设置是否前台显示
            if($date['flag']==1){
                $sum=model('Video')->where(['flag'=>1])->sum('flag');
                if($sum>=1){
                    return json(['status'=>false,'message'=>'前台首页展示只能设置一个']);
                }
            }
            $video->flag=$date['flag'];
        }else{
            $video->status=$date['status'];
        }
        if($video->save()){
            return json(['status'=>true,'message'=>'设置成功']);
        }
        return json(['status'=>false,'message'=>'设置失败']);
    }
}
