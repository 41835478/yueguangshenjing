<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:88:"C:\xampp\htdocs\yyyyy\yueguangshenjing\public/../application/admin\view\banners\add.html";i:1508382469;s:90:"C:\xampp\htdocs\yyyyy\yueguangshenjing\public/../application/admin\view\public\header.html";i:1508331166;s:90:"C:\xampp\htdocs\yyyyy\yueguangshenjing\public/../application/admin\view\public\popBox.html";i:1508331166;s:88:"C:\xampp\htdocs\yyyyy\yueguangshenjing\public/../application/admin\view\public\foot.html";i:1508331166;}*/ ?>
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
                    </div>
                    <div class="ibox-content">
                        <form action="<?php echo url('banners/save'); ?>"  method="post" class="form-horizontal" enctype="multipart/form-data" >
                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">外链地址:</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" name="links" value=""><p>外链地址,不外链填＃</p>
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">轮播图:</label>
                                <div class="col-sm-6">
                                    <img src="" alt="" id="img">
                                    <img src="http://www.lt55.cn/assets/hadmin/img/addImg.png" width="109px" height="109px" alt="" id="addMain" >
                                    <input type="file"  name="img" id="mainImg"  onchange="setImageShow('mainImg','img')" style="display:none" accept="image/*" >
                                    <div class="hr-line-dashed"></div>
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">排序:</label>
                                <div class="col-sm-6">
                                    <input type="number" class="form-control" name="sort" value="">
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
                            <input type="hidden" name="img" id="imageBanner">
                                <div class="col-sm-4 col-sm-offset-2">
                                    <button class="btn btn-primary" type="submit">保存内容</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- {__CONTENT__} -->


    </section>
    <script src="__PUBLIC__/admin/js/jquery.min.js?v=2.1.4"></script>
<script src="__PUBLIC__/admin/js/bootstrap.min.js?v=3.3.6"></script>
<script src="__PUBLIC__/admin/js/plugins/metisMenu/jquery.metisMenu.js"></script>
<script src="__PUBLIC__/admin/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>
<script src="__PUBLIC__/admin/js/plugins/layer/layer.min.js"></script>
    <script>
        $('#addMain').on('click',function(){
            $("input[name='img']").trigger('click');
        });

        function setImageShow(fileId,imgId){  //上传图片显示,input内用onchange
            var docObj = document.getElementById(fileId);
            var fileList = docObj.files;
            var imgObjPreview = document.getElementById(imgId);
            imgObjPreview.style.width="110px";
            imgObjPreview.style.height="110px";
            imgObjPreview.src = window.URL.createObjectURL(fileList[0]);
            $("#imageBanner").val(imgObjPreview.src);
            $('#addMain').css('display','none');
        }
    </script>