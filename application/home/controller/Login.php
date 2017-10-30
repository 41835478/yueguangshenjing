<?php
namespace app\home\controller;
use think\Controller;
use app\admin\model\User;
use think\Cache;
use think\View;
use think\Session;
use think\Cookie;
class Login extends Controller
{
    
    public function _initialize()
    {
        parent::_initialize();
        $this->user = new User;  
    }

    public function register()
    {
		$post=input('param.');
		if(request()->isPost()){
			 #判断手机号
		    if (preg_match("/^(13|15)d{9}$/",$post['phone'])) 
		    { 
		       	return jsonp(['status'=>401,'message'=>'手机验证码不符合规则']);
		    }

           
            
		    $user=db('user')->where('mobile',$post['phone'])->find();
		    if($user){
		    	return jsonp(['status'=>401,'message'=>'该手机号已经注册']);
		    }
		    if ($post['login_pwd1'] !=$post['login_pwd2']) 
		    { 
		       	return jsonp(['status'=>401,'message'=>'两次密码不一致']);
		    }

            if(strlen(trim($post['login_pwd1'])) < 6 || strlen(trim($post['login_pwd1'])) > 20){
                return jsonp(['status'=>401,'message'=>'请填写6位到20位登录密码']);
            } 
		    #判断验证码
		    $code=Cache::get('yzm');
		    if($post['yzm']!=$code['yzm'] || $post['phone']!=$code['phone'] ){
		    	return jsonp(['status'=>401,'message'=>'手机验证码有误']);
		    }
            if(strlen(trim($post['pay_pwd'])) != 6){
                return jsonp(['status'=>401,'message'=>'请填写6位支付密码']);
            }
		    #生成随机密码
		    $randpwd='';
		    for ($i = 0; $i < 5; $i++) 
			{ 
			$randpwd .= chr(mt_rand(97, 122)); 
			}
			$password=md5(trim($post['login_pwd1']).$randpwd);
			#组合数据
			$data=[];
             if($post['uid'] != 0){
                $data['pid']=$post['uid'];
            }            
			$data['mobile']=$post['phone'];
			$data['unique']=$randpwd;
			$data['login_pwd']=$password;
			$data['pay_pwd']=md5(trim($post['pay_pwd']));
			$data['created_at']=time();
			$data['updated_at']=time();
			$res=db('user')->insert($data);			
    		if($res){
    			return jsonp(['status'=>200,'message'=>'注册成功']);
    		}else{
    			return jsonp(['status'=>401,'message'=>'注册失败,请稍后再试']);
    		}			
		}
        if(input('param.uid')){
            $uid=input('param.uid');
        }else{
            $uid=0;
        }
        $this->assign('uid',$uid);
		return view();
    }
      #登录
    public function login()
    {
        $post=input('param.');
        if(request()->isPost())
        {
            $user=db('user')->where('mobile',$post['phone'])->find();
            if(!$user){
                return jsonp(['status'=>401,'message'=>'用户不存在']);
            }
            //dump($post['login_pwd1']);die;
           $password=md5($post['login_pwd1'].$user['unique']);
            #判断是否存在Cookie 
            if(Cookie::has('username') && Cookie::has('password')){
            	$random='ygsj';
            	$password=md5(self::encrypt($post['login_pwd1'], 'D', $random).$user['unique']);
            	$num=1;
            	if($user['login_pwd'] != $password){
            		$password=md5($post['login_pwd1'].$user['unique']);
            		$num=2;

            	}
            }

            if($user['login_pwd'] == $password){
                Session::set('uid',$user['id']);
                if($post['change']==1){               	
                    Cookie::set('username',$post['phone'],3600 * 24 *30);                   
                    #开始加密
                    $random='ygsj';

                    $pass=self::encrypt($post['login_pwd1'], 'E', $random);
                    #不通过cookie进入,就进行加密
                    if($num==2) {
                    	Cookie::set('password',$pass,3600 * 24 *30);
                    }                  
                                  
                }elseif($post['change']==0){
                    Cookie::delete('username');
                    Cookie::delete('password');
                }
                     
                return jsonp(['status'=>200,'message'=>'登录成功']);
            }else{
                return jsonp(['status'=>401,'message'=>'账户或密码错误']);
            }

        }
        $user='';
        $pwd='';


        if(Cookie::has('username') && Cookie::has('password')){
            $user=Cookie::get('username');
            $pwd=Cookie::get('password'); 

        }

        $this->assign('user',$user);
        $this->assign('pwd',$pwd);
        return view();
    }

    #加密规则
    static public   function encrypt($string,$operation,$key=''){
			   	$key=md5($key); 
			    $key_length=strlen($key); 
			    $string=$operation=='D'?base64_decode($string):substr(md5($string.$key),0,8).$string; 
			     $string_length=strlen($string); 
			     $rndkey=$box=array(); 
			     $result=''; 
			     for($i=0;$i<=255;$i++){ 
			            $rndkey[$i]=ord($key[$i%$key_length]); 
			         $box[$i]=$i; 
			     } 
			    for($j=$i=0;$i<256;$i++){ 
			         $j=($j+$box[$i]+$rndkey[$i])%256; 
			         $tmp=$box[$i]; 
			         $box[$i]=$box[$j]; 
			         $box[$j]=$tmp; 
			     } 
			    for($a=$j=$i=0;$i<$string_length;$i++){ 
				         $a=($a+1)%256; 
				         $j=($j+$box[$a])%256; 
				         $tmp=$box[$a]; 
				         $box[$a]=$box[$j]; 
				         $box[$j]=$tmp; 
				         $result.=chr(ord($string[$i])^($box[($box[$a]+$box[$j])%256])); 
				     } 
			    if($operation=='D'){ 
			         if(substr($result,0,8)==substr(md5(substr($result,8).$key),0,8)){ 
			             return substr($result,8); 
		        }else{ 
			             return''; 
			         } 
			    }else{ 
			         return str_replace('=','',base64_encode($result)); 
			    } 
    }

    #忘记密码
    public function findpassword()
    {
    	$post=input('param.');
    	if(request()->isPost())
    	{
    		if (preg_match("/^(13|15)d{9}$/",$post['phone'])) 
		    { 
		       	return jsonp(['status'=>401,'message'=>'手机验证码不符合规则']);
		    }
		    $user=db('user')->where('mobile',$post['phone'])->find();
		    if(!$user){
		    	return jsonp(['status'=>401,'message'=>'用户不存在']);
		    }
		    if ($post['login_pwd1'] !=$post['login_pwd2']) 
		    { 
		       	return jsonp(['status'=>401,'message'=>'两次密码不一致']);
		    } 
		    #判断验证码
		    $code=Cache::get('yzm');
		    if($post['yzm']!=$code['yzm'] || $post['phone']!=$code['phone'] ){
		    	return jsonp(['status'=>401,'message'=>'手机验证码有误']);
		    }
		    #生成随机密码
		    $randpwd='';
		    for ($i = 0; $i < 5; $i++) 
			{ 
			$randpwd .= chr(mt_rand(97, 122)); 
			}
			$password=md5($post['login_pwd1'].$randpwd);
			$data=[];
			$data['unique']=$randpwd;
			$data['login_pwd']=$password;
			$data['updated_at']=time();
			$res=$this->user->where('mobile',$user['mobile'])->update($data);
			if($res){
				return jsonp(['status'=>200,'message'=>'修改成功请重新登录']);
			}else{
				return jsonp(['status'=>401,'message'=>'修改失败请重试']);
			}
    	}
    	 

    	return $this->fetch();

    }

    #注册时发送验证码
    public function registercode(){
        $post=input('param.');
        $user=User::where('mobile',$post['phone'])->find();
        if($user){
            return jsonp(['status'=>401,'message'=>'该手机号已经注册过,您可以去登录或找回密码']);
        }
        $countnum=db('config')->where('name','codenum')->value('value');
        #今天时间戳
        $today=strtotime(date('Y-m-d'));
        #明天时间戳
        //$tomorrow=strtotime(date('Y-m-d',strtotime('+1 day')));
        $count=db('coderecord')->where(['phone'=>$post['phone']])->where('created_at','>',$today)->count();
        if($count >= $countnum){
            return jsonp(['status'=>401,'message'=>'今天发送的短信太多了,明天再试吧']);
        }
        $res=self::sendMsg($post['phone']);
            #发送成功插入一条数据
            $data=[];
            $data['phone']=$post['phone'];
            $data['created_at']=time();
            $data['updated_at']=time();
            db('coderecord')->insert($data);
        if($res){
            return jsonp(['status'=>200,'message'=>'发送成功,验证码十分钟内有效']);
         }else{
            return jsonp(['status'=>401,'message'=>'发送失败,请稍后再试']);
         } 


    }
    #发送验证码
    public function sendyamcode()
    {
        $post=input('param.');
        $countnum=db('config')->where('name','codenum')->value('value');
        #今天时间戳
        $today=strtotime(date('Y-m-d'));
        #明天时间戳
        //$tomorrow=strtotime(date('Y-m-d',strtotime('+1 day')));
        $count=db('coderecord')->where(['phone'=>$post['phone']])->where('created_at','>',$today)->count();
        if($count >= $countnum){
            return jsonp(['status'=>401,'message'=>'今天发送的短信太多了,明天再试吧']);
        }
        $res=self::sendMsg($post['phone']);
            #发送成功插入一条数据
            $data=[];
            $data['phone']=$post['phone'];
            $data['created_at']=time();
            $data['updated_at']=time();
            db('coderecord')->insert($data);
    	if($res){
            return jsonp(['status'=>200,'message'=>'发送成功,验证码十分钟内有效']);
         }else{
            return jsonp(['status'=>401,'message'=>'发送失败,请稍后再试']);
         }    	
    }
    #注册协议页面
    public function registerprotocol(){
    	return $this->fetch();
    }



/***验证码功能********************************************/

    #发送验证码
    private function sendMsg($mobile)//发送验证码
    {
        $username = "YGSJ";
        $pwd = "oP1iU7fB";
        $password = md5($username.md5($pwd));
        $code=self::greatRand();
        $content = "您的验证码是".$code."，请在10分钟内填写，切勿将验证码泄露于他人。【越光神镜】";
        $url = "http://120.55.248.18/smsSend.do?";
        $data=array('username'=>$username,'password'=>$password,'mobile'=>$mobile,'content'=>urlencode($content));
        $param=self::getSign($data);
        $result=self::http_curl($url,'post','json',$param);
        if($result){
            $data=[];
            $data['yzm']=$code;
            $data['phone']=$mobile;
            Cache::set('yzm',$data,600);
            return true;
        }else{
            return false;
        }
    }

    //生成随机数
    private function greatRand(){
        $str = '1234567890';
        $result = '';
        for($i=0;$i<6;$i++){
            $result .= $str{rand(0,strlen($str)-1)};
        }
        return $result;
    }

    //生成签名
    private function getSign($data){
        $result = '';
        $i = 0;
        foreach($data as $k=>$v){
            $i++;
            if($i!=1){
                $result .= '&';
            }
            $result .= $k . '=' . $v;
        }

        return $result;
    }

    /*
     * $url 接口url
     * $type 请求类型 string
     * $res 返回数据类型 string
     * $arr post的请求参数 string*/
    private function http_curl($url, $type = 'get', $res = 'json', $arr = '')
    {
        //1、初始化curl
        $ch = curl_init();
        //2、设置curl的参数
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//将抓取到的东西返回
        if ($type == 'post') {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $arr);// 传递一个作为HTTP “POST”操作的所有数据的字符串。
        }
        //3、采集
        $output = curl_exec($ch);

        if ($res == 'json') {
            if (curl_errno($ch)) {
                //请求失败，返回错误信息
                $str=curl_error($ch);
                //4、关闭
                curl_close($ch);
                return $str;
            } else {
                //请求成功
                $json=json_decode($output, true);
                //4、关闭
                curl_close($ch);
                return $json;
            }
        }
    }


}
