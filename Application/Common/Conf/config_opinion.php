<?php
/**
 * opinion数组预定义区
 * Created by PhpStorm.
 * User: Timothy Zhang
 * Date: 14-6-15
 * Time: 下午2:47
 */

return array(

    "url_function" => array(
        'native' => '直链http://xxxx',
        'in_site' => '站内相对链接',
        'get_post_url' => '文章Post',
        'get_cat_url' => '分类Cat',
        'get_tag_url' => '标签Tag',
        'get_channel_url' => '频道Cannel',
        'U' => 'TP原生U函数',
        'get_addon_url' => '插件链接生成',
        'get_deep_url' => '用户分类链接'
    ),

    "url_open" => array(
        '_self' => '当前页面',
        '_blank' => '新页面'
    ),

    "post_model" => array(
        'native' => '原生模式',
        'absolute' => '绝对链接',
        'year/month/day/post_id' => '年/月/日/post_id',
        'year/month/day/post_name' => '年/月/日/post_name',
        'year/month/post_id' => '年/月/post_id',
        'year/month/post_name' => '年/月/post_name',
        'year/post_id' => '年/post_id',
        'year/post_name' => '年/post_name'),

    "url_model0" => array(
        0 => '普通模式',
        1 => 'PATHINFO模式',
        2 => 'REWRITE模式',
        3 => '兼容模式'
    ),

    "tag_model" => array(
        'native' => '原生模式',
        'slug' => 'slug短语'
    ),

    "cat_model" => array(
        'native' => '原生模式',
        'slug' => 'slug短语'
    ),

    "post_tpl" => array(
        "single" => "文章",
        "page" => "页面"
    ),

    "cache_type" => array(
        "File" => "文件",
        "Memcache" => "Memcache",
        "Xcache" => "Xcache"
    ),

    "post_status" => array(
//        "all" => "全都",
        "publish" => "已发布",
        "unverified" => "待审核",
        "reverify" => "未通过",
        "draft" => "草稿",
        "preDel" => "待删除"
    ),


    "post_type" => array(
        "single" => "普通文章",
        "page" => "页面(无分类标签功能)",
    ),


);