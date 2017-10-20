<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:87:"C:\xampp\htdocs\yyyyy\yueguangshenjing\public/../application/home\view\index\index.html";i:1508493646;}*/ ?>
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
<link rel="stylesheet" type="text/css" href="__CSS__/swiper.min.css">
<link rel="stylesheet" type="text/css" href="__CSS__/public.css">
<link rel="stylesheet" type="text/css" href="__FONTS__/iconfont.css">
<script type="text/javascript" src="__JS__/jquery-1.10.1.min.js"></script>
<script type="text/javascript" src="__JS__/swiper.min.js"></script>
<script type="text/javascript" src="__JS__/public.js"></script>
<link rel="stylesheet" href="__CSS__/main.css">
<style type="text/css">
	/* body{ background: #f5f5f5; } */
</style>
</head>
<body>
	<div class="header_banner">
		<div class="swiper-container">
	        <div class="swiper-wrapper">
				<?php foreach($content["banner"] as $v): ?>
	            <div class="swiper-slide">
	            	<a class="a_jump" href="<?php echo $v['links']; ?>">
	            		<img class="content-banner" src="<?php echo $v['img']; ?>" />
	        		</a>
	        	</div>
				<?php endforeach; ?>
	        </div>
	        <!-- 如果需要分页器 -->
	        <div class="swiper-pagination"></div>
			<img class="banner_btm_img" src="__IMG__/index_hut.png" alt="">
	    </div>
	</div>

	<div class="header_nav">
		<ul>
			<li class="nav_li">
				<a href="<?php echo url('content/index'); ?>">
					<img class="img" src="__IMG__/about.png" alt="平台介绍">
					<p>平台介绍</p>
				</a>
			</li>
			<li class="nav_li">
				<a href="<?php echo url('content/beginnerGuide'); ?>">
					<img class="img" src="__IMG__/guide.png" alt="新手指南">
					<p>新手指南</p>
				</a>
			</li>
			<li class="nav_li gonggao_li">
				<a href="<?php echo url('content/notice'); ?>">
					<img class="img" src="__IMG__/news.png" alt="最新公告">
					<p>最新公告</p>

					<span class="msg_span">1</span>
				</a>
			</li>
			<li class="nav_li">
				<a href="<?php echo url('content/kefu'); ?>">
					<img class="img" src="__IMG__/kefu.png" alt="客服中心">
					<p>客服中心</p>
				</a>
			</li>
		</ul>
	</div>

	<div class="index_body">
		<div class="body_1">
			<div class="index_tit index_tit1">
				<div class="titbg">产品展示</div>
				<img src="__IMG__/index_hub.png" alt="" class="hu">
			</div>
			<div class="show_div">
				<img class="goods_img" src="__IMG__/index_1.png" alt="">
				<div class="right">
					<p class="p1">越光神镜智能后视镜</p>
					<p class="p2">至尊高速4G版</p>
					<p class="price">单价：<span>2880</span>元</p>
					<a class="go_a" href="goodsDetails.html">点击进入</a>
				</div>
			</div>
		</div>
		
		<div class="body_2">
			<div class="index_tit index_tit1">
				<div class="titbg">视频直播入口</div>
				<img src="__IMG__/index_hut.png" alt="" class="hu">
			</div>
			<div class="show_div">
				<img class="goods_img" src="__IMG__/index_2.png" alt="">
				<p class="jieshao_p">越光神镜公司介绍、产品宣传、功能演示等更多精彩视屏</p>
				<div class="seemore_div">
					<a href="videoList.html">
						<img src="__IMG__/index_3.png" alt="">
						<p>点击进入查看更多视频</p>
					</a>
				</div>
			</div>
		</div>

		<div class="index_jishu">
			<div class="xian_div"></div>
			<p class="jishu_p">技术支持：<a href="http://jzg.zzjbs.com">金帮手</a></p>
			<div class="xian_div"></div>
		</div>
	</div>
	
	<div class="footer">
		<ul>
			<li class="footer_li">
				<a href="index.html">
					<img src="__IMG__/ly_32.png" alt="">
					<p class="p_on">首页</p>
				</a>
			</li>
			<li class="footer_li QR_li">
				<a href="javascript:void(0);">
					<img src="__IMG__/ly_33.png" alt="">
					<p>二维码</p>
				</a>
			</li>
			<li class="footer_li">
				<a href="my.html">
					<img src="__IMG__/ly_35.png" alt="">
					<p>我的</p>
				</a>
			</li>
		</ul>
	</div>
	

<!-- tan -->
<div class="popBox" style="display:none;">
	<div class="pop">
		<p class="p_top">提示</p>
		<p class="p_cot">需成为消费会员后，才能查看个人专属二维码。</p>
		<div class="btnBox">
			<button class="btn1" type="button">忽略</button>
			<button class="btn2" type="button">确定</button>
		</div>
	</div>
</div>

<script>
	'use strict';
	/*Shuffling figure*/
	$(function () {
	    var mySwiper = new Swiper ('.swiper-container',{
	        direction: 'horizontal',
	        loop: true,
	        autoplay : 4000,
	        pagination: '.swiper-pagination',
	        autoplayDisableOnInteraction : false,
	        observer:true,
	        observerParents:true
	    }); 

	    // $(".QR_li").click(function() {
	    // 	$(".popBox").fadeIn('fast');
	    // });
	    // $(".popBox .btn1").click(function() {
	    // 	$(".popBox").fadeOut('fast');
	    // });


	    // $(".popBox .btn2").click(function() {
	    // 	window.location.href = 'QRcode.html';
	    // });
	});
</script>
</body>
</html>