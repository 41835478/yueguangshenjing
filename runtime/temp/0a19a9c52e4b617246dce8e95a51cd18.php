<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:92:"C:\xampp\htdocs\yyyyy\yueguangshenjing\public/../application/admin\view\goods\goodsList.html";i:1508572780;s:90:"C:\xampp\htdocs\yyyyy\yueguangshenjing\public/../application/admin\view\public\header.html";i:1508572780;s:90:"C:\xampp\htdocs\yyyyy\yueguangshenjing\public/../application/admin\view\public\popBox.html";i:1508331166;}*/ ?>
<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">


    <title> - 商品列表</title>
    <meta name="keywords" content="">
    <meta name="description" content="">

    <link rel="shortcut icon" href="favicon.ico"> <link href="__PUBLIC__/admin/css/bootstrap.min.css?v=3.3.6" rel="stylesheet">
<link href="__PUBLIC__/admin/css/font-awesome.min.css?v=4.4.0" rel="stylesheet">
<link href="__PUBLIC__/admin/css/animate.css" rel="stylesheet">
<link href="__PUBLIC__/admin/css/style.css?v=4.1.0" rel="stylesheet">
<link href="__PUBLIC__/admin/css/plugins/sweetalert/sweetalert.css" rel="stylesheet">
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
            <h5>商品列表</h5>
            <div class="ibox-tools">
                <a class="collapse-link" href="<?php echo url('goods/index'); ?>">
                    <button type="button" class="btn btn-success">
                        添加商品
                    </button>
                </a>
            </div>
        </div>
        <div class="ibox-content">
            <table class="footable table table-stripped" data-page-size="10" data-filter=#filter>
                <thead>
                <tr>
                    <th>ID</th>
                    <th>商品名称</th>
                    <th>商品描述</th>
                    <th>商品主图</th>
                    <th>商品展示图</th>
                    <th>商品价格</th>
                    <th>商品品牌</th>
                    <th>商品颜色</th>
                    <th>商品状态</th>
                    <th>商品详情</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                <tr class="gradeX">
                    <?php if(count($data)>0): if(is_array($data) || $data instanceof \think\Collection || $data instanceof \think\Paginator): if( count($data)==0 ) : echo "" ;else: foreach($data as $key=>$v): ?>
                    <td class="did" ><?php echo $v->id; ?></td>
                    <td><?php echo $v->name; ?></td>
                    <td><?php echo $v->describes; ?></td>
                    <td>
                        <img style="border: 1px dashed #c0c0c0" src="__PUBLIC__<?php echo $v->main_pic; ?>" width="50px" height="50px">
                    </td>
                    <td>
                        <img style="border: 1px dashed #c0c0c0" src="__PUBLIC__<?php echo $v->show_pic; ?>" width="50px" height="50px">
                    </td>
                    <td><?php echo $v->goods_price; ?></td>
                    <td><?php echo $v->goods_brand; ?></td>
                    <td><?php echo $v->goods_color; ?></td>
                    <td>
                        <?php if($v->status == 1): ?>
                        <b style="color:green">上架</b> | <b style="color:#ccc;cursor:pointer" class="goodsDown">下架</b>
                        <?php else: ?>
                        <b style="color:#ccc;cursor:pointer" class="goodsUp">上架</b> | <b style="color:green">下架</b>
                        <?php endif; ?>
                    </td>
                    <td>
                        <button type="button" _content='"<?php echo $v->content; ?>"' onclick='getGoodsDate(this,"<?php echo $v->id; ?>")' class="btn btn-primary" data-toggle="modal" data-target="#myModal5">
                            查看详情
                        </button>
                    </td>
                    <td>
                        <a onclick="modalEditGoods(this,'<?php echo $v->id; ?>','<?php echo $v->main_pic; ?>')"  data-toggle="modal" data-target="#myModal3">修改</a> |
                        <a onclick="editShowPic('<?php echo $v->id; ?>','<?php echo $v->show_pic; ?>')" data-toggle="modal" data-target="#myModal4">修改商品展示图</a> |
                        <a onclick="delGoods('<?php echo $v->id; ?>')">删除</a>
                    </td>
                </tr>
                <?php endforeach; endif; else: echo "" ;endif; endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="modal inmodal fade" id="myModal3" tabindex="-1" role="dialog"  aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">修改商品</h4>
            </div>
            <div class="modal-body">
            <form method="post" class="form-horizontal" action="<?php echo url('goods/editGoods'); ?>" enctype="multipart/form-data">
                <div class="form-group">
                    <label class="col-sm-2 control-label">商品名称</label>
                    <div class="col-sm-10">
                        <input class="form-control" id="name" type="text" name="name" placeholder="">
                    </div>
                </div>
                <div class="hr-line-dashed"></div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">商品描述</label>
                    <div class="col-sm-10">
                        <input class="form-control" type="text" id="describes" name="describes">
                    </div>
                </div>
                <div class="hr-line-dashed"></div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">商品主图</label>
                    <div class="col-sm-10">
                        <input type="file" name="main_pic" value="" id="singleFile">
                        <img id="singleImg" style="border: 1px dashed #c0c0c0" src="__PUBLIC__/admin/goods/index0.jpg" width="100px" height="100px">
                    </div>
                </div>
                <div class="hr-line-dashed"></div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">商品价格</label>
                    <div class="col-sm-10">
                        <input class="form-control" type="text" id="goods_price" name="goods_price">
                    </div>
                </div>
                <div class="hr-line-dashed"></div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">商品品牌</label>
                    <div class="col-sm-10">
                        <input class="form-control" type="text" id="goods_brand" name="goods_brand">
                    </div>
                </div>
                <div class="hr-line-dashed"></div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">商品颜色</label>
                    <div class="col-sm-10">
                        <input class="form-control" type="text" id="goods_color" name="goods_color">
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
            <div class="modal-footer">
                <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
            </div>
        </div>
    </div>
</div>
<div class="modal inmodal" id="myModal4" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content animated bounceInRight">
            <div class="modal-header">
                <h1 class="modal-title">修改商品展示图</h1>
            </div>
            <div class="modal-body">
                <form method="post" class="form-horizontal" action="<?php echo url('goods/editShowPic'); ?>" enctype="multipart/form-data">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">商品展示图</label>
                        <div class="col-sm-9">
                            <input type="file" name="show_pic" value="" id="showFile">
                            <img id="showImg" style="border: 1px dashed #c0c0c0" src="__PUBLIC__/admin/goods/index0.jpg" width="100px" height="100px">
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <input type="hidden" id="goods_id" name="id" value="">
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
<div class="modal inmodal fade" id="myModal5" tabindex="-1" role="dialog"  aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form action="<?php echo url('goods/editGoodsInfo'); ?>" method="post">
            <div class="modal-content">
                <div class="modal-header">
                    <!--<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title">商品详情</h4>
                </div>
                <div class="modal-body">
                    <p>
                        <script id="editor" name="content" type="text/plain" style="width:100%;height:300px;"></script>
                    </p>
                </div>
                <input id="getid" type="hidden" name="id" value="">
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
                    <button type="submit" class="btn btn-primary">保存</button>
                </div>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript" charset="utf-8" src="__PUBLIC__/ueditor/ueditor.config.js"></script>
<script type="text/javascript" charset="utf-8" src="__PUBLIC__/ueditor/ueditor.all.min.js"> </script>
<!--建议手动加在语言，避免在ie下有时因为加载语言失败导致编辑器加载失败-->
<!--这里加载的语言文件会覆盖你在配置项目里添加的语言类型，比如你在配置项目里配置的是英文，这里加载的中文，那最后就是中文-->
<script type="text/javascript" charset="utf-8" src="__PUBLIC__/ueditor/lang/zh-cn/zh-cn.js"></script>
<script src="__PUBLIC__/admin/js/jquery.min.js?v=2.1.4"></script>
<script src="__PUBLIC__/admin/js/bootstrap.min.js?v=3.3.6"></script>
<!--<script src="__PUBLIC__/admin/js/jquery.min.js"></script>-->
<script type="text/javascript">
    var ue =UE.getEditor('editor');
</script>
<script>
    $('.close').click(function(){
        $(this).parent().parent().remove();
    })
    function getGoodsDate($this,id){
        $('#getid').val(id)
        var content=$($this).attr('_content');
        ue.setContent(content)
    }
</script>
<script>
    function delGoods(id){
        var sure=confirm('你确定要删除该商品吗？');
        if(sure){
            var data={'id':id};
            var url="<?php echo url('goods/delGoods'); ?>";
            sendAjax(url,data);
        }
    }
    function editShowPic(id,show_pic){
        $('#showImg').attr('src','__PUBLIC__'+show_pic)
        $('#goods_id').val(id);
    }
    function modalEditGoods($this,id,main_pic){
        var obj=$($this).parent().parent().find('td');
        $('#name').val(obj.eq(1).html());
        $('#describes').val(obj.eq(2).html());
        $('#goods_price').val(obj.eq(5).html());
        $('#goods_brand').val(obj.eq(6).html());
        $('#goods_color').val(obj.eq(7).html());
        $('#singleImg').attr('src','__PUBLIC__'+main_pic);
        $('#myId').val(id)
    }
    $('.goodsDown').click(function(){
        var id=$(this).parent().parent().find('.did').html()
        var data={
            'id':id,
            'flag':2,
        };
        var url="<?php echo url('goods/goodsStatus'); ?>";
        sendAjax(url,data);
    })
    $('.goodsUp').click(function(){
        var id=$(this).parent().parent().find('.did').html()
        var data={
            'id':id,
            'flag':1,
        };
        var url="<?php echo url('goods/goodsStatus'); ?>";
        sendAjax(url,data);
    })
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
<script type="text/javascript">
    $("#singleFile").change(function(){
        var objUrl = getObjectURL(this.files[0]) ;
        console.log("objUrl = "+objUrl) ;
        if (objUrl) {
            $("#singleImg").attr("src", objUrl) ;
        }
    }) ;
    //建立一個可存取到該file的url
    function getObjectURL(file) {
        var url = null ;
        if (window.createObjectURL!=undefined) { // basic
            url = window.createObjectURL(file) ;
        } else if (window.URL!=undefined) { // mozilla(firefox)
            url = window.URL.createObjectURL(file) ;
        } else if (window.webkitURL!=undefined) { // webkit or chrome
            url = window.webkitURL.createObjectURL(file) ;
        }
        return url ;
    }
</script>
<script type="text/javascript">
    $("#showFile").change(function(){
        var objUrl = getObjectURL(this.files[0]) ;
        console.log("objUrl = "+objUrl) ;
        if (objUrl) {
            $("#showImg").attr("src", objUrl) ;
        }
    }) ;
    //建立一個可存取到該file的url
    function getObjectURL(file) {
        var url = null ;
        if (window.createObjectURL!=undefined) { // basic
            url = window.createObjectURL(file) ;
        } else if (window.URL!=undefined) { // mozilla(firefox)
            url = window.URL.createObjectURL(file) ;
        } else if (window.webkitURL!=undefined) { // webkit or chrome
            url = window.webkitURL.createObjectURL(file) ;
        }
        return url ;
    }
</script>