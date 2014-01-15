<?php
/**
 * Created by Green Studio.
 * File: config.php
 * User: TianShuo
 * Date: 14-1-15
 * Time: 下午11:23
 */


$menu_arr = array (

    'admin_big_menu' => array (
        'Index' => '首页',
        'Posts' => '文章管理',
        'Sysdata' => '数据管理',
        'Access' => '权限管理',
        'Webinfo' => '系统设置',
        'Filemanage' => '文件管理'
    )
,
    'admin_sub_menu' => array (
        'Index' => array (
            'Index/index' => '仪表盘',
            'Index/myInfo' => '修改密码',
            'Index/cache' => '缓存清理',
            'Posts/add' => '文章发布',
            'Index/main' => '返回首页'
        )
    ,

        'Posts' => array (
            'Posts/index' => '文章列表',
            'Posts/page' => '页面列表',
            'Posts/category' => '分类管理',
            'Posts/tag' => '标签管理',
            'Posts/add' => '发布文章',
            'Posts/unverified' => '待审核',
            'Posts/recycle' => '回收站'
        )
    ,
        'Sysdata' => array (
            'Sysdata/index' => '数据库备份',
            'Sysdata/restore' => '数据库导入',
            'Sysdata/zipList' => '数据库压缩包',
            'Sysdata/repair' => '数据库优化修复'
        )
    ,
        'Filemanage' => array (

            'Filemanage/index' => '文件管理'
        )
    ,

        'Access' => array (
            'Access/index' => '用户管理',
            'Access/guest' => '游客管理',
            'Access/nodelist' => '节点管理',
            'Access/rolelist' => '角色管理',
            // 'Access/addAdmin' => '添加管理员',
            'Access/addUser' => '添加用户',
            'Access/addNode' => '添加节点',
            'Access/addRole' => '添加角色'
        ),
        'Webinfo' => array (
            'Webinfo/index' => '站点设置',
            'Webinfo/special' => '附加设置',
            'Webinfo/setEmailConfig' => '邮箱配置',
            'Webinfo/setSafeConfig' => '安全选项',
            'Webinfo/plugin' => '插件管理',
            'Webinfo/links' => '链接管理',
            'Webinfo/update' => '系统升级',
            'Webinfo/info' => '系统信息'
        )
    )

)
;

$config_rbac = array(
    /*
     * RBAC认证配置信息
    */
    'USER_AUTH_ON' => true,
    'USER_AUTH_TYPE' => 2, // 默认认证类型 1 登录认证 2 实时认证
    'USER_AUTH_KEY' => 'authId', // 用户认证SESSION标记
    'ADMIN_AUTH_KEY' => 'ADMIN',
    'USER_AUTH_MODEL' => 'User', // 默认验证数据表模型
    'AUTH_PWD_ENCODER' => 'md5', // 用户认证密码加密方式encrypt
    'USER_AUTH_GATEWAY' => 'Admin/Login/index', // 默认认证网关
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
    /*
     * 系统备份数据库时每个sql分卷大小，单位字节
    */
    'sqlFileSize' => 5242880  ,
    // (测试功能)该值不可太大，否则会导致恢复失败，建议5M一卷
    // 5M=5*1024*1024=5242880

);

return array_merge ( $config_rbac, $menu_arr );