<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:96:"C:\xampp\htdocs\yyyyy\yueguangshenjing\public/../application/admin\view\administrator\index.html";i:1508491848;s:90:"C:\xampp\htdocs\yyyyy\yueguangshenjing\public/../application/admin\view\public\header.html";i:1508331166;s:90:"C:\xampp\htdocs\yyyyy\yueguangshenjing\public/../application/admin\view\public\popBox.html";i:1508331166;}*/ ?>
<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">


    <title> - 管理员管理</title>
    <meta name="keywords" content="">
    <meta name="description" content="">

    <link rel="shortcut icon" href="favicon.ico"> <link href="__PUBLIC__/admin/css/bootstrap.min.css?v=3.3.6" rel="stylesheet">
<link href="__PUBLIC__/admin/css/font-awesome.min.css?v=4.4.0" rel="stylesheet">
<link href="__PUBLIC__/admin/css/animate.css" rel="stylesheet">
<link href="__PUBLIC__/admin/css/style.css?v=4.1.0" rel="stylesheet">
    <link href="__PUBLIC__/admin/css/plugins/jsTree/style.min.css" rel="stylesheet">

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
            <h5>管理员管理</h5>
            <div class="ibox-tools">
                <a class="collapse-link" href="javascript:;">
                    <button type="button" onclick="modalRole(0)" class="btn btn-outline btn-success" data-toggle="modal" data-target="#myModal5">
                        添加管理员
                    </button>
                </a>
            </div>
        </div>
        <div class="ibox-content">
            <table class="footable table table-stripped" data-page-size="10" data-filter=#filter>
                <thead>
                <tr>
                    <th>ID</th>
                    <th>管理员名称</th>
                    <th>管理员头像</th>
                    <th>管理员手机号</th>
                    <th>管理员角色</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                <tr class="gradeX">
                    <?php if(is_array($data) || $data instanceof \think\Collection || $data instanceof \think\Paginator): if( count($data)==0 ) : echo "" ;else: foreach($data as $key=>$v): ?>
                    <td class="did" ><?php echo $v->id; ?></td>
                    <td><?php echo $v->name; ?></td>
                    <td>
                        <img id="img" style="border: 1px dashed #c0c0c0" src="__PUBLIC__<?php echo (isset($v->pic) && ($v->pic !== '')?$v->pic:'/admin/headPic/a5.jpg'); ?>" width="50px" height="50px">
                    </td>
                    <td><?php echo $v->mobile; ?></td>
                    <td><?php echo $v->role_name; ?></td>
                    <td>
                        <a onclick="editRole(this,'<?php echo $v->id; ?>')" data-toggle="modal" data-target="#myModal6">修改角色</a> |
                        <a onclick="delRole('<?php echo $v->id; ?>')">删除</a>
                    </td>
                </tr>
                <?php endforeach; endif; else: echo "" ;endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="modal inmodal fade" id="myModal5" tabindex="-1" role="dialog"  aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">添加管理员</h4>
            </div>
            <div class="modal-body">
                <form method="post" class="form-horizontal" action="<?php echo url('administrator/addUser'); ?>" enctype="multipart/form-data">
                    <div class="form-group">
                        <label class="col-sm-2 control-label">管理员名称</label>
                        <div class="col-sm-10">
                            <input class="form-control" type="text" name="name" placeholder="">
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">管理员手机号</label>
                        <div class="col-sm-10">
                            <input class="form-control" type="text" name="mobile">
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">管理员密码</label>
                        <div class="col-sm-10">
                            <input class="form-control" type="password" name="pwd">
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">确认密码</label>
                        <div class="col-sm-10">
                            <input class="form-control" type="password" name="re_pwd">
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">角色</label>
                        <div class="col-sm-10">
                            <select class="form-control" name="role" type="text" id="mallType">
                                <option value="">--请选择角色--</option>
                                <?php if(count($role)>0): foreach($role as $v): ?>
                                        <option value="<?php echo $v->id; ?>"><?php echo $v->role_name; ?></option>
                                    <?php endforeach; endif; ?>
                            </select>
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <input type="hidden" id="myId" name="id" value="">
                    <div class="form-group">
                        <div class="col-sm-4 col-sm-offset-2">
                            <button class="btn btn-primary" type="submit">添加</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <button class="btn btn-white" type="reset">重置</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
            </div>
        </div>
    </div>
</div>
<div class="modal inmodal" id="myModal6" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content animated bounceInRight">
            <div class="modal-header">
                <h1 class="modal-title">修改角色</h1>
            </div>
            <div class="modal-body">
                <form method="post" class="form-horizontal" action="<?php echo url('administrator/editRole'); ?>" enctype="multipart/form-data">
                    <div class="form-group">
                        <label class="col-sm-2 control-label">角色名称</label>
                        <div class="col-sm-10">
                            <select class="form-control" name="role" type="text" id="roleName">
                                <option value="">--请选择角色--</option>
                                <?php if(count($role)>0): foreach($role as $v): ?>
                                <option value="<?php echo $v->id; ?>"><?php echo $v->role_name; ?></option>
                                <?php endforeach; endif; ?>
                            </select>
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <input type="hidden" id="user_id" name="id">
                    <div class="form-group">
                        <div class="col-sm-4 col-sm-offset-2">
                            <button class="btn btn-primary" type="submit">修改</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <button class="btn btn-white" type="reset">重置</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="__PUBLIC__/admin/editor/kindeditor.js"></script>

<script src="__PUBLIC__/admin/js/jquery.min.js?v=2.1.4"></script>
<script src="__PUBLIC__/admin/js/bootstrap.min.js?v=3.3.6"></script>
<!--<script src="__PUBLIC__/admin/js/jquery.min.js"></script>-->
<script>
    $('.close').click(function(){
        $(this).parent().parent().remove();
    })
</script>
<script>
    function modalRole(id)
    {
        $('#myId').val(id);

    }
    function editRole($this,id){
        var role_name=$($this).parent().prev().html();
        $('#user_id').val(id);
    }
</script>
</body>
</html>