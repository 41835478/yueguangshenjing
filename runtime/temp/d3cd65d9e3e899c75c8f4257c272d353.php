<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:87:"C:\xampp\htdocs\yyyyy\yueguangshenjing\public/../application/admin\view\node\index.html";i:1508331166;s:90:"C:\xampp\htdocs\yyyyy\yueguangshenjing\public/../application/admin\view\public\header.html";i:1508331166;s:90:"C:\xampp\htdocs\yyyyy\yueguangshenjing\public/../application/admin\view\public\popBox.html";i:1508331166;}*/ ?>
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
                <a class="collapse-link" href="<?php echo url('node/nodeView'); ?>">
                    <button type="button" class="btn btn-outline btn-success">添加节点</button>
                </a>
                <a class="close-link" href="">
                    <i class="fa fa-refresh" href="javascript:void(0)" onclick="location.reload()" style="color:black">刷新</i>
                </a>
            </div>
        </div>
        <div class="ibox-content">

            <div id="jstree1">
                <ul>
                    <li class="jstree-open">H+主题
                        <ul>
                            <li>css
                                <ul>
                                    <li data-jstree='{"type":"css"}'>animate.css</li>
                                    <li data-jstree='{"type":"css"}'>bootstrap.css</li>
                                    <li data-jstree='{"type":"css"}'>style.css</li>
                                </ul>
                            </li>
                            <li>email-templates
                                <ul>
                                    <li data-jstree='{"type":"html"}'>action.html</li>
                                    <li data-jstree='{"type":"html"}'>alert.html</li>
                                    <li data-jstree='{"type":"html"}'>billing.html</li>
                                </ul>
                            </li>
                            <li>fonts
                                <ul>
                                    <li data-jstree='{"type":"svg"}'>glyphicons-halflings-regular.eot</li>
                                    <li data-jstree='{"type":"svg"}'>glyphicons-halflings-regular.svg</li>
                                    <li data-jstree='{"type":"svg"}'>glyphicons-halflings-regular.ttf</li>
                                    <li data-jstree='{"type":"svg"}'>glyphicons-halflings-regular.woff</li>
                                </ul>
                            </li>
                            <li class="jstree-open">img
                                <ul>
                                    <li data-jstree='{"type":"img"}'>profile_small.jpg</li>
                                    <li data-jstree='{"type":"img"}'>angular_logo.png</li>
                                    <li class="text-navy" data-jstree='{"type":"img"}'>html_logo.png</li>
                                    <li class="text-navy" data-jstree='{"type":"img"}'>mvc_logo.png</li>
                                </ul>
                            </li>
                            <li class="jstree-open">js
                                <ul>
                                    <li data-jstree='{"type":"js"}'>hplus.js</li>
                                    <li data-jstree='{"type":"js"}'>bootstrap.js</li>
                                    <li data-jstree='{"type":"js"}'>jquery-2.1.1.js</li>
                                    <li data-jstree='{"type":"js"}'>jquery-ui.custom.min.js</li>
                                    <li class="text-navy" data-jstree='{"type":"js"}'>jquery-ui-1.10.4.min.js</li>
                                </ul>
                            </li>
                            <li data-jstree='{"type":"html"}'>affix.html</li>
                            <li data-jstree='{"type":"html"}'>dashboard.html</li>
                            <li data-jstree='{"type":"html"}'>buttons.html</li>
                            <li data-jstree='{"type":"html"}'>calendar.html</li>
                            <li data-jstree='{"type":"html"}'>contacts.html</li>
                            <li data-jstree='{"type":"html"}'>css_animation.html</li>
                            <li class="text-navy" data-jstree='{"type":"html"}'>flot_chart.html</li>
                            <li class="text-navy" data-jstree='{"type":"html"}'>google_maps.html</li>
                            <li data-jstree='{"type":"html"}'>icons.html</li>
                            <li data-jstree='{"type":"html"}'>inboice.html</li>
                            <li data-jstree='{"type":"html"}'>login.html</li>
                            <li data-jstree='{"type":"html"}'>mailbox.html</li>
                            <li data-jstree='{"type":"html"}'>profile.html</li>
                            <li class="text-navy" data-jstree='{"type":"html"}'>register.html</li>
                            <li data-jstree='{"type":"html"}'>timeline.html</li>
                            <li data-jstree='{"type":"html"}'>video.html</li>
                            <li data-jstree='{"type":"html"}'>widgets.html</li>
                        </ul>
                    </li>
                </ul>
            </div>

        </div>
    </div>
</div>


<script type="text/javascript" src="__PUBLIC__/admin/editor/kindeditor.js"></script>

<script type="text/javascript" charset="utf-8" src="__PUBLIC__/ueditor/ueditor.config.js"></script>
<script type="text/javascript" charset="utf-8" src="__PUBLIC__/ueditor/ueditor.all.min.js"> </script>
<!--建议手动加在语言，避免在ie下有时因为加载语言失败导致编辑器加载失败-->
<!--这里加载的语言文件会覆盖你在配置项目里添加的语言类型，比如你在配置项目里配置的是英文，这里加载的中文，那最后就是中文-->
<script type="text/javascript" charset="utf-8" src="__PUBLIC__/ueditor/lang/zh-cn/zh-cn.js"></script>
<!-- <script src="__PUBLIC__/admin/js/jquery.min.js?v=2.1.4"></script> -->
<script src="__PUBLIC__/admin/js/bootstrap.min.js?v=3.3.6"></script>
<script src="__PUBLIC__/admin/js/jquery.min.js"></script>
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
    $(document).ready(function () {

        $('#jstree1').jstree({
            'core': {
                'check_callback': true
            },
            'plugins': ['types', 'dnd'],
            'types': {
                'default': {
                    'icon': 'fa fa-folder'
                },
                'html': {
                    'icon': 'fa fa-file-code-o'
                },
                'svg': {
                    'icon': 'fa fa-file-picture-o'
                },
                'css': {
                    'icon': 'fa fa-file-code-o'
                },
                'img': {
                    'icon': 'fa fa-file-image-o'
                },
                'js': {
                    'icon': 'fa fa-file-text-o'
                }

            }
        });

        $('#using_json').jstree({
            'core': {
                'data': [
                    'Empty Folder',
                    {
                        'text': 'Resources',
                        'state': {
                            'opened': true
                        },
                        'children': [
                            {
                                'text': 'css',
                                'children': [
                                    {
                                        'text': 'animate.css',
                                        'icon': 'none'
                                    },
                                    {
                                        'text': 'bootstrap.css',
                                        'icon': 'none'
                                    },
                                    {
                                        'text': 'main.css',
                                        'icon': 'none'
                                    },
                                    {
                                        'text': 'style.css',
                                        'icon': 'none'
                                    }
                                ],
                                'state': {
                                    'opened': true
                                }
                            },
                            {
                                'text': 'js',
                                'children': [
                                    {
                                        'text': 'bootstrap.js',
                                        'icon': 'none'
                                    },
                                    {
                                        'text': 'hplus.min.js',
                                        'icon': 'none'
                                    },
                                    {
                                        'text': 'jquery.min.js',
                                        'icon': 'none'
                                    },
                                    {
                                        'text': 'jsTree.min.js',
                                        'icon': 'none'
                                    },
                                    {
                                        'text': 'custom.min.js',
                                        'icon': 'none'
                                    }
                                ],
                                'state': {
                                    'opened': true
                                }
                            },
                            {
                                'text': 'html',
                                'children': [
                                    {
                                        'text': 'layout.html',
                                        'icon': 'none'
                                    },
                                    {
                                        'text': 'navigation.html',
                                        'icon': 'none'
                                    },
                                    {
                                        'text': 'navbar.html',
                                        'icon': 'none'
                                    },
                                    {
                                        'text': 'footer.html',
                                        'icon': 'none'
                                    },
                                    {
                                        'text': 'sidebar.html',
                                        'icon': 'none'
                                    }
                                ],
                                'state': {
                                    'opened': true
                                }
                            }
                        ]
                    },
                    'Fonts',
                    'Images',
                    'Scripts',
                    'Templates',
                ]
            }
        });

    });
</script>
<script type="text/javascript">
    //实例化编辑器
    var ue = UE.getEditor('editor');
</script>
<script>
    $('.close').click(function(){
        $(this).parent().parent().remove();
    })

</script>

</body>
</html>