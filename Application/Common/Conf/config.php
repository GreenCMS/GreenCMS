<?php
return array(
    //'配置项'=>'配置值'

    'SHOW_PAGE_TRACE' => APP_DEBUG,

    'COOKIE_PREFIX' => 'greencms_', // cookie 名称前缀
    'COOKIE_EXPIRE' => 3600, // Coodie有效期
    'COOKIE_DOMAIN' => '', // Cookie有效域名
    'COOKIE_PATH' => '/', // Cookie路径
    'SESSION_AUTO_START' => true, // 是否自动开启Session
    'SESSION_OPTIONS' => array(), // session 配置数组 支持type name id path expire domain 等参数
    'SESSION_PREFIX' => '', // session 前缀
    'DEFAULT_TIMEZONE' => 'PRC', // 默认时区
    'DEFAULT_AJAX_RETURN' => 'JSON', // 默认AJAX 数据返回格式,可选JSON XML ...


    'AUTH_CODE' => "ZTS",
    'ADMIN' => 'admin',
    'TOKEN_ON' => false, //TOKEN_ON
    'DEFAULT_FILTER' => 'htmlspecialchars', //过滤方法

    'DATA_CACHE_TYPE' => 'File', // 数据缓存类型,支持:File||Memcache|Xcache
    'DATA_CACHE_SUBDIR' => true, // 使用子目录缓存 (自动根据缓存标识的哈希创建子目录)

    'URL_CASE_INSENSITIVE' => true, //URL大小写不敏感

    //静态缓存
    'HTML_CACHE_ON' => false, //生产环境设置为开启
    'HTML_CACHE_RULES' => array(
        '*' => array('{$_SERVER.REQUEST_URI}', '36000', ''), //全局静态缓存，第二个参数为时间单位秒
    ),

    'TMPL_PARSE_STRING' => array(
        '__EXTEND__' => ExtendDir,
        //'__PUBLIC__' => 'PUBLIC', // 强制修正__PUBLIC__
        //'__ROOT__' => '',// 强制修正__ROOT__
    ),

    'DB_FIELDS_CACHE' => false,
    'DB_SQL_BUILD_CACHE' => true,


    /**
     * TP 3.1升级
     */
    'MODULE_ALLOW_LIST' => array('Home', 'Admin'), //配置你原来的分组列表
    'DEFAULT_MODULE' => 'Home', //T默认分组
    // 'DEFAULT_M_LAYER'       =>  'Logic', //默认的模型层名称


    'LOAD_EXT_CONFIG' => 'config_db,config_log,config_oauth', // 加载扩展配置文件 config_alias,config_db,config_system
);