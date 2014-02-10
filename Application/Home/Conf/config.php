<?php
return array(
    //静态缓存
    'HTML_CACHE_ON'        => true, //生产环境设置为开启
    'HTML_CACHE_TIME'   =>    600,   // 全局静态缓存有效期（秒）
    'HTML_CACHE_RULES'     => array(
        '*' => array('{$_SERVER.REQUEST_URI}', '36000', ''), //全局静态缓存，第二个参数为时间单位秒
    ),

    'SHOW_PAGE_TRACE'      => APP_DEBUG,

    'MAINTAIN'        => false,

    'DEFAULT_THEME'   => 'Vena',

    'LOAD_EXT_CONFIG' => 'config_router', // 加载扩展配置文件

);