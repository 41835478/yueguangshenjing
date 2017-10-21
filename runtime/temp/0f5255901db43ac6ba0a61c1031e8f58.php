<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:90:"C:\xampp\htdocs\yyyyy\yueguangshenjing\public/../application/home\view\content\notice.html";i:1508548094;}*/ ?>
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
<style>
	body{
		background-color:#f5f5f5;
	}
</style>
</head>
<body>
<div class="public_head">
	<h3>最新公告</h3>
	<a href="javascript:history.go(-1);" class="iconfont icon-mjiantou-copy"></a>
</div>
<!-- 内容区 -->
<div class="content">
	<div class="Notice_body">
		<ul>
			<?php foreach($notice as $v): ?>
			<li class="li">
				<a href="<?php echo url('content/noticeDetails'); ?>?id=<?php echo $v['id']; ?>">
					<span><?php echo $v['title']; ?></span>
					<i class="iconfont icon-arrow-right"></i>
				</a>
				<p class="content"><?php echo $v['content']; ?></p>
				<p class="time"><?php echo $v['created_at']; ?></p>
			</li>
			<?php endforeach; ?>
		</ul>

		<p class="btm_p">已经到底了... ...</p>
	</div>
</div>

<script>
	'use strict';
	/*Shuffling figure*/
	$(function () {
		$(".Notice_body .li:last").css('margin','0');
	});
</script>
</body>
</html>