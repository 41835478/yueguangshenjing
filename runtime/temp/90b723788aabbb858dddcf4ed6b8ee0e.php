<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:92:"C:\xampp\htdocs\yyyyy\yueguangshenjing\public/../application/admin\view\contents\notice.html";i:1508488889;s:90:"C:\xampp\htdocs\yyyyy\yueguangshenjing\public/../application/admin\view\public\header.html";i:1508572780;s:90:"C:\xampp\htdocs\yyyyy\yueguangshenjing\public/../application/admin\view\public\popBox.html";i:1508331166;s:88:"C:\xampp\htdocs\yyyyy\yueguangshenjing\public/../application/admin\view\public\foot.html";i:1508555543;}*/ ?>
<link rel="shortcut icon" href="favicon.ico"> <link href="__PUBLIC__/admin/css/bootstrap.min.css?v=3.3.6" rel="stylesheet">
<link href="__PUBLIC__/admin/css/font-awesome.min.css?v=4.4.0" rel="stylesheet">
<link href="__PUBLIC__/admin/css/animate.css" rel="stylesheet">
<link href="__PUBLIC__/admin/css/style.css?v=4.1.0" rel="stylesheet">
<link href="__PUBLIC__/admin/css/plugins/sweetalert/sweetalert.css" rel="stylesheet">
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
<style>
    .imgBox {
        width: 100%;
        height: 100vh;
        background: rgba(0, 0, 0, 0.4);
        position: fixed;
        top: 0;
        left: 0;
        display: none;
        z-index: 200;
    }

    .imgBox img {
        display: block;
        margin: 0 auto;
        margin-top: 200px;
    }

    .img1 {
        cursor: pointer;
    }
</style>
<div class="imgBox">
    <img id="imgSrc" src=""/>
</div>
<section id="main-content">
    <div class="row">
        <div class="col-sm-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>公告列表</h5>
                    <div class="ibox-tools">
                        <a class="collapse-link btn btn-outline btn-success" href="<?php echo url('contents/create'); ?>?type=3">
                            新增公告
                        </a>
                    </div>
                </div>

                <div class="ibox-content">
                    <table class="footable table table-stripped" data-page-size="10" data-filter=#filter>
                        <thead>
                        <tr>
                            <th>公告ID</th>
                            <th>公告标题</th>
                            <th>公告内容</th>
                            <th>创建时间</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach($notice as $v): ?>
                        <tr class="gradeX">
                            <td><?php echo $v['id']; ?></td>
                            <td><?php echo $v['title']; ?></td>
                            <td><?php echo $v['content']; ?></td>
                            <td><?php echo $v['created_at']; ?></td>
                            <td>
                                <a href="<?php echo url('contents/edit'); ?>?id=<?php echo $v['id']; ?>">修改</a>  | <a onclick="delNotice('<?php echo $v['id']; ?>')">删除</a></td>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
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
    $(document).ready(function () {
        //新增
        //点击图片放大
        $(".img1").on("click", function () {
            var url_1 = $(this).attr("src");
            $("#imgSrc").attr("src", url_1);
            $(".imgBox").fadeIn(400);
        });
        $(".imgBox").on("click", function () {
            $(".imgBox").fadeOut(400);
        });

    });

    function delNotice(id) {
        if (confirm('确认删除此图片吗?')) {
            window.location.href = "<?php echo url('contents/delete'); ?>?id=" + id;
        }
    }

</script>