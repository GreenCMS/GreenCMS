<?php
return array(
    //'配置项'=>'配置值'

    // 数据库配置
    'DB_TYPE' => GreenCMS_DB_TYPE,
    'DB_HOST' => GreenCMS_DB_HOST,
    'DB_NAME' => GreenCMS_DB_NAME,
    'DB_USER' => GreenCMS_DB_USR,
    'DB_PWD' => GreenCMS_DB_PWD,
    'DB_PORT' => GreenCMS_DB_PORT,
    'DB_PREFIX' => GreenCMS_DB_PREFIX, //测试是为空，生产环境需要自形添加 如 green_
    //   'DB_DSN' => 'pgsql:host=localhost;port=5432;dbname=green;',

    'DB_SQL_BUILD_CACHE'    =>  true, // 数据库查询的SQL创建缓存



    'AUTOLOAD_NAMESPACE' => array('Addons' => './Addons/'), //扩展模块列表

    'SHOW_PAGE_TRACE' => false,

    'SESSION_OPTIONS' => array(), // session 配置数组 支持type name id path expire domain 等参数
    'SESSION_PREFIX' => '', // session 前缀
    'USER_AUTH_KEY' => 'authId', // 用户认证SESSION标记

    'AUTH_CODE' => "ZTS", //安装完毕之后不要改变，否则所有密码都会出错
    'ADMIN' => 'admin',//如果管理员不是admin 需要修改此项
    'TOKEN_ON' => false, //TOKEN_ON

    'DATA_CACHE_SUBDIR' => true, // 使用子目录缓存 (自动根据缓存标识的哈希创建子目录)

    'URL_CASE_INSENSITIVE' => true, //URL大小写不敏感
    'URL_MODEL' => 0,

    'TMPL_PARSE_STRING' => array(
        '__EXTEND__' => Extend_PATH,
        //'__PUBLIC__' => 'PUBLIC', // 强制修正__PUBLIC__
        //'__ROOT__' => '',// 强制修正__ROOT__
    ),

    'TAGLIB_BUILD_IN' => 'Green,Cx',

    'LOAD_EXT_CONFIG' => 'config_opinion,config_node,config_custom,config_oem', // 加载扩展配置文件 config_alias,config_db,config_system

    'DEFAULT_MODULE' => 'Home',

    'VAR_FILTERS' => 'remove_xss',
    'DEFAULT_FILTER' => 'htmlspecialchars',


    'MODULE_ALLOW_LIST' => array('Home', 'Admin', 'Weixin', 'Install', 'Api', 'Zel', 'Oauth'), //配置你原来的分组列表
    'MODULE_DENY_LIST' => array('Common'),


//    'DATA_CACHE_TYPE' => 'Memcache', // 数据缓存类型,支持:File||Memcache|Xcache
//    'MEMCACHE_HOST'   =>  '127.0.0.1',
//    'MEMCACHE_PORT'   =>  '11211',
//    'DATA_CACHE_TIMEOUT'   =>  '180',

);