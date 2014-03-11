<?php
return array(
    //'配置项'=>'配置值'

    // 数据库配置
    'DB_TYPE'              => GreenCMS_DB_TYPE,
    'DB_HOST'              => GreenCMS_DB_HOST,
    'DB_NAME'              => GreenCMS_DB_NAME,
    'DB_USER'              => GreenCMS_DB_USR,
    'DB_PWD'               => GreenCMS_DB_PWD,
    'DB_PORT'              => GreenCMS_DB_PORT,
    'DB_PREFIX'            => GreenCMS_DB_PREFIX, //测试是为空，生产环境需要自形添加 如 green_

    'AUTOLOAD_NAMESPACE'   => array('Addons' => './Addons/'), //扩展模块列表

    'SHOW_PAGE_TRACE'      => false,

    'SESSION_OPTIONS'      => array(), // session 配置数组 支持type name id path expire domain 等参数
    'SESSION_PREFIX'       => '', // session 前缀

    'AUTH_CODE'            => "ZTS", //安装完毕之后不要改变，否则所有密码都会出错
    'ADMIN'                => 'admin',
    'TOKEN_ON'             => false, //TOKEN_ON

    'DATA_CACHE_TYPE'      => 'File', // 数据缓存类型,支持:File||Memcache|Xcache
    'DATA_CACHE_SUBDIR'    => true, // 使用子目录缓存 (自动根据缓存标识的哈希创建子目录)

    'URL_CASE_INSENSITIVE' => true, //URL大小写不敏感
    'URL_MODEL'            => 0,

    'TMPL_PARSE_STRING'    => array(
        '__EXTEND__' => Extend_PATH,
        //'__PUBLIC__' => 'PUBLIC', // 强制修正__PUBLIC__
        //'__ROOT__' => '',// 强制修正__ROOT__
    ),

    'TAGLIB_BUILD_IN'      => 'Green,Cx',

    // 'LOAD_EXT_CONFIG'      => '', // 加载扩展配置文件 config_alias,config_db,config_system

    /**
     * TP 3.1升级
     */

    'MODULE_ALLOW_LIST'    => array('Home', 'Admin', 'Weixin', 'Install'), //配置你原来的分组列表
    'MODULE_DENY_LIST'     => array('Common'),
    'DEFAULT_MODULE'       => 'Home',

);