<?php
/**
 * Created by Green Studio.
 * File: config.php
 * User: TianShuo
 * Date: 14-2-6
 * Time: 下午3:01
 */

$menu_arr = array(

    'admin_big_menu' => array(
        'Home'    => '首页',
        'Reply'   => '预设回复',
//        'Menu'   => '菜单管理',
        'Rule'    => '回复规则',
        'User'    => '用户查看',
        'Message' => '消息管理',
        'System'  => '系统设置',
    ),

    'admin_sub_menu' => array(
        'Home'   => array(
            'Home/index'      => '微信仪表盘',
            'Home/changePass' => '修改密码',
        ),

        'Reply'  => array(
            'Reply/index' => '预设回复内容',
            'Reply/text'  => '文字回复',
            'Reply/pic'   => '图片回复',
            'Reply/news'  => '图文回复',
//          'Reply/music' => '音乐回复',
//          'Reply/video'        => '视频回复',
        ),


        'Menu'   => array(
            'Menu/index' => '菜单管理',
            'Menu/add'   => '菜单添加',
        ),

        'Rule'   => array(
            'Rule/index' => '规则管理',
            'Rule/add'   => '规则添加',
        ),


        'User'   => array(
            'User/index' => '用户管理',
        ),
        'Message'   => array(
            'Message/index' => '消息记录',
            'Message/new' => '尚未回复',
            'Message/send' => '发送消息',

        ),



        'System' => array(
            'System/index' => '系统设置',

        )
    )

);


$config_rbac = array(
    /*
     * RBAC认证配置信息
    */
    'USER_AUTH_ON'        => true,
    'USER_AUTH_TYPE'      => 2, // 默认认证类型 1 登录认证 2 实时认证
    'USER_AUTH_KEY'       => 'authId', // 用户认证SESSION标记
    'ADMIN_AUTH_KEY'      => 'ADMIN',
    'USER_AUTH_MODEL'     => 'User', // 默认验证数据表模型
    'AUTH_PWD_ENCODER'    => 'md5', // 用户认证密码加密方式encrypt
    'USER_AUTH_GATEWAY'   => '/Weixin/Login/index', // 默认认证网关
    'NOT_AUTH_MODULE'     => 'Public', // 默认无需认证模块
    'REQUIRE_AUTH_MODULE' => '', // 默认需要认证模块
    'NOT_AUTH_ACTION'     => '', // 默认无需认证操作
    'REQUIRE_AUTH_ACTION' => '', // 默认需要认证操作
    'GUEST_AUTH_ON'       => false, // 是否开启游客授权访问
    'GUEST_AUTH_ID'       => 0, // 游客的用户ID
    'RBAC_ROLE_TABLE'     => GreenCMS_DB_PREFIX . 'role',
    'RBAC_USER_TABLE'     => GreenCMS_DB_PREFIX . 'role_users',
    'RBAC_ACCESS_TABLE'   => GreenCMS_DB_PREFIX . 'access',
    'RBAC_NODE_TABLE'     => GreenCMS_DB_PREFIX . 'node',


);


$setting = array(

    'URL_MODEL'         => 0,
    'SHOW_PAGE_TRACE'   => false,
    'SHOW_CHROME_TRACE' => false,

    'Weixin_log'        => true,




);

$ra = array_merge($config_rbac, $menu_arr);
return array_merge($ra, $setting);