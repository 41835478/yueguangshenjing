<?php

namespace app\admin\controller;

use app\admin\model\AccountRecordModel;
use app\admin\model\RearviewModel;
use app\admin\model\User;
use Service\AccountRecord;
use think\Controller;
use think\Request;

class Users extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index(Request $request)
    {

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
        $data=$user->paginate(config("page"),false, [
            'query' => Request::instance()->param(),//不丢失已存在的url参数
        ]);
        foreach ($data as $v){
            $v['pid'] = User::get($v['pid'])["mobile"];
            $v["level"] = config('level')[$v["level"]];
            $v["created_at"] = date("Y-m-d H:i:s",$v["created_at"]);
        }

        $page=$this->getPage($data);

        return $this->fetch("users/index",["currentPage"=>$page['currentPage'],
            "total"=>$page['total'], "data"=>$data,"render"=>$page['page']]);
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

    public function recharge()
    {
        $input = Request::instance()->only("is_add,recharge_id,num");
        $user = User::get($input["recharge_id"]);
        $account = new AccountRecord();

        if($input["is_add"] == "1"){
            $user->setInc("account",$input["num"]);
            $account->setAccountRecord($input["recharge_id"],"系统充值",
                AccountRecordModel::TYPE_EIGHT,1,$input["num"]);

            $Url = popBox("success","系统充值成功");
            $this->redirect($Url);
        }else{
            $user->setDec("account",$input["num"]);

            $account->setAccountRecord($input["recharge_id"],"系统扣款",
                AccountRecordModel::TYPE_EIGHT,2,$input["num"]);

            $Url = popBox("success","系统扣款成功");
            $this->redirect($Url);

        }
    }

    public function lock()
    {
        $input = Request::instance()->only("id,flag");
        $user = User::get($input["id"]);
        if($input["flag"] == 1){
            $ob = $user->save(["status"=>2],["id"=>$input["id"]]);
            if($ob){
                return json(["status"=>0,"msg"=>"解锁定"]);
            }
        }else if($input["flag"] == 2){
            $ob = $user->save(["status"=>1],["id"=>$input["id"]]);
            if($ob){
                return json(["status"=>0,"msg"=>"锁定"]);
            }
        }
    }
    public function pwdreset(){
        $input = Request::instance()->only("id");
        $user = User::get($input["id"]);

        #生成随机密码
        $randpwd='';
        for ($i = 0; $i < 5; $i++)
        {
            $randpwd .= chr(mt_rand(97, 122));
        }

        $password=md5($user->mobile.$randpwd);
        $user->save(['login_pwd'=>$password],["id"=>$input["id"]]);

        return json(['status'=>0,'msg'=>'重置成功']);
    }
    //下级
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
                $str .= '<td>'.$downU.'</td>';
                $str .= '</tr>';
            }
            $str .= '</table>';
            return json(["data"=>$str,"user"=>$user,"num"=>$childenNum]);
        }else{
            return json(["message"=>"没有下级了"]);
        }
    }
    //单个查询下级
    public function downU($id){
        $user = User::get($id);
        $userList = User::where(["pid"=>$user->id])->find();
        if($userList){
            return "n";
        }
    }
    //获取用户等级
    public function userOne($id){
        $user = User::get($id);
        $userList = User::where(["id"=>$user->id])->find();
        return json(["status"=>0,"data"=>$userList]);
    }

    //修改用户等级
    public function levelEdit(){
        $input = Request::instance()->only("id,level,area");
        if($input['level'] == 3 ||$input['level'] == 4 ||$input['level'] == 5 ||$input['level'] == 6){
            if(empty($input["area"])){
                return json(["status"=>100,"msg"=>"请填写代理商地区"]);
            }

            $userOb = User::where(["area"=>$input["area"]])->find();
            if($userOb){
                return json(["status"=>100,"msg"=>"该地区已有代理商,请重新选择!"]);
            }

            $rearview = new RearviewModel();
//            $rearview->data(["uid"=>$input['id'],"stock"=>""]);

        }

        $user = User::get($input['id']);
        $user->save($input,["id"=>$input["id"]]);

        return json(["status"=>0,"data"=>config('level')[$user->level]]);
    }

}
