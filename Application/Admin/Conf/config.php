<?php
/**
 * Created by Green Studio.
 * File: config.php
 * User: TianShuo
 * Date: 14-1-15
 * Time: 下午11:23
 */


$menu_arr = array(

    'admin_big_menu' => array(
        'Index'  => '首页',
        'Posts'  => '文章管理',
        'Data'   => '数据缓存',
        'Media'  => '文件附件',
        'Custom' => '个性设置',
        'Access' => '权限管理',
        'System' => '系统设置',
    ),

    'admin_sub_menu' => array(
        'Index'  => array(
            'Index/index'      => '仪表盘',
            'Index/changePass' => '修改密码',
            'Posts/add'        => '文章发布',
            'Index/main'       => '返回首页'
        ),

        'Posts'  => array(
            'Posts/index'      => '文章列表',
            'Posts/page'       => '页面列表',
            'Posts/category'   => '分类管理',
            'Posts/tag'        => '标签管理',
            'Posts/add'        => '发布文章',
            'Posts/unverified' => '待审核',
            'Posts/recycle'    => '回收站'
        ),

        'Data'   => array(
            'Data/db'      => '数据库设置',
            'Data/index'   => '数据库备份',
            'Data/restore' => '数据库导入',
            'Data/zipList' => '数据库压缩',
            'Data/repair'  => '数据库优化',
            'Data/cache'   => '缓存设置',
            'Data/clear'   => '缓存清理',


        ),

        'Custom' => array(
            'Custom/plugin' => '插件管理',
            'Custom/theme'  => '主题管理',
            'Custom/menu'   => '菜单管理'
        ),



        'Media'  => array(
            'Media/file'       => '文件管理',
            'Media/backupFile' => '文件备份',
            'Media/restoreFile' => '文件恢复',
        ),

        'Access' => array(
            'Access/index'    => '用户管理',
            'Access/guest'    => '游客管理',
            'Access/nodelist' => '节点管理',
            'Access/rolelist' => '角色管理',
            'Access/addUser'  => '添加用户',
            'Access/addNode'  => '添加节点',
            'Access/addRole'  => '添加角色'
        ),

        'System' => array(
            'System/index'          => '站点设置',
            'System/special'        => '附加设置',
            'System/setEmailConfig' => '邮箱配置',
            'System/setSafeConfig'  => '安全选项',
            'System/links'          => '链接管理',
//            'System/update'         => '系统升级',
            'System/info'           => '系统信息'
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
    'USER_AUTH_GATEWAY'   => '/Admin/Login/index', // 默认认证网关
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

$config_other = array(
    'URL_MODEL'       => 0,

    'SHOW_PAGE_TRACE' => false,

    /*
    * 系统备份数据库时每个sql分卷大小，单位字节
   */
    'sqlFileSize'     => 5242880,
    // (测试功能)该值不可太大，否则会导致恢复失败，建议5M一卷
    // 5M=5*1024*1024=5242880



);
$config = array_merge($config_rbac, $menu_arr);
return array_merge($config, $config_other);