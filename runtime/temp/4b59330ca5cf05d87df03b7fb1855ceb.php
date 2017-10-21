<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:93:"C:\xampp\htdocs\yyyyy\yueguangshenjing\public/../application/admin\view\statistics\index.html";i:1508557270;s:90:"C:\xampp\htdocs\yyyyy\yueguangshenjing\public/../application/admin\view\public\header.html";i:1508331166;s:90:"C:\xampp\htdocs\yyyyy\yueguangshenjing\public/../application/admin\view\public\popBox.html";i:1508331166;s:88:"C:\xampp\htdocs\yyyyy\yueguangshenjing\public/../application/admin\view\public\foot.html";i:1508555543;}*/ ?>
<link rel="shortcut icon" href="favicon.ico"> <link href="__PUBLIC__/admin/css/bootstrap.min.css?v=3.3.6" rel="stylesheet">
<link href="__PUBLIC__/admin/css/font-awesome.min.css?v=4.4.0" rel="stylesheet">
<link href="__PUBLIC__/admin/css/animate.css" rel="stylesheet">
<link href="__PUBLIC__/admin/css/style.css?v=4.1.0" rel="stylesheet">
<?php if(\think\Session::get('status') != null): ?>
<div class="ibox-content">
    <?php if(\think\Session::get('status') == 'success'): ?>
    <div class="alert alert-success alert-dismissable">
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
        <?php echo \think\Session::get('value'); ?>
    </div>
    <?php endif; if(\think\Session::get('status') == 'error'): ?>
    <div class="alert alert-danger alert-dismissable">
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
        <?php echo \think\Session::get('value'); ?>
    </div>
    <?php endif; if(\think\Session::get('status') == 'warning'): ?>
    <div class="alert alert-warning alert-dismissable">
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
        <?php echo \think\Session::get('value'); ?>
    </div>
    <?php endif; ?>
</div>
<?php endif; ?>
<section id="main-content">
    <div class="row">
        <div class="col-sm-3">
            <div class="ibox">
                <div class="ibox-content">
                    <h5 class="m-b-md">累计会员总量</h5>
                    <h2 class="text-navy">
                        <i class="fa fa-play fa-rotate-270"></i> 上升
                    </h2>
                    <small>更新时间：12天以前</small>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="ibox">
                <div class="ibox-content ">
                    <h5 class="m-b-md">今日新增会员量</h5>
                    <h2 class="text-navy">
                        <i class="fa fa-play fa-rotate-270"></i> 上升
                    </h2>
                    <small>更新时间：12天以前</small>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="ibox">
                <div class="ibox-content">
                    <h5 class="m-b-md">历史累计收入总额</h5>
                    <h2 class="text-danger">
                        <i class="fa fa-play fa-rotate-90"></i> 下降
                    </h2>
                    <small>更新时间：12天以前</small>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="ibox">
                <div class="ibox-content">
                    <h5 class="m-b-md">历史累计发货总量</h5>
                    <h2 class="text-danger">
                        <i class="fa fa-play fa-rotate-90"></i> 下降
                    </h2>
                    <small>更新时间：12天以前</small>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-3">
            <div class="ibox">
                <div class="ibox-content">
                    <h5>历史累计订单总量</h5>
                    <h2>198 009</h2>
                    <div id="sparkline1"></div>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="ibox">
                <div class="ibox-content">
                    <h5>会员账户余额总计</h5>
                    <h2>65 000</h2>
                    <div id="sparkline2"></div>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="ibox">
                <div class="ibox-content">
                    <h5>今日新增会员量统计</h5>
                    <h2>680 900</h2>
                    <div id="sparkline3"></div>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="ibox">
                <div class="ibox-content">
                    <h5>今日订单统计</h5>
                    <h2>00:06:40</h2>
                    <div id="sparkline4"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-3">
            <div class="ibox">
                <div class="ibox-content">
                    <h5>已发放提现总额</h5>
                    <h2>42/20</h2>
                    <div class="text-center">
                        <div id="sparkline5"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="ibox">
                <div class="ibox-content">
                    <h5>提现申请总额</h5>
                    <h2>100/54</h2>
                    <div class="text-center">
                        <div id="sparkline6"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="ibox">
                <div class="ibox-content">
                    <h5>粉丝数量统计</h5>
                    <h2>685/211</h2>
                    <div class="text-center">
                        <div id="sparkline7"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="ibox">
                <div class="ibox-content">
                    <h5>会员数量统计</h5>
                    <h2>240/32</h2>
                    <div class="text-center">
                        <div id="sparkline8"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-3">
            <div class="ibox">
                <div class="ibox-content">
                    <h5>县级市代理数量统计</h5>
                    <h2>65%</h2>
                    <div class="progress progress-mini">
                        <div style="width: 68%;" class="progress-bar"></div>
                    </div>

                    <div class="m-t-sm small">4:32更新</div>
                </div>
            </div>
        </div>

        <div class="col-sm-3">
            <div class="ibox">
                <div class="ibox-content">
                    <h5>地级市代理数量统计</h5>
                    <h2>10%</h2>
                    <div class="progress progress-mini">
                        <div style="width: 78%;" class="progress-bar"></div>
                    </div>
                    <div class="m-t-sm small">4:32更新</div>
                </div>
            </div>
        </div>

        <div class="col-sm-3">
            <div class="ibox">
                <div class="ibox-content">
                    <h5>省会城市代理数量统计</h5>
                    <h2>14%</h2>
                    <div class="progress progress-mini">
                        <div style="width: 38%;" class="progress-bar progress-bar-danger"></div>
                    </div>

                    <div class="m-t-sm small">4:32更新</div>
                </div>
            </div>
        </div>

        <div class="col-sm-3">
            <div class="ibox">
                <div class="ibox-content">
                    <h5>一线城市代理数量统计</h5>
                    <h2>20%</h2>
                    <div class="progress progress-mini">
                        <div style="width: 28%;" class="progress-bar progress-bar-danger"></div>
                    </div>

                    <div class="m-t-sm small">4:32更新</div>
                </div>
            </div>
        </div>
    </div>
</section>

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
<script>
    $("#sparkline1").sparkline([34, 43, 43, 35, 44, 32, 44, 52], {
        type: 'line',
        width: '100%',
        height: '60',
        lineColor: '#1ab394',
        fillColor: "#ffffff"
    });

    $("#sparkline2").sparkline([24, 43, 43, 55, 44, 62, 44, 72], {
        type: 'line',
        width: '100%',
        height: '60',
        lineColor: '#1ab394',
        fillColor: "#ffffff"
    });

    $("#sparkline3").sparkline([74, 43, 23, 55, 54, 32, 24, 12], {
        type: 'line',
        width: '100%',
        height: '60',
        lineColor: '#ed5565',
        fillColor: "#ffffff"
    });

    $("#sparkline4").sparkline([24, 43, 33, 55, 64, 72, 44, 22], {
        type: 'line',
        width: '100%',
        height: '60',
        lineColor: '#ed5565',
        fillColor: "#ffffff"
    });
    $("#sparkline5").sparkline([1, 4], {
        type: 'pie',
        height: '140',
        sliceColors: ['#1ab394', '#F5F5F5']
    });

    $("#sparkline6").sparkline([5, 3], {
        type: 'pie',
        height: '140',
        sliceColors: ['#1ab394', '#F5F5F5']
    });

    $("#sparkline7").sparkline([2, 2], {
        type: 'pie',
        height: '140',
        sliceColors: ['#ed5565', '#F5F5F5']
    });

    $("#sparkline8").sparkline([2, 3], {
        type: 'pie',
        height: '140',
        sliceColors: ['#ed5565', '#F5F5F5']
    });
</script>