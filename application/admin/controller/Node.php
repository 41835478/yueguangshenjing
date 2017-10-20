<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;
use app\admin\model\Nodes;

class Node extends Base
{
    public function index()//节点管理
    {
        $data=model('Nodes')->field(['id','pid','node_name','concat(path,",",id)'=>'paths','controller_name','action_name','is_menu','style','create_at'])
            ->order('paths')->paginate(50);
        if($data){
            foreach($data->items() as $k=>$v){
                $count = (count(explode(',', $v['paths']))-1)*2;
                if ($v['pid'] == 0) {
                    $name = $v['node_name'];
                } else {
                    $name = str_repeat('&nbsp;&nbsp;', $count) . $v['node_name'];
                }
                $data->items()[$k]['name'] = $name;
                $data->items()[$k]['level'] = count(explode(',', $v['paths']))-2;
                $data->items()[$k]['parent_name'] = $this->parentName($v['pid']);
            }
        }else{
            $data=array();
        }
        $page=$this->getPage($data);
        $this->assign('currentPage',$page['currentPage']);
        $this->assign('total',$page['total']);
        $this->assign('data', $data);
        $this->assign('render', $page['page']);
        return view('node/index');
    }

    public function parentName($pid)//获取父类名称
    {
        if($pid){
            $node=model('Nodes')->get($pid);
            return $node->node_name;
        }
        return '顶级节点';
    }

    public function nodeView()//加载添加节点视图
    {
        return view('node/addNode');
    }

    public function addNode()//添加节点
    {
        $date=input('post.');
        $date['controller_name']=strtolower($date['controller_name']);
        $date['action_name']=strtolower($date['action_name']);
        if($date['pid']){//说明不是顶级节点
            $path=$this->getPath($date['pid']);
            $date['path']=$path.','.$date['pid'];
            $res=Nodes::create($date);
        }else{//要添加顶级节点
            $res=Nodes::create($date);
        }
        if($res){
            $url=popBox('success','添加节点成功');
        }else{
            $url=popBox('error','添加节点失败');
        }
        $this->redirect($url);
    }

    public function editNode(Request $request)//修改节点
    {
        $date=$request->post();
        $res=model('Nodes')->save($date,['id'=>$date['id']]);
        if($res){
            $url=popBox('success','修改节点成功');
        }else{
            $url=popBox('error','修改节点失败');
        }
        $this->redirect($url);
    }

    public function delNode(Request $request)//删除节点
    {
        $date=$request->post();
        $find=model('Nodes')->where(['pid'=>$date['id']])->find();
        if($find){
            return json(['status'=>false,'message'=>'请先去删除其子节点']);
        }else{
            $res=model('Nodes')->where('id',$date['id'])->delete();
            if($res){
                return json(['status'=>true,'message'=>'删除成功']);
            }
            return json(['status'=>false,'message'=>'删除失败']);
        }
    }

    public function getPath($id)
    {
        $node=model('Nodes')->get($id);
        return $node->path;
    }

    public function loop($pid = 0,$space=0, &$result = array())//用于处理分类的公共方法
    {
        $space+=2;
        $data=model('Nodes')->field(['id','pid','node_name'])->where(['pid'=>$pid])->select();
        if($data){
            foreach($data as $v){
                $arr['id']=$v['id'];
                $arr['node_name']=str_repeat('&nbsp;',$space).$v['node_name'];
                $arr['pid']=$v['pid'];
                $result[]=$arr;
                $this->loop($v['id'],$space,$result);
            }
        }
        return $result;
    }

    public function common($data, $pid = 0)//生成树
    {
        $tree = [];
        if ($data && is_array($data)) {
            foreach($data as $v) {
                if($v['pid'] == $pid) {
                    $tree[] = [
                        'id' => $v['id'],
                        'pid' => $v['pid'],
                        'node_name' => $v['node_name'],
                        'children' => self::loop($data, $v['id']),
                    ];
                }
            }
        }
        return $tree;
    }
}
