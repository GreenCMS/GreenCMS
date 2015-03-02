<?php
/**
 * Created by PhpStorm.
 * User: Timothy Zhang
 * Date: 14-6-1
 * Time: 下午10:02
 */
return array(

    'DATA_CACHE_TYPE' => get_opinion('DATA_CACHE_TYPE', false, 'File'), // 数据缓存类型,支持:File||Memcache|Xcache
    'DATA_CACHE_TIME' => get_opinion("DATA_CACHE_TIME", false, 10),
    'DEFAULT_FILTER' => get_opinion('DEFAULT_FILTER', false, 'htmlspecialchars'),
    'SHOW_PAGE_TRACE' => get_opinion('SHOW_PAGE_TRACE', false, false),
    'SHOW_CHROME_TRACE' => get_opinion('SHOW_CHROME_TRACE', false, false),


    'COOKIE_PREFIX' => get_opinion('COOKIE_PREFIX', false, 'greencms_'),
    'COOKIE_EXPIRE' => get_opinion('COOKIE_EXPIRE', false, 3600),
    'COOKIE_DOMAIN' => get_opinion('COOKIE_DOMAIN', false),
    'COOKIE_PATH' => get_opinion('COOKIE_PATH', false),


    'LOG_RECORD' => get_opinion('LOG_RECORD', false),
    'LOG_LEVEL' => get_opinion('LOG_LEVEL', false, ''),


    /*
     * RBAC认证配置信息
    */
    'USER_AUTH_ON' => true,
    'USER_AUTH_TYPE' => 2, // 默认认证类型 1 登录认证 2 实时认证
    'USER_AUTH_KEY' => 'authId', // 用户认证SESSION标记
    'ADMIN_AUTH_KEY' => 'ADMIN',
    'USER_AUTH_MODEL' => 'User', // 默认验证数据表模型
    'AUTH_PWD_ENCODER' => 'md5', // 用户认证密码加密方式encrypt
    'USER_AUTH_GATEWAY' => '?s=/Admin/Login/index', // 默认认证网关
    'NOT_AUTH_MODULE' => 'Public', // 默认无需认证模块
    'REQUIRE_AUTH_MODULE' => '', // 默认需要认证模块
    'NOT_AUTH_ACTION' => '', // 默认无需认证操作
    'REQUIRE_AUTH_ACTION' => '', // 默认需要认证操作
    'GUEST_AUTH_ON' => false, // 是否开启游客授权访问
    'GUEST_AUTH_ID' => 0, // 游客的用户ID
    'RBAC_ROLE_TABLE' => GreenCMS_DB_PREFIX . 'role',
    'RBAC_USER_TABLE' => GreenCMS_DB_PREFIX . 'role_users',
    'RBAC_ACCESS_TABLE' => GreenCMS_DB_PREFIX . 'access',
    'RBAC_NODE_TABLE' => GreenCMS_DB_PREFIX . 'node',

    'LOAD_EXT_CONFIG' => 'config_Oauth', // 加载扩展配置文件 config_alias,config_db,config_system

    "URL_MODEL" => 3,

);