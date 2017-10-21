<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:87:"C:\xampp\htdocs\yyyyy\yueguangshenjing\public/../application/admin\view\role\index.html";i:1508491848;s:90:"C:\xampp\htdocs\yyyyy\yueguangshenjing\public/../application/admin\view\public\header.html";i:1508331166;s:90:"C:\xampp\htdocs\yyyyy\yueguangshenjing\public/../application/admin\view\public\popBox.html";i:1508331166;}*/ ?>
<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">


    <title> - 修改管理员信息</title>
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
            <h5>角色管理</h5>
            <div class="ibox-tools">
                <a class="collapse-link" href="javascript:;">
                    <button type="button" onclick="modal(0)" class="btn btn-outline btn-success" data-toggle="modal" data-target="#myModal5">
                        添加角色
                    </button>
                </a>
            </div>
        </div>
        <div class="ibox-content">
            <table class="footable table table-stripped" data-page-size="10" data-filter=#filter>
                <thead>
                <tr>
                    <th>ID</th>
                    <th>角色名称</th>
                    <th>查看权限</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                <tr class="gradeX">
                    <?php if(is_array($data) || $data instanceof \think\Collection || $data instanceof \think\Paginator): if( count($data)==0 ) : echo "" ;else: foreach($data as $key=>$v): ?>
                    <td class="did" ><?php echo $v->id; ?></td>
                    <td _role_name><?php echo $v->role_name; ?></td>
                    <td>
                        <button onclick="ruleSee('<?php echo $v->id; ?>')" type="button" class="btn btn-w-m btn-info" data-toggle="modal" data-target="#myModal4">查看</button>
                    </td>
                    <td>
                        <a onclick="modalEditRole('<?php echo $v->id; ?>')">编辑</a> |
                        <a href="<?php echo url('role/roleNode',array('id'=>$v->id)); ?>">分配权限</a> |
                        <a onclick="delRole('<?php echo $v->id; ?>')">删除</a>
                    </td>
                </tr>
                <?php endforeach; endif; else: echo "" ;endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="modal inmodal" id="myModal5" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content animated bounceInRight">
            <div class="modal-header">
                <h1 class="modal-title">添加角色</h1>
            </div>
                <div class="modal-body">
                    <form method="post" class="form-horizontal" action="<?php echo url('role/addRole'); ?>" enctype="multipart/form-data">
                        <div class="form-group">
                            <label class="col-sm-2 control-label">角色名称</label>
                            <div class="col-sm-10">
                                <input class="form-control" type="text" name="role_name" placeholder="">
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
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
<div class="modal inmodal" id="myModal4" tabindex="-1" role="dialog"  aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content animated fadeIn">
            <div class="modal-header">
                <h4 class="modal-title">角色所拥有的权限</h4>
            </div>
            <div class="modal-body">
                <p id="modalRule"></p>
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
    function ruleSee(id){
        $.ajax({
            'url':"<?php echo url('role/ruleSee'); ?>",
            'data':{'id':id},
            'async':true,
            'type':'post',
            'dataType':'json',
            success:function(res){
                $('#modalRule').html('');
                $('#modalRule').html(res.data);
            },
            error:function(){
                alert('操作失败');
            }
        })
    }
    function modalEditRole(id){
        var name=prompt('请输入您要修改为的角色名称：');
        if(name){
            var data={
                'name':name,
                'id':id,
                'flag':1,
            };
            var url='<?php echo url("role/roleAct"); ?>'
            sendAjax(url,data)
        }
    }
    function delRole(id){
        var sure=confirm('您确定要删除该角色名称吗？');
        if(sure){
            var data={
                'id':id,
                'flag':2,
            };
            var url='<?php echo url("role/roleAct"); ?>'
            sendAjax(url,data)
        }
    }
    function sendAjax(url,data){
        $.ajax({
            'url':url,
            'data':data,
            'async':true,
            'type':'post',
            'dataType':'json',
            success:function(res){
                alert(res.message);
                window.location.reload();
            },
            error:function(){
                alert('操作失败');
            }
        })
    }
</script>
</body>
</html>