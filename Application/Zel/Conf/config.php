<?php

$config = array(
    //静态缓存
    'HTML_CACHE_ON'    => get_opinion('HTML_CACHE_ON', false, false), //生产环境设置为开启
    'HTML_CACHE_TIME'  => get_opinion('HTML_CACHE_TIME', false, 600),
    'HTML_CACHE_RULES' => array(
        '*' => array('{$_SERVER.REQUEST_URI|md5}'), //全局静态缓存，第二个参数为时间单位秒
    ),


    'SHOW_PAGE_TRACE'  => get_opinion('SHOW_PAGE_TRACE', false, false),
    'URL_MODEL'        => get_opinion('home_url_model', false, 0),

);




return array_merge($config, $config_router);