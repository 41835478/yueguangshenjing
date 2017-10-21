<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:86:"C:\xampp\htdocs\yyyyy\yueguangshenjing\public/../application/admin\view\main\base.html";i:1508331166;s:90:"C:\xampp\htdocs\yyyyy\yueguangshenjing\public/../application/admin\view\public\header.html";i:1508331166;s:88:"C:\xampp\htdocs\yyyyy\yueguangshenjing\public/../application/admin\view\public\foot.html";i:1508555543;}*/ ?>
<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">


    <title>后台首页</title>
    <meta name="keywords" content="">
    <meta name="description" content="">

    <link rel="shortcut icon" href="favicon.ico"> <link href="__PUBLIC__/admin/css/bootstrap.min.css?v=3.3.6" rel="stylesheet">
<link href="__PUBLIC__/admin/css/font-awesome.min.css?v=4.4.0" rel="stylesheet">
<link href="__PUBLIC__/admin/css/animate.css" rel="stylesheet">
<link href="__PUBLIC__/admin/css/style.css?v=4.1.0" rel="stylesheet">

</head>

<body class="gray-bg">
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-sm-8">
        <h2>后台首页</h2>
        <ol class="breadcrumb">
            <li>
                <a href="JavaScript:;">
                    亲爱的 <font color="red"><?php echo $res['name']; ?></font> <?php echo $time; ?>
                </a>
            </li>
        </ol>
        <div style="height:20px"></div>
        <p style="margin-left: 100px;">
            您当前登陆时间：<?php echo date("Y-m-d H:i",$res['current_time']); ?>&nbsp;&nbsp;&nbsp;&nbsp;
            您上次登陆时间：<?php echo date("Y-m-d H:i",$res['last_time']); ?>&nbsp;&nbsp;&nbsp;&nbsp;
            您当前登陆IP：<?php echo $res['current_ip']; ?>&nbsp;&nbsp;&nbsp;&nbsp;
            您上次登陆IP：<?php echo $res['last_ip']; ?>
        <p>
    </div>
</div>

<!-- 全局js -->
<script src="__PUBLIC__/admin/js/jquery.min.js?v=2.1.4"></script>
<script src="__PUBLIC__/admin/js/bootstrap.min.js?v=3.3.6"></script>
<script src="__PUBLIC__/admin/js/plugins/metisMenu/jquery.metisMenu.js"></script>
<script src="__PUBLIC__/admin/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>
<script src="__PUBLIC__/admin/js/plugins/layer/layer.min.js"></script>
<script src="__PUBLIC__/admin/js/plugins/dataTables/jquery.dataTables.js"></script>
<script src="__PUBLIC__/admin/js/plugins/sparkline/jquery.sparkline.min.js"></script>
<script src="__PUBLIC__/admin/layui-2.0/layui.js"></script>

<script type="text/javascript" charset="utf-8" src="__PUBLIC__/ueditor/ueditor.config.js"></script>
<script type="text/javascript" charset="utf-8" src="__PUBLIC__/ueditor/ueditor.all.min.js"> </script>
<script type="text/javascript" charset="utf-8" src="__PUBLIC__/ueditor/lang/zh-cn/zh-cn.js"></script>

<!-- 自定义js -->
<script src="__PUBLIC__/admin/js/content.js?v=1.0.0"></script>
</body>
</html>
