<?php
return array(
    //'配置项'=>'配置值'

    // 数据库配置
 
    'SHOW_PAGE_TRACE'      => false,

    'SESSION_OPTIONS'      => array(), // session 配置数组 支持type name id path expire domain 等参数
    'SESSION_PREFIX'       => '', // session 前缀
    'USER_AUTH_KEY'       => 'authId', // 用户认证SESSION标记

    'AUTH_CODE'            => "ZTS", //安装完毕之后不要改变，否则所有密码都会出错
    'ADMIN'                => 'admin',//如果管理员不是admin 需要修改此项
    'TOKEN_ON'             => false, //TOKEN_ON

   
    'URL_CASE_INSENSITIVE' => true, //URL大小写不敏感
    'URL_MODEL'            => 0,

 
    'DEFAULT_MODULE'       => 'Install',
 
);