<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:95:"C:\xampp\htdocs\yyyyy\yueguangshenjing\public/../application/admin\view\contents\introduce.html";i:1508483123;s:90:"C:\xampp\htdocs\yyyyy\yueguangshenjing\public/../application/admin\view\public\header.html";i:1508331166;s:90:"C:\xampp\htdocs\yyyyy\yueguangshenjing\public/../application/admin\view\public\popBox.html";i:1508331166;s:88:"C:\xampp\htdocs\yyyyy\yueguangshenjing\public/../application/admin\view\public\foot.html";i:1508481485;}*/ ?>
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
                    <h5>平台介绍</h5>
                </div>
                <div class="ibox-content">
                    <form action="<?php echo url('contents/update'); ?>?id=<?php echo $contentOne['id']; ?>" method="post" class="form-horizontal"
                          enctype="multipart/form-data">
                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">平台介绍:</label>
                            <div class="col-sm-6">
                                <script id="editor" name="content" type="text/plain"><?php echo $contentOne['content']; ?></script>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-4 col-sm-offset-2">
                                <button type="submit" class="btn btn-w-m btn-primary">保存内容</button>
                            </div>
                        </div>
                    </form>
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

    var ue = UE.getEditor('editor',{
        initialFrameWidth :800,//设置编辑器宽度
        initialFrameHeight:450,//设置编辑器高度
        //scaleEnabled:true//设置不自动调整高度
//scaleEnabled {Boolean} [默认值：false]//是否可以拉伸长高，(设置true开启时，自动长高失效)
    });

</script>