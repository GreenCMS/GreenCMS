<?php
/**
 * Created by PhpStorm.
 * User: TianShuo
 * Date: 14-6-21
 * Time: 下午6:24
 */


return array(

    'group_level_1' => array(
        'Admin' => 'CMS管理',
        'Weixin' => '微信管理',

    ),


    'admin_level_2' => array(
        'Index' => '仪表盘',
        'Posts' => '文章页面',
        'Data' => '数据缓存',
        //  'Comments'=>'留言评论',
        'Media' => '文件附件',
        'Custom' => '定制中心',
        'Access' => '用户安全',
        'Tools' => '小工具',
        'System' => '系统设置',
        'Other' => '其他',
    ),

    'admin_level_3' => array(
        'Index' => array(
            'Index/index' => '基本信息',
            'Index/profile' => '用户信息',
            'Index/sns' => '社交账号绑定',
            'Index/changePass' => '修改密码',
        ),

        'Posts' => array(

            'Posts/index' => '所有文章',
            'Posts/page' => '页面列表',

            'Posts/category' => '分类管理',
            'Posts/tag' => '标签管理',
            'Posts/add' => '添加文章',
            'Posts/reverify' => '未通过',
            'Posts/unverified' => '待审核',
            'Posts/recycle' => '回收站',

//todo            'Posts/stats' => '信息统计',

        ),


//        'Comments' => array(
//            'Comments/index' => '留言',
//
//        ),


        'Data' => array(
            'Data/db' => '数据库设置',
            'Data/index' => '数据库备份',
            'Data/restore' => '数据库导入',
            'Data/zipList' => '数据库压缩',
            'Data/repair' => '数据库优化',
            'Data/cache' => '缓存设置',
            'Data/clear' => '缓存清理',


        ),

        'Custom' => array(
            'Custom/plugin' => '插件管理',
            'Custom/theme' => '主题管理',
            'Custom/menu' => '菜单管理',
//            'Custom/links' => '链接管理',
            'Custom/linkgroup' => '链接管理',
            //      'Custom/slider'  => '轮播管理',

        ),


        'Media' => array(
            'Media/file' => '文件管理',
            'Media/backupFile' => '文件备份',
            'Media/restoreFile' => '文件恢复',
        ),

        'Access' => array(
            'Access/index' => '用户管理',
            'Access/guest' => '游客管理',
            'Access/nodelist' => '节点管理',
            'Access/rolelist' => '角色管理',
            'Access/addUser' => '添加用户',
            'Access/addNode' => '添加节点',
            'Access/addRole' => '添加角色',

            'Access/loginlog' => '登陆记录',
            'Access/log' => '操作记录',

        ),

        'Tools' => array(
            'Tools/index' => '可用工具',
            'Tools/wordpress' => '从wordpress导入',
            // 'Tools/rss'    => '从rss导入',
            // 'Tools/export'    => '导出',

        ),


        'System' => array(
            'System/index' => '站点设置',
            'System/user' => '用户设置',

            'System/post' => '文章设置',
            'System/url' => '链接设置',
            'System/safe' => '安全选项',
            'System/email' => '邮箱配置',
//            'System/kvset'  => '其他设置',
            'System/sns' => '社交登录设置',
            'System/green' => '强制设置',
            'System/update' => '系统升级',
            'System/info' => '系统信息',

        )
    ),


);


