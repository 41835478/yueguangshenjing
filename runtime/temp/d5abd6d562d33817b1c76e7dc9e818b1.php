<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:93:"C:\xampp\htdocs\yyyyy\yueguangshenjing\public/../application/admin\view\contents\service.html";i:1508549779;s:90:"C:\xampp\htdocs\yyyyy\yueguangshenjing\public/../application/admin\view\public\header.html";i:1508331166;s:90:"C:\xampp\htdocs\yyyyy\yueguangshenjing\public/../application/admin\view\public\popBox.html";i:1508331166;s:88:"C:\xampp\htdocs\yyyyy\yueguangshenjing\public/../application/admin\view\public\foot.html";i:1508481485;}*/ ?>
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
                    <h5>客服中心</h5>
                </div>
                <div class="ibox-content">
                    <form method="post" class="form-horizontal" action="<?php echo url('contents/update'); ?>?id=<?php echo $service['id']; ?>"
                          enctype="multipart/form-data">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="form-group has-warning">
                            <label class="col-sm-2 control-label">在线客服 :</label>
                            <div class="col-sm-10">
                                <input type="text" name="telephone" class="form-control" style="width:20%"
                                       value="<?php echo $service['telephone']; ?>" placeholder="请输入客服电话">
                            </div>
                        </div>

                        <div class="form-group has-warning">
                            <label class="col-sm-2 control-label">工作时间 :</label>
                            <div class="col-sm-10">
                                <input type="text" name="worktime" class="form-control" style="width:20%"
                                       value="<?php echo $service['worktime']; ?>" placeholder="格式为:早 09:00 - 晚 18:00">
                            </div>
                        </div>
                        <div class="form-group has-warning">
                            <label class="col-sm-2 control-label">客服二维码 :</label>
                            <!--<div class="col-sm-10">-->
                                <!--<script id="editor" name="content" type="text/plain"><?php echo $service['content']; ?></script>-->
                            <!--</div>-->
                            <div class="col-sm-6">
                                <img src="<?php echo $service['content']; ?>" width="109px" height="109px" alt="" id="addMain" >

                                <button style="position:absolute;top:56px;left:220px;padding:0 40px;opacity:0" type="button" class="layui-btn" id="test1">12312</button>
                                <input class="layui-upload-file" type="file" name="picname">
                                <input type="hidden" name="content" value="<?php echo $service['content']; ?>" id="bannerImg">

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

//    var ue = UE.getEditor('editor', {
//        initialFrameWidth: 800,//设置编辑器宽度
//        initialFrameHeight: 450,//设置编辑器高度
//        //scaleEnabled:true//设置不自动调整高度
////scaleEnabled {Boolean} [默认值：false]//是否可以拉伸长高，(设置true开启时，自动长高失效)
//    });
/*上传头像*/
layui.use('upload',function(){
    var $ = layui.jquery
        ,upload = layui.upload;

    //普通图片上传
    var uploadInst = upload.render({
        elem: '#test1'
        ,url: '/upload.php'
        ,field:'picname'
        ,before: function(obj){
            //预读本地文件示例，不支持ie8
            obj.preview(function(index, file, result){
                $('#demo1').attr('alt', "正在上传中..."); //图片链接（base64）
            });

        }
        ,done: function(res){
            console.log(res);
            //如果上传失败
            if(res.code > 0){
                return layer.msg('上传失败');
            }
            $("#addMain").attr("src",res.url);
            //上传成功
            $("#bannerImg").val(res.url);

        }
        ,error: function(){
            //演示失败状态，并实现重传
            var demoText = $('#demoText');
            demoText.html('<span style="color: #FF5722;">上传失败</span> <a class="layui-btn layui-btn-mini demo-reload">重试</a>');
            demoText.find('.demo-reload').on('click', function(){
                uploadInst.upload();
            });
        }
    });
});

</script>