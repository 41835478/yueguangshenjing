<?php

namespace app\agent\controller;

use app\admin\model\RearviewModel;
use app\admin\model\RearviewRecordModel;
//use app\agent\model\User;
use app\admin\model\User;
use think\Controller;
use think\Request;
use think\Session;

class Users extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index(Request $request)
    {
        $uid = Session::get('id','agent');
        $user = new User();
        if($request->has('mobile','param',true)){
            $user->where('mobile','like','%'.$request->param('mobile').'%');
        }
        if($request->has('nickname','param',true)){
            $user->where('nickname','like','%'.$request->param('nickname').'%');
        }
        if($request->has('from_phone','param',true)){#推荐人手机号
            $fromUser = $user->where(["mobile"=>$request->param('from_phone')])->column("id");
            $where["pid"] = ["IN",$fromUser];
            $user->where($where);
        }

        if($request->has('level','param',true)){
            $user->where(['level'=>$request->param('level')]);
        }
        if($request->has('start','param',true)){#开始日期
            $user->where('created_at','>=',$request->input('start'));
        }
        if($request->has('end','param',true)){#结束日期
            $user->where('created_at','<=',$request->input('end'));
        }
        $data=$user->where('level','in',[1,2,7,8])->where("pid",$uid)
            ->paginate(config("page"),false, [
            'query' => Request::instance()->param(),//不丢失已存在的url参数
        ]);
        foreach ($data as $v){
            $v['pid'] = User::get($v['pid'])["mobile"];
            $v["created_at"] = date("Y-m-d H:i:s",$v["created_at"]);
            $v["level"] = config('level')[$v["level"]];
        }

        $page=$this->getPage($data);

        return $this->fetch("users/index",["currentPage"=>$page['currentPage'],
            "total"=>$page['total'], "users"=>$data,"render"=>$page['page']]);
    }
    public function getPage($object)//分页
    {
        if($object){
            $pages['currentPage']= $object->currentPage();
            $pages['total'] = $object->total();
            $pages['page'] = $object->render();
        }else{
            $pages['currentPage']=$pages['total']=$pages['page']=0;
        }
        return $pages;
    }
    public function suborDinate($id){
        $user = User::get($id);
        $childen = User::where("pid",$id)->select();
        if($childen){
            $childenNum = count($childen);
            $str = '<table class="table table-hover table-striped">';
            $str .= '<th>ID</th>';
            $str .= '<th>推荐人</th>';
            $str .= '<th>会员昵称</th>';
            $str .= '<th>会员手机号</th>';
            $str .= '<th>会员身份</th>';
            $str .= '<th>会员注册时间</th>';
            $str .= '<th>会员余额</th>';
            $str .= '<th>操作</th>';
            foreach($childen as $key=>$vo){
                if($this->downU($vo['id']) == "n"){
                    $downU = ">";
                }else{
                    $downU = " ";
                }
                $str .= "<tr style='cursor:pointer' onclick='childen({$vo['id']})'>";
                $str .= "<td><a id='user_name' href='javascript:void(0)'>".$vo['id'].'</a></td>';
                $str .= '<td>'.User::get($vo['pid'])["mobile"].'</td>';
                $str .= '<td>'.$vo['name'].'</td>';
                $str .= '<td>'.$vo['mobile'].'</td>';
                $str .= '<td>'.config('level')[$vo['level']].'</td>';
                $str .= '<td>'.date("Y-m-d H:i:s",$vo['created_at']).'</td>';
                $str .= '<td>'.$vo['account'].'</td>';
                $str .= "<td data-toggle='modal' data-target='#myModal5'onclick='editLevel({$vo['id']})'>修改店面</td>";
                $str .= '<td>'.$downU.'</td>';
                $str .= '</tr>';
            }
            $str .= '</table>';
            return json(["data"=>$str,"user"=>$user,"num"=>$childenNum]);
        }else{
            return json(["message"=>"没有下级了"]);
        }
    }

    //获取用户等级
    public function userOne($id){
        $user = User::get($id);
        $userList = User::where(["id"=>$user->id])->find();
        return json(["status"=>0,"data"=>$userList]);
    }
    //单个查询下级
    public function downU($id){
        $user = User::get($id);
        $userList = User::where(["pid"=>$user->id])->find();
        if($userList){
            return "n";
        }
    }
    public function editStorefront(){
        $input = Request::instance()->only("id,level,shop_name");
        $user = User::get($input["id"]);
        $uid = Session::get('id','agent');

        if($input['level'] == 7 ||$input['level'] == 8 ){
            if($user->agency_id != ""){
                $userOne = User::get($user->agency_id);
                if($user->agency_id != $uid){
                    return json(["status"=>100,"msg"=>"该店面已被用户".($user->agency_id == '0' ?"系统":$userOne->mobile)."指定"]);
                }
            }
            $input["agency_id"] = $uid;
        }


        $user->save($input,["id"=>$input["id"]]);
        return json(["status"=>0]);
    }
    #出货明细
    public function rearview(){
        $uid = Session::get('id','agent');

        $rearview = RearviewModel::where(["uid"=>$uid])->find();#库存
        $rearviewRecord = new RearviewRecordModel();
        $data = $rearviewRecord->where(["uid"=>$uid])->paginate(config("page"),false, [
            'query' => Request::instance()->param(),//不丢失已存在的url参数
        ]);
        $page=$this->getPage($data);
        return $this->fetch("users/rearview",["currentPage"=>$page['currentPage'],
            "rearview"=>$rearview,"total"=>$page['total'], "data"=>$data,"render"=>$page['page']]);
    }
}
