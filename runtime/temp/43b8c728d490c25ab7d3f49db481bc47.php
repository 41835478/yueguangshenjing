<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:87:"C:\xampp\htdocs\yyyyy\yueguangshenjing\public/../application/home\view\login\login.html";i:1508459387;}*/ ?>
<!DOCTYPE html>
<html>
<head>
<title>越光神镜</title>
<meta charset="utf-8">
<meta name="author" content="yy">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
<meta http-equiv="Content-Type" content="text/html; charset=GBK">
<meta name="author" content="yy">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta name="format-detection" content="telephone=no">
<meta http-equiv="Expires" content="-1">
<meta http-equiv="Cache-Control" content="no-cache">
<meta http-equiv="Pragma" content="no-cache">
<meta name="description" content="" />
<meta name="Keywords" content="" />
<link rel="stylesheet" type="text/css" href="/resource/home/css/swiper.min.css">
<link rel="stylesheet" type="text/css" href="/resource/home/css/public.css">
<link rel="stylesheet" type="text/css" href="/resource/home/font/iconfont.css">
<script type="text/javascript" src="/resource/home/js/jquery-1.10.1.min.js"></script>
<script type="text/javascript" src="/resource/home/js/swiper.min.js"></script>
<script type="text/javascript" src="/resource/home/js/public.js"></script>
<link rel="stylesheet" href="/resource/home/css/main.css">
<style>
	::-webkit-input-placeholder { /* WebKit browsers */  
	    color: #999;  
	}  
	:-moz-placeholder { /* Mozilla Firefox 4 to 18 */  
	   color: #999;  
	   opacity:  1;  
	}  
	::-moz-placeholder { /* Mozilla Firefox 19+ */  
	   color: #999;  
	   opacity:  1;  
	}  
	:-ms-input-placeholder { /* Internet Explorer 10+ */  
	   color: #999;  
	} 
	.login_body ul{
		padding: 20px 9% 4px;
	}
	.go_register{
		text-decoration: underline;
	}
</style>
</head>
<body>
	<div class="login_top">
		<div class="header_login">
			<a href="javascript:void(0);" class="head_left iconfont icon-mjiantou-copy"></a>
			<h3>登录</h3>
			<a href="<?php echo url('Login/register'); ?>" class="head_right">注册</a>
		</div>
	</div>
	<div class="login_body">
		<ul>
			<li class="li">
				<img class="login_icon login_icon1" src="/resource/home/images/ly_36.png" alt="手机号">
				<div class="input_box">
					<input class="text_input phone" type="number" value="<?php echo $user; ?>" placeholder="请输入您的手机号">
				</div>
			</li>
			<li class="li">
				<img class="login_icon" src="/resource/home/images/login_icon3.png" alt="登录密码">
				<div class="input_box">
					<input class="text_input login_pwd1" type="password" value="<?php echo $pwd; ?>" placeholder="请输入您的密码">
				</div>
			</li>
		</ul>
		
		<div class="login_mima">
			<div class="left">
				<i class="iconfont icon-fangxingxuanzhong" date-change="1"></i>
				<span>记住密码</span>
			</div>
			<a class="findPwd" href="<?php echo url('Login/findpassword'); ?>">忘记密码？</a>
		</div>
		<button class="login_btn true">登录</button>
		<p class="regist_xieyi">还没有账号？<a class="go_register" href="<?php echo url('Login/register'); ?>">立即注册</a></p>
	</div>

	<script>
	var flag = true;
	$(".login_mima .left").click(function() {
		if(flag){
			$(this).children('i').removeClass('icon-fangxingxuanzhong').addClass('icon-fangxingweixuanzhong').attr("date-change",'0');
			flag = false;
		}else{
			$(this).children('i').removeClass('icon-fangxingweixuanzhong').addClass('icon-fangxingxuanzhong').attr("date-change",'1');
			flag = true;
		}
	});
 $('.true').click(function(){
            var phone=$('.phone').val();
            var login_pwd1=$('.login_pwd1').val();

            var change = $(".login_mima .left").find('i').attr("date-change");
            
            if(phone==""){
                alert("请输入您的手机号码！");
                return false;
            }
            if(!phone.match(/^1[34578]\d{9}$/)){
                alert('手机号不符合规则！');
                return false;
            }           
            if(login_pwd1==""){
                alert("请输入密码！");
                return false;
            }          
            var data={
                'phone':phone,
                'login_pwd1':login_pwd1, 
                'change':change,            
            }
            var url="<?php echo url('Login/login'); ?>";
            sendAjax(data,url)
        })
        function sendAjax(data,url){
            $.ajax({
                'url':url,
                'data':data,
                'async':true,
                'type':'post',
                'dataType':'jsonp',
                success:function(data){
                	 if(data.status){                				 
                	 		if(data.status==200){
                	 			alert(data.message); 
                	 			window.location.href="/";
                	 		}
                	 		if(data.status==401){
                	 			alert(data.message);
                	 			window.location.reload();                 	 			
                	 		}                 	                           
                        }else{
                        	alert(data.message);
                        	window.location.reload();
                        }                  
                },
             
            })
        }
	

	</script>
</body>
</html>