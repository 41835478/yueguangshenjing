<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:90:"C:\xampp\htdocs\yyyyy\yueguangshenjing\public/../application/admin\view\configs\index.html";i:1508553179;s:90:"C:\xampp\htdocs\yyyyy\yueguangshenjing\public/../application/admin\view\public\header.html";i:1508331166;s:90:"C:\xampp\htdocs\yyyyy\yueguangshenjing\public/../application/admin\view\public\popBox.html";i:1508331166;s:88:"C:\xampp\htdocs\yyyyy\yueguangshenjing\public/../application/admin\view\public\foot.html";i:1508481485;}*/ ?>
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
        <div class="col-sm-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>系统设置
                        <small></small>
                    </h5>
                    <div class="ibox-tools">

                    </div>
                </div>
                <div class="ibox-content">
                    <?php foreach($configs as $v): ?>
                    <div class="row">
                        <form action="<?php echo url('configs/edit'); ?>?id=<?php echo $v['id']; ?>" method="post">
                            <div class="form-group">
                                <label class="col-sm-1 control-label"><?php echo $v['description']; ?>:</label>
                                <div class="col-sm-4">
                                    <input type="number" name="value" min="0" value="<?php echo $v['value']; ?>"
                                           step="0.1" class="form-control"> <span class="help-block m-b-none">填写请谨慎</span>
                                </div>
                                <div class="col-sm-7">
                                    <button type="submit" class="btn btn-info">确认</button>
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>
                        </form>
                    </div>
                    <?php endforeach; ?>
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
<script src="__PUBLIC__/admin/layui-2.0/layui.js"></script>

<script type="text/javascript" charset="utf-8" src="__PUBLIC__/ueditor/ueditor.config.js"></script>
<script type="text/javascript" charset="utf-8" src="__PUBLIC__/ueditor/ueditor.all.min.js"> </script>
<script type="text/javascript" charset="utf-8" src="__PUBLIC__/ueditor/lang/zh-cn/zh-cn.js"></script>


<!-- 自定义js -->
<script>
</script>