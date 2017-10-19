<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:87:"C:\xampp\htdocs\yyyyy\yueguangshenjing\public/../application/admin\view\member\pwd.html";i:1508331166;s:90:"C:\xampp\htdocs\yyyyy\yueguangshenjing\public/../application/admin\view\public\header.html";i:1508331166;s:90:"C:\xampp\htdocs\yyyyy\yueguangshenjing\public/../application/admin\view\public\popBox.html";i:1508331166;}*/ ?>
<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">


    <title>修改管理员密码</title>
    <meta name="keywords" content="">
    <meta name="description" content="">

    <link rel="shortcut icon" href="favicon.ico"> <link href="__PUBLIC__/admin/css/bootstrap.min.css?v=3.3.6" rel="stylesheet">
<link href="__PUBLIC__/admin/css/font-awesome.min.css?v=4.4.0" rel="stylesheet">
<link href="__PUBLIC__/admin/css/animate.css" rel="stylesheet">
<link href="__PUBLIC__/admin/css/style.css?v=4.1.0" rel="stylesheet">

</head>

<body class="gray-bg">

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
<div class="col-sm-12">
    <div class="ibox float-e-margins">
        <div class="ibox-title">
            <h5>修改管理员密码
            </h5>
            <div class="ibox-tools">

            </div>
        </div>
        <div class="ibox-content">
            <form method="post" class="form-horizontal" action="<?php echo url('member/editPwd'); ?>" enctype="multipart/form-data">
                <div class="form-group">
                    <label class="col-sm-2 control-label">管理员名称</label>
                    <div class="col-sm-10">
                        <input class="form-control" disabled type="text" value="<?php echo $name; ?>">
                    </div>
                </div>
                <div class="hr-line-dashed"></div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">管理员账号</label>
                    <div class="col-sm-10">
                        <input class="form-control" disabled type="text" value="<?php echo $mobile; ?>">
                    </div>
                </div>
                <div class="hr-line-dashed"></div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">旧密码</label>
                    <div class="col-sm-10">
                        <input class="form-control" type="password" name="oldPwd" value="" id="oldPwd">
                    </div>
                </div>
                <div class="hr-line-dashed"></div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">新密码</label>
                    <div class="col-sm-10">
                        <input class="form-control" type="password" name="newPwd" value="">
                    </div>
                </div>
                <div class="hr-line-dashed"></div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">确认密码</label>
                    <div class="col-sm-10">
                        <input class="form-control" type="password" name="rePwd" value="">
                    </div>
                </div>
                <div class="hr-line-dashed"></div>
                <input type="hidden" name="id" value="<?php echo $id; ?>" id="id">
                <div class="form-group">
                    <div class="col-sm-4 col-sm-offset-2">
                        <button class="btn btn-primary" type="submit">修改</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <button class="btn btn-white" type="reset">重置</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="__PUBLIC__/admin/js/bootstrap.min.js?v=3.3.6"></script>
<script src="__PUBLIC__/admin/js/jquery.min.js"></script>
<script>
    $('.close').click(function(){
        $(this).parent().parent().remove();
    })
    $('#oldPwd').blur(function(){
        var id=$('#id').val();
        var oldPwd=$(this).val();
        $.ajax({
            'url':'<?php echo url("member/validatePwd"); ?>',
            'data':{'id':id,'oldPwd':oldPwd},
            'async':true,
            'type':'post',
            'dataType':'json',
            success:function(data){
                if(data.status==false){
                    parent.layer.alert(data.error_message, {
                        skin: 'layui-layer-lan',
                        shift: 4 //动画类型
                    });
                }else{
                    parent.layer.alert(data.error_message, {
                        skin: 'layui-layer-molv',//样式类名
                        shift: 4 //动画类型
                    })
                }
            },
            error:function(){
                alert('Ajax响应失败');
            }
        })
    })
</script>
</body>
</html>