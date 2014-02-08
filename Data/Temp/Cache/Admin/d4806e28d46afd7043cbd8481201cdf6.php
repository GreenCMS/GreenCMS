<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<!--[if IE 8]>
<html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]>
<html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en"> <!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title><?php echo ($action); ?> | <?php echo get_opinion('title');?></title>
    <meta name="description" content="GreenCMS"/>
    <meta name="keywords" content="GreenCMS"/>
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
<link href="/Green2013/Public/admin/assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
<link href="/Green2013/Public/admin/assets/plugins/bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet"
      type="text/css"/>
<link href="/Green2013/Public/admin/assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet"
      type="text/css"/>
<link href="/Green2013/Public/admin/assets/css/style-metro.css" rel="stylesheet" type="text/css"/>
<link href="/Green2013/Public/admin/assets/css/style.css" rel="stylesheet" type="text/css"/>
<link href="/Green2013/Public/admin/assets/css/style-responsive.css" rel="stylesheet" type="text/css"/>
<link href="/Green2013/Public/admin/assets/css/themes/default.css" rel="stylesheet" type="text/css" id="style_color"/>
<link href="/Green2013/Public/admin/assets/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css"/>
<!-- END GLOBAL MANDATORY STYLES -->
<!-- BEGIN PAGE LEVEL STYLES -->
<link rel="stylesheet" type="text/css" href="/Green2013/Public/admin/assets/plugins/select2/select2_metro.css"/>
<link rel="stylesheet" href="/Green2013/Public/admin/assets/plugins/data-tables/DT_bootstrap.css"/>

<link href="/Green2013/Public/admin/assets/plugins/bootstrap-fileupload/bootstrap-fileupload.css" rel="stylesheet"
      type="text/css"/>
<link href="/Green2013/Public/admin/assets/plugins/chosen-bootstrap/chosen/chosen.css" rel="stylesheet"
      type="text/css"/>
<link href="/Green2013/Public/admin/assets/css/pages/profile.css" rel="stylesheet" type="text/css"/>
<link href="/Green2013/Public/css/tab.css" rel="stylesheet" type="text/css"/>

<!-- END PAGE LEVEL STYLES -->
<link rel="shortcut icon" href="/Green2013/Public/images/logo.png"/>


</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<body class="page-header-fixed">
<!-- BEGIN HEADER -->
<div class="header navbar navbar-inverse navbar-fixed-top">
    <!-- BEGIN TOP NAVIGATION BAR -->
    <?php echo W('Common/header');?>
    <!-- END TOP NAVIGATION BAR -->
</div>
<!-- END HEADER -->
<!-- BEGIN CONTAINER -->
<div class="page-container row-fluid">
    <!-- BEGIN SIDEBAR -->
    <div class="page-sidebar nav-collapse collapse">
        <!-- BEGIN SIDEBAR MENU -->
        <?php echo W('Common/sideBar');?>
        <!-- END SIDEBAR MENU -->
    </div>
    <!-- END SIDEBAR -->
    <!-- BEGIN PAGE -->
    <div class="page-content">
        <!-- BEGIN PAGE CONTAINER-->
        <div class="container-fluid">
            <!-- BEGIN PAGE HEADER-->
            <div class="row-fluid">
                <div class="span12">
                    <!-- BEGIN PAGE TITLE & BREADCRUMB-->
                    <h3 class="page-title">
                        <?php echo ($action); ?>
                        <small>&nbsp;&nbsp;<?php echo (C("title")); ?></small>
                    </h3>
                    <ul class="breadcrumb">
                        <li><a href="<?php echo ($module_url); ?>"><?php echo ($module); ?></a> <i
                                class="icon-angle-right"></i></li>
                        <li><a href="<?php echo ($action_url); ?>"><?php echo ($action); ?></a></li>
                    </ul>
                    <!-- END PAGE TITLE & BREADCRUMB-->
                </div>
            </div>
            <!-- END PAGE HEADER-->
            <!-- BEGIN PAGE CONTENT-->
            <div class="row-fluid profile">
                <div class="span12">
                    <!--BEGIN TABS-->
                    <div class="tabbable tabbable-custom tabbable-full-width">
                        <div class="tab-content">
                            <div class="tab-pane row-fluid active" id="tab_1_1">

                                <div class="span9">


                                    <div class="portlet box light-grey">
                                        <div class="portlet-title">
                                            <div class="caption">
                                                <i class="icon-globe"></i><?php echo ($action); ?>
                                            </div>
                                        </div>
                                        <div class="portlet-body">
                                            <form method="post">
                                                <table
                                                        class="table table-striped table-bordered table-hover">

                                                    <thead>
                                                    <tr>
                                                        <th style="width: 8px;">选择


                                                        </th>
                                                        <th class="hidden-240">名称</th>
                                                        <th class="hidden-480">路径</th>
                                                        <th class="">缓存大小</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody id="cache_table">
                                                    <?php if(is_array($caches)): $k = 0; $__LIST__ = $caches;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$cache): $mod = ($k % 2 );++$k;?><tr>
                                                            <td width="30" align="center"><input
                                                                    class='groupclass' type="checkbox" name="cache[]"
                                                                    value="<?php echo ($key); ?>"/></td>
                                                            <td><?php echo ($cache["name"]); ?></td>
                                                            <td>(<?php echo ($cache["path"]); ?>)</td>
                                                            <td><?php echo ($cache["size"]); ?></td>
                                                        </tr><?php endforeach; endif; else: echo "" ;endif; ?>

                                                    </tbody>
                                                    <tfoot>
                                                    <tr>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                    </tr>
                                                    </tfoot>

                                                </table>


                                                <div class="form-actions" style="text-align: center">
                                                    <div class="span4">
                                                        <button type="submit" class="btn green">
                                                            <i class="m-icon-swapright m-icon-white"></i>开始清理
                                                        </button>
                                                    </div>

                                                    <div class="span2">
                                                        <button type="button" class="btn" id="checkall">全选</button>
                                                    </div>
                                                    <div class="span2">
                                                        <button type="button" class="btn red" id="check_cancel">取消
                                                        </button>
                                                    </div>
                                                    <div class="span2">
                                                        <button type="button" class="btn blue" id="check_reverse">反选
                                                        </button>
                                                    </div>
                                                </div>

                                            </form>


                                        </div>

                                    </div>
                                    <!--end span9-->
                                </div>
                                <!--end tab-pane-->
                            </div>
                        </div>
                        <!--END TABS-->
                    </div>
                </div>
                <!-- END PAGE CONTENT-->
            </div>
            <!-- END PAGE CONTAINER-->
        </div>
        <!-- END PAGE -->
    </div>
    <!-- END CONTAINER -->
    <!-- BEGIN FOOTER -->
    <?php echo W('Common/footer');?>
    <!-- END FOOTER -->

    <!-- BEGIN CORE PLUGINS -->
<script type="text/javascript" src="/green2014/Public/share/js/jquery-1.9.0.min.js"></script>
<!--<script src="/green2014/Public/admin/assets/plugins/jquery-1.10.1.min.js" type="text/javascript"></script>
-->
<script src="/green2014/Public/admin/assets/plugins/jquery-migrate-1.2.1.min.js" type="text/javascript"></script>
<!-- IMPORTANT! Load jquery-ui-1.10.1.custom.min.js before bootstrap.min.js to fix bootstrap tooltip conflict with jquery ui tooltip -->
<script src="/green2014/Public/admin/assets/plugins/jquery-ui/jquery-ui-1.10.1.custom.min.js" type="text/javascript"></script>
<script src="/green2014/Public/admin/assets/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<!--[if lt IE 9]>
<script src="/green2014/Public/admin/assets/plugins/excanvas.min.js"></script>
<script src="/green2014/Public/admin/assets/plugins/respond.min.js"></script>
<![endif]-->


<script src="/green2014/Public/admin/assets/plugins/jquery.blockui.min.js" type="text/javascript"></script>
<script src="/green2014/Public/admin/assets/plugins/jquery.cookie.min.js" type="text/javascript"></script>
<script src="/green2014/Public/admin/assets/plugins/uniform/jquery.uniform.min.js" type="text/javascript"></script>

<!-- END CORE PLUGINS -->
<!-- BEGIN PAGE LEVEL PLUGINS -->
<script type="text/javascript" src="/green2014/Public/admin/assets/plugins/select2/select2.min.js"></script>
<script type="text/javascript" src="/green2014/Public/admin/assets/plugins/data-tables/jquery.dataTables.js"></script>
<script type="text/javascript" src="/green2014/Public/admin/assets/plugins/data-tables/DT_bootstrap.js"></script>
<!-- END PAGE LEVEL PLUGINS -->
<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="/green2014/Public/admin/assets/scripts/app.js"></script>
<script src="/green2014/Public/admin/assets/scripts/table-managed.js"></script>
<!-- <script type="text/javascript"
        src="/green2014/Public/admin/assets/plugins/bootstrap-fileupload/bootstrap-fileupload.js"></script> -->
<script type="text/javascript"
        src="/green2014/Public/admin/assets/plugins/chosen-bootstrap/chosen/chosen.jquery.min.js"></script>
<script src="/green2014/Public/admin/assets/scripts/form-wizard.js"></script>
<script type="text/javascript"
        src="/green2014/Public/admin/assets/plugins/bootstrap-wizard/jquery.bootstrap.wizard.min.js"></script>
<script type="text/javascript"
        src="/green2014/Public/admin/assets/plugins/jquery-validation/dist/jquery.validate.min.js"></script>
<!---->
<script type="text/javascript" src="/green2014/Public/admin/assets/js/jquery.form.js"></script>
<script type="text/javascript" src="/green2014/Public/admin/assets/js/functions.js"></script>



    <script>

        jQuery(document).ready(function () {

            $("#cache_table :checkbox").attr("checked", true);
            App.init();
            TableManaged.init();

            $("#checkall").click(function () { //":checked"匹配所有的复选框
                $("#cache_table :checkbox").attr("checked", true); //"#div1 :checked"之间必须有空格checked是设置选中状态。如果为true则是选中fo否则false为不选中
                $("span").addClass("checked");
            });
            $("#check_cancel").click(function () {
                $("#cache_table :checkbox").attr("checked", false);
                $("span").removeClass("checked");
            });
            //理解用迭代原理each（function(){}）
            $("#check_reverse").click(function () {
                $("#cache_table :checkbox").each(function () {

                    $(this).attr("checked", !$(this).attr("checked"));
                    $(this).parentsUntil('div').toggleClass("checked");


                    //!$(this).attr("checked")判断复选框的状态：如果选中则取反
                });
            });


        });
        //

    </script>

</body>
<!-- END BODY -->
</html>