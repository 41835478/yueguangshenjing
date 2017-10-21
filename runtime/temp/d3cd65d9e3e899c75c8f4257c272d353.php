<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:87:"C:\xampp\htdocs\yyyyy\yueguangshenjing\public/../application/admin\view\node\index.html";i:1508491848;s:90:"C:\xampp\htdocs\yyyyy\yueguangshenjing\public/../application/admin\view\public\header.html";i:1508331166;s:90:"C:\xampp\htdocs\yyyyy\yueguangshenjing\public/../application/admin\view\public\popBox.html";i:1508331166;}*/ ?>
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
            <h5>节点管理</h5>
            <div class="ibox-tools">
                <a class="collapse-link" href="javascript:;">
                    <button type="button" onclick="modal(0)" class="btn btn-outline btn-success" data-toggle="modal" data-target="#myModal5">
                        添加顶级节点
                    </button>
                </a>
                <a class="close-link" href="">
                    <i class="fa fa-refresh" href="javascript:void(0)" onclick="location.reload()" style="color:black">刷新</i>
                </a>
            </div>
        </div>
        <div class="ibox-content">
            <table class="footable table table-stripped" data-page-size="10" data-filter=#filter>
                <thead>
                <tr>
                    <th>ID</th>
                    <th>节点名称</th>
                    <th>控制器</th>
                    <th>方法名</th>
                    <th>是否是菜单</th>
                    <th>菜单图标</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                <tr class="gradeX">
                    <?php if(is_array($data) || $data instanceof \think\Collection || $data instanceof \think\Paginator): if( count($data)==0 ) : echo "" ;else: foreach($data as $k=>$v): ?>
                    <td class="did" _pid="<?php echo $v->pid; ?>" _id="<?php echo $v->id; ?>" _parent_name="<?php echo $v->parent_name; ?>"><?php echo $k+1; ?></td>
                    <td _name="<?php echo $v->node_name; ?>">
                        <?php if($v->level==2): ?>
                        &nbsp;&nbsp;&nbsp;&nbsp;<?php echo $v->name; else: ?>
                        <?php echo $v->name; endif; ?>
                    </td>
                    <td><?php echo $v->controller_name; ?></td>
                    <td><?php echo $v->action_name; ?></td>
                    <td _is_menu="<?php echo $v->is_menu; ?>">
                        <?php if($v->is_menu==1): ?>
                        是
                        <?php else: ?>
                        否
                        <?php endif; ?>
                    </td>
                    <td><?php echo $v->style; ?></td>
                    <td>
                        <?php if(($v->pid==0) OR ($v->level==1)): ?>
                        <a class="addSonNode btn btn-success" data-toggle="modal" data-target="#myModal5">添加子节点</a> |
                        <?php endif; ?>
                        <a onclick="modalEditNode(this,'<?php echo $v->id; ?>')" class="btn btn-info  dim" data-toggle="modal" data-target="#myModal6">修改</a> |
                        <a class="btn btn-danger  dim " onclick="delNode('<?php echo $v->id; ?>')">删除</a>
                    </td>
                </tr>
                <?php endforeach; endif; else: echo "" ;endif; ?>
                </tbody>
                <tfoot>
                <tr>
                    <td colspan="3">
                        总共 <?php echo $total; ?> 页 当前显示第 <?php echo $currentPage; ?> 页
                    </td>
                    <td colspan="4" style="text-align: right">
                        <?php echo $render; ?>
                    </td>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
<div class="modal inmodal fade" id="myModal5" tabindex="-1" role="dialog"  aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">添加节点</h4>
            </div>
            <div class="modal-body">
                <form method="post" class="form-horizontal" action="<?php echo url('node/addNode'); ?>" enctype="multipart/form-data">
                    <div class="form-group">
                        <label class="col-sm-2 control-label">节点名称</label>
                        <div class="col-sm-10">
                            <input class="form-control" type="text" name="node_name" placeholder="">
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">所属节点</label>
                        <div class="col-sm-10">
                            <input class="form-control" type="text" id="pidNode">
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">控制器名</label>
                        <div class="col-sm-10">
                            <input class="form-control" type="text" name="controller_name" placeholder="请填小写英文名称（若是顶级请填‘#’）">
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">方法名</label>
                        <div class="col-sm-10">
                            <input class="form-control" type="text" name="action_name" placeholder="若是顶级请填‘#’">
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">是否是菜单项</label>
                        <div class="col-sm-10">
                            <div class="radio radio-inline">
                                <input type="radio" id="inlineRadio1" value="1" checked name="is_menu">是
                            </div>
                            <div class="radio radio-inline">
                                <input type="radio" id="inlineRadio2" value="2" name="is_menu">否
                            </div>
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">菜单图标</label>
                        <div class="col-sm-10">
                            <input class="form-control" type="text" name="style" placeholder="如：fa fa fa-bar-chart-o（只有顶级节点才有图标）">
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <input type="hidden" id="parentId" name="pid" value="">
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
<div class="modal inmodal fade" id="myModal6" tabindex="-1" role="dialog"  aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">修改节点</h4>
            </div>
            <div class="modal-body">
                <form method="post" class="form-horizontal" action="<?php echo url('node/editNode'); ?>" enctype="multipart/form-data">
                    <div class="form-group">
                        <label class="col-sm-2 control-label">节点名称</label>
                        <div class="col-sm-10">
                            <input class="form-control" id="node_name" type="text" name="node_name" placeholder="">
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">所属节点</label>
                        <div class="col-sm-10">
                            <input class="form-control" type="text" id="pidNode1">
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">控制器名</label>
                        <div class="col-sm-10">
                            <input class="form-control" type="text" id="controller_name" name="controller_name" placeholder="请填小写英文名称（若是顶级请填‘#’）">
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">方法名</label>
                        <div class="col-sm-10">
                            <input class="form-control" type="text" id="action_name" name="action_name" placeholder="若是顶级请填‘#’">
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">是否是菜单项</label>
                        <div class="col-sm-10">
                            <div class="radio radio-inline">
                                <input type="radio" id="inlineRadio3" value="1" name="is_menu">是
                            </div>
                            <div class="radio radio-inline">
                                <input type="radio" id="inlineRadio4" value="2" name="is_menu">否
                            </div>
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">菜单图标</label>
                        <div class="col-sm-10">
                            <input class="form-control" type="text" id="style" name="style" placeholder="如：fa fa fa-bar-chart-o（只有顶级节点才有图标）">
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <input type="hidden" id="myId" name="id" value="">
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
<script src="__PUBLIC__/admin/js/plugins/jsTree/jstree.min.js"></script>

<style>
    .jstree-open > .jstree-anchor > .fa-folder:before {
        content: "\f07c";
    }
    .jstree-default .jstree-icon.none {
        width: 0;
    }
</style>
<script>
    function modal(pid){
        if(pid==0){
            $('#parentId').val(pid);
            $('#pidNode').attr('placeholder','顶级节点');
            $('#pidNode').attr('disabled','disabled');
        }
    }
    $('.addSonNode').click(function(){
        var obj=$(this).parent().parent().find('.did');
        var id=obj.attr('_id');
        var name=obj.next().attr('_name');
        $('#parentId').val(id);
        $('#pidNode').attr('placeholder',name);
        $('#pidNode').attr('disabled','disabled');
    })
    function modalEditNode($this,id)
    {
        var obj=$($this).parent().parent().find('td');
        var parent_name=obj.eq(0).attr('_parent_name');
        var node_name=obj.eq(1).attr('_name');
        var controller=obj.eq(2).html();
        var action=obj.eq(3).html();
        var is_menu=obj.eq(4).attr('_is_menu');
        var style=obj.eq(5).html();
        $('#myId').val(id);
        $('#node_name').val(node_name);
        $('#pidNode1').val(parent_name);
        $('#pidNode1').attr('disabled','disabled');
        $('#controller_name').val(controller);
        $('#action_name').val(action);
        $('#style').val(style);
        if(is_menu==1){
            $('#inlineRadio3').attr('checked','checked');
        }else{
            $('#inlineRadio4').attr('checked','checked');
        }
    }
    function delNode(id)
    {
        var name=confirm('你确定要删除改节点吗?');
        if(name){
            var data={
                'id':id,
            };
            var url="<?php echo url('node/delNode'); ?>";
            sendAjax(data,url)
        }
    }
    function sendAjax(data,url)
    {
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
<script>

</script>
<script>
    $('.close').click(function(){
        $(this).parent().parent().remove();
    })
</script>
</body>
</html>