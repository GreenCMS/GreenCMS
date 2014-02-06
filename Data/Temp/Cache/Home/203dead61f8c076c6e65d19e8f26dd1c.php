<?php if (!defined('THINK_PATH')) exit();?><!-- Main Menu / Start
================================================== -->
<header class="menu">

    <div class="container">

        <!-- Mobile Only Menu Button  -->
        <a href="#menu" class="menu-trigger"><i class="icon-reorder"></i></a>

        <!-- Logo  -->
        <a class="logo" href="<?php echo U('/');?>"><img
                src="/green2014/Public/vena/images/logo.png" alt="" title=""/></a>

        <!-- Navigation -->
        <nav>
            <ul class="navigation">
                <!-- <li class="search"><input class="searchform" type="search"
                    value="" placeholder="Search" /></li>-->
                <li class="current"><a href="<?php echo U('/');?>">首页</a></li>

                <li><a href="<?php echo getCatURLByID('15');?>">科技新闻</a></li>
                <li><a href="" class="arrow">消息公告</a>
                    <ul>
                        <li><a href="<?php echo getCatURLByID('17');?>">作品发布</a></li>
                        <li><a href="<?php echo getCatURLByID('20');?>">活动发布</a></li>
                        <li><a href="<?php echo getCatURLByID('16');?>">公告发布</a></li>
                    </ul>
                </li>
                <li><a href="<?php echo getCatURLByID('18');?>">技术分享</a></li>

                <li><a href="<?php echo getCatURLByID('19');?>">成员介绍</a></li>


                <li><a href="#" class="arrow">归档</a>
                    <ul>
                        <li><a href="http://green.njut.edu.cn">旧版入口</a></li>
                        <li><a href="<?php echo U('Archive/single');?>" class="arrow">所有文章</a>

                            <ul>

                                <?php if(is_array($posts)): $i = 0; $__LIST__ = $posts;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li><a href="<?php echo (getsingleurlbyid($vo["post_id"],'single')); ?>">
                                        <?php echo ($vo["post_title"]); ?> </a></li><?php endforeach; endif; else: echo "" ;endif; ?>

                            </ul>
                        </li>
                        <li><a href="<?php echo U('Archive/page');?>" class="arrow">所有页面</a>

                            <ul>

                                <?php if(is_array($pages)): $i = 0; $__LIST__ = $pages;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li><a href="<?php echo (getsingleurlbyid($vo["post_id"],'page')); ?>">
                                        <?php echo ($vo["post_title"]); ?> </a></li><?php endforeach; endif; else: echo "" ;endif; ?>

                            </ul>
                        </li>


                        <li><a href="#" class="arrow">所有分类</a>
                            <ul>
                                <?php if(is_array($cats)): $i = 0; $__LIST__ = $cats;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li><a href="<?php echo (getcaturlbyid($vo["cat_id"])); ?>">
                                        <?php echo ($vo["cat_slug"]); ?> </a></li><?php endforeach; endif; else: echo "" ;endif; ?>
                            </ul>
                        </li>


                        <li><a href="#" class="arrow">所有标签</a>
                            <ul>
                                <?php if(is_array($tags)): $i = 0; $__LIST__ = $tags;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li><a href="<?php echo (gettagurlbyid($vo["tag_id"])); ?>">
                                        <?php echo ($vo["tag_name"]); ?> </a></li><?php endforeach; endif; else: echo "" ;endif; ?>
                            </ul>
                        </li>


                    </ul>
                </li>


                <li><a href="" class="arrow">加入我们</a>
                    <ul>
                        <li><a href="<?php echo getSingleURLByID('95','page');?>">联系我们</a></li>
                        <li><a href="<?php echo U('Form/apply');?>">加入我们</a>
                    </ul>
                </li>


            </ul>
        </nav>
    </div>
    <!-- End of container -->
</header>
<!-- Main Menu / End
================================================== -->