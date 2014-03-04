<?php
return array(
    //静态缓存
    'HTML_CACHE_ON'    => get_opinion('HTML_CACHE_ON',true,false), //生产环境设置为开启
    'HTML_CACHE_TIME'  => 600, // 全局静态缓存有效期（秒）
    'HTML_CACHE_RULES' => array(
        '*' => array('{$_SERVER.REQUEST_URI|md5}', '36000', ''), //全局静态缓存，第二个参数为时间单位秒
    ),

    'DEFAULT_THEME'    => get_kv('home_theme',false,'Vena'),

    'LOAD_EXT_CONFIG'  => 'config_router', // 加载扩展配置文件



// 数据库配置
    'DB_TYPE'   => GreenCMS_DB_TYPE,
    'DB_HOST'   => GreenCMS_DB_HOST,
    'DB_NAME'   => GreenCMS_DB_NAME,
    'DB_USER'   => GreenCMS_DB_USR,
    'DB_PWD'    => GreenCMS_DB_PWD,
    'DB_PORT'   => GreenCMS_DB_PORT,
    'DB_PREFIX' => GreenCMS_DB_PREFIX, //测试是为空，生产环境需要自形添加 如 green_

);