<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:87:"C:\xampp\htdocs\yyyyy\yueguangshenjing\public/../application/admin\view\main\index.html";i:1508554839;s:90:"C:\xampp\htdocs\yyyyy\yueguangshenjing\public/../application/admin\view\public\header.html";i:1508331166;s:88:"C:\xampp\htdocs\yyyyy\yueguangshenjing\public/../application/admin\view\public\foot.html";i:1508555543;}*/ ?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="renderer" content="webkit">

    <title>越光神镜后台</title>

    <meta name="keywords" content="">
    <meta name="description" content="">

    <!--[if lt IE 9]>
    <meta http-equiv="refresh" content="0;ie.html" />
    <![endif]-->

    <link rel="shortcut icon" href="favicon.ico"> <link href="__PUBLIC__/admin/css/bootstrap.min.css?v=3.3.6" rel="stylesheet">
<link href="__PUBLIC__/admin/css/font-awesome.min.css?v=4.4.0" rel="stylesheet">
<link href="__PUBLIC__/admin/css/animate.css" rel="stylesheet">
<link href="__PUBLIC__/admin/css/style.css?v=4.1.0" rel="stylesheet">
</head>

<body class="fixed-sidebar full-height-layout gray-bg" style="overflow:hidden">
<div id="wrapper">
    <!--左侧导航开始-->
    <nav class="navbar-default navbar-static-side" role="navigation">
        <div class="nav-close"><i class="fa fa-times-circle"></i>
        </div>
        <div class="sidebar-collapse">
            <ul class="nav" id="side-menu">
                <li class="nav-header">
                    <div class="dropdown profile-element">
                        <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                                <span class="clear">
                                    <span class="block m-t-xs" style="font-size:20px;">
                                        <?php if($_SESSION['admin']['pic'] != ''): ?>
                                        <img src="__PUBLIC__<?php echo $_SESSION['admin']['pic']; ?>" style="border-radius:40px" width="80px" height="80px">
                                        <?php else: ?>
                                        <img src="__PUBLIC__/admin/headPic/a5.jpg" style="border-radius:40px" width="80px" height="80px">
                                        <?php endif; ?>
                                    </span>
                                </span>
                        </a>
                    </div>
                    <div class="logo-element">后台
                    </div>
                </li>
                <li class="hidden-folded padder m-t m-b-sm text-muted text-xs">
                    <span class="ng-scope">分类</span>
                </li>
                <li>
                    <a class="J_menuItem" href="<?php echo url('main/base'); ?>">
                        <i class="fa fa-home"></i>
                        <span class="nav-label">主页</span>
                    </a>
                </li>
                <li class="hidden-folded padder m-t m-b-sm text-muted text-xs">
                    <span class="ng-scope">分类</span>
                </li>
                <li>
                    <a href="javascript:;">
                        <i class="fa fa fa-bar-chart-o"></i>
                        <span class="nav-label">权限管理</span>
                        <span class="fa arrow"></span>
                    </a>
                    <ul class="nav nav-second-level">
                        <li>
                            <a class="J_menuItem" href="<?php echo url('role/index'); ?>">角色管理</a>
                        </li>
                        <li>
                            <a class="J_menuItem" href="<?php echo url('node/index'); ?>">节点管理</a>
                        </li>
                        <li>
                            <a class="J_menuItem" href="<?php echo url('administrator/index'); ?>">管理员管理</a>
                        </li>
                    </ul>
                </li>
                <li class="line dk"></li>
                <li class="hidden-folded padder m-t m-b-sm text-muted text-xs">
                    <span class="ng-scope">分类</span>
                </li>
                <li>
                    <a href="javascript:;">
                        <i class="fa fa fa-bar-chart-o"></i>
                        <span class="nav-label">管理员管理</span>
                        <span class="fa arrow"></span>
                    </a>
                    <ul class="nav nav-second-level">
                        <li>
                            <a class="J_menuItem" href="<?php echo url('member/index'); ?>">修改管理员信息</a>
                        </li>
                        <li>
                            <a class="J_menuItem" href="<?php echo url('member/pwd'); ?>">修改管理员密码</a>
                        </li>
                    </ul>
                </li>
                <li class="line dk"></li>
                <li class="hidden-folded padder m-t m-b-sm text-muted text-xs">
                    <span class="ng-scope">分类</span>
                </li>
                <li>
                    <a class="J_menuItem" href="<?php echo url('statistics/index'); ?>"><i class="fa fa fa-bar-chart-o"></i> <span class="nav-label">数据统计</span></a>
                </li>
                <li class="hidden-folded padder m-t m-b-sm text-muted text-xs">
                    <span class="ng-scope">分类</span>
                </li>
                <li>
                    <a href="javascript:;"><i class="fa fa-envelope"></i> <span class="nav-label">首页轮播图</span><span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <li><a class="J_menuItem" href="<?php echo url('banners/index'); ?>">轮播图列表</a>
                        </li>
                        <li><a class="J_menuItem" href="<?php echo url('banners/create'); ?>">添加轮播图</a>
                        </li>
                    </ul>
                </li>
                <li class="hidden-folded padder m-t m-b-sm text-muted text-xs">
                    <span class="ng-scope">分类</span>
                </li>
                <li>
                    <a href="javascript:;"><i class="fa fa-envelope"></i> <span class="nav-label">内容管理</span><span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <li><a class="J_menuItem" href="<?php echo url('contents/index'); ?>?type=1">平台介绍</a>
                        </li>
                        <li><a class="J_menuItem" href="<?php echo url('contents/index'); ?>?type=2">新手指南</a>
                        </li>
                        <li><a class="J_menuItem" href="<?php echo url('contents/index'); ?>?type=3">最新公告</a>
                        </li>
                        <li><a class="J_menuItem" href="<?php echo url('contents/index'); ?>?type=4">客服中心</a>
                        </li>
                    </ul>
                </li>
                <li class="hidden-folded padder m-t m-b-sm text-muted text-xs">
                    <span class="ng-scope">分类</span>
                </li>
                <li>
                    <a href="javascript:;"><i class="fa fa-envelope"></i> <span class="nav-label">商品管理</span><span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <li><a class="J_menuItem" href="<?php echo url('goods/index'); ?>">添加商品</a>
                        </li>
                        <li><a class="J_menuItem" href="<?php echo url('goods/goodsList'); ?>">商品列表</a>
                        </li>
                    </ul>
                </li>

                <li class="hidden-folded padder m-t m-b-sm text-muted text-xs">
                    <span class="ng-scope">分类</span>
                </li>
                <li>
                    <a class="J_menuItem" href="<?php echo url('configs/index'); ?>"><i class="fa fa-user"></i> <span class="nav-label">参数设置</span></a>
                </li>
                <!--<li class="hidden-folded padder m-t m-b-sm text-muted text-xs">-->
                <!--<span class="ng-scope">分类</span>-->
                <!--</li>-->
                <!--<li>-->
                <!--<a href="#"><i class="fa fa-flask"></i> <span class="nav-label">UI元素</span><span class="fa arrow"></span></a>-->
                <!--<ul class="nav nav-second-level">-->
                <!--<li><a class="J_menuItem" href="typography.html">排版</a>-->
                <!--</li>-->
                <!--<li>-->
                <!--<a href="#">字体图标 <span class="fa arrow"></span></a>-->
                <!--<ul class="nav nav-third-level">-->
                <!--<li>-->
                <!--<a class="J_menuItem" href="fontawesome.html">Font Awesome</a>-->
                <!--</li>-->
                <!--<li>-->
                <!--<a class="J_menuItem" href="glyphicons.html">Glyphicon</a>-->
                <!--</li>-->
                <!--<li>-->
                <!--<a class="J_menuItem" href="iconfont.html">阿里巴巴矢量图标库</a>-->
                <!--</li>-->
                <!--</ul>-->
                <!--</li>-->
                <!--<li>-->
                <!--<a href="#">拖动排序 <span class="fa arrow"></span></a>-->
                <!--<ul class="nav nav-third-level">-->
                <!--<li><a class="J_menuItem" href="draggable_panels.html">拖动面板</a>-->
                <!--</li>-->
                <!--<li><a class="J_menuItem" href="agile_board.html">任务清单</a>-->
                <!--</li>-->
                <!--</ul>-->
                <!--</li>-->
                <!--<li><a class="J_menuItem" href="buttons.html">按钮</a>-->
                <!--</li>-->
                <!--<li><a class="J_menuItem" href="tabs_panels.html">选项卡 &amp; 面板</a>-->
                <!--</li>-->
                <!--<li><a class="J_menuItem" href="notifications.html">通知 &amp; 提示</a>-->
                <!--</li>-->
                <!--<li><a class="J_menuItem" href="badges_labels.html">徽章，标签，进度条</a>-->
                <!--</li>-->
                <!--<li>-->
                <!--<a class="J_menuItem" href="grid_options.html">栅格</a>-->
                <!--</li>-->
                <!--<li><a class="J_menuItem" href="plyr.html">视频、音频</a>-->
                <!--</li>-->
                <!--<li>-->
                <!--<a href="#">弹框插件 <span class="fa arrow"></span></a>-->
                <!--<ul class="nav nav-third-level">-->
                <!--<li><a class="J_menuItem" href="layer.html">Web弹层组件layer</a>-->
                <!--</li>-->
                <!--<li><a class="J_menuItem" href="modal_window.html">模态窗口</a>-->
                <!--</li>-->
                <!--<li><a class="J_menuItem" href="sweetalert.html">SweetAlert</a>-->
                <!--</li>-->
                <!--</ul>-->
                <!--</li>-->
                <!--<li>-->
                <!--<a href="#">树形视图 <span class="fa arrow"></span></a>-->
                <!--<ul class="nav nav-third-level">-->
                <!--<li><a class="J_menuItem" href="jstree.html">jsTree</a>-->
                <!--</li>-->
                <!--<li><a class="J_menuItem" href="tree_view.html">Bootstrap Tree View</a>-->
                <!--</li>-->
                <!--<li><a class="J_menuItem" href="nestable_list.html">nestable</a>-->
                <!--</li>-->
                <!--</ul>-->
                <!--</li>-->
                <!--<li><a class="J_menuItem" href="toastr_notifications.html">Toastr通知</a>-->
                <!--</li>-->
                <!--<li><a class="J_menuItem" href="diff.html">文本对比</a>-->
                <!--</li>-->
                <!--<li><a class="J_menuItem" href="spinners.html">加载动画</a>-->
                <!--</li>-->
                <!--<li><a class="J_menuItem" href="widgets.html">小部件</a>-->
                <!--</li>-->
                <!--</ul>-->
                <!--</li>-->
                <!--<li>-->
                <!--<a href="#"><i class="fa fa-table"></i> <span class="nav-label">表格</span><span class="fa arrow"></span></a>-->
                <!--<ul class="nav nav-second-level">-->
                <!--<li><a class="J_menuItem" href="table_basic.html">基本表格</a>-->
                <!--</li>-->
                <!--<li><a class="J_menuItem" href="table_data_tables.html">DataTables</a>-->
                <!--</li>-->
                <!--<li><a class="J_menuItem" href="table_jqgrid.html">jqGrid</a>-->
                <!--</li>-->
                <!--<li><a class="J_menuItem" href="table_foo_table.html">Foo Tables</a>-->
                <!--</li>-->
                <!--<li><a class="J_menuItem" href="table_bootstrap.html">Bootstrap Table-->
                <!--<span class="label label-danger pull-right">推荐</span></a>-->
                <!--</li>-->
                <!--</ul>-->
                <!--</li>-->
                <!--<li class="line dk"></li>-->
                <!--<li class="hidden-folded padder m-t m-b-sm text-muted text-xs">-->
                <!--<span class="ng-scope">分类</span>-->
                <!--</li>-->
                <!--<li>-->
                <!--<a href="#"><i class="fa fa-picture-o"></i> <span class="nav-label">相册</span><span class="fa arrow"></span></a>-->
                <!--<ul class="nav nav-second-level">-->
                <!--<li><a class="J_menuItem" href="basic_gallery.html">基本图库</a>-->
                <!--</li>-->
                <!--<li><a class="J_menuItem" href="carousel.html">图片切换</a>-->
                <!--</li>-->
                <!--<li><a class="J_menuItem" href="blueimp.html">Blueimp相册</a>-->
                <!--</li>-->
                <!--</ul>-->
                <!--</li>-->
                <!--<li>-->
                <!--<a class="J_menuItem" href="css_animation.html"><i class="fa fa-magic"></i> <span class="nav-label">CSS动画</span></a>-->
                <!--</li>-->
                <!--<li>-->
                <!--<a href="#"><i class="fa fa-cutlery"></i> <span class="nav-label">工具 </span><span class="fa arrow"></span></a>-->
                <!--<ul class="nav nav-second-level">-->
                <!--<li><a class="J_menuItem" href="form_builder.html">表单构建器</a>-->
                <!--</li>-->
                <!--</ul>-->
                <!--</li>-->

            </ul>
        </div>
    </nav>
    <!--左侧导航结束-->
    <!--右侧部分开始-->
    <div id="page-wrapper" class="gray-bg dashbard-1">
        <div class="row border-bottom">
            <nav class="navbar navbar-static-top" role="navigation" style="margin-bottom: 0">
                <div class="navbar-header"><a class="navbar-minimalize minimalize-styl-2 btn btn-info " href="javascript:;"><i class="fa fa-bars"></i> </a>

                </div>
                <ul class="nav navbar-top-links navbar-right">
                    <li class="dropdown">
                        <a href="<?php echo url('index/layout'); ?>">
                            <i class="fa fa-power-off">
                            </i>退出
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
        <div class="row J_mainContent" id="content-main">
            <iframe id="J_iframe" width="100%" height="100%" src="<?php echo url('main/base'); ?>" frameborder="0" data-id="<?php echo url('main/base'); ?>" seamless></iframe>
        </div>
    </div>
    <!--右侧部分结束-->
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
<script src="__PUBLIC__/admin/js/hAdmin.js?v=4.1.0"></script>
<script type="text/javascript" src="__PUBLIC__/admin/js/index.js"></script>

<!-- 第三方插件 -->
<script src="__PUBLIC__/admin/js/plugins/pace/pace.min.js"></script>

</body>

</html>
