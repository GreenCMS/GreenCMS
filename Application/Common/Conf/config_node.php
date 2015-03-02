<?php
/**
 * Created by PhpStorm.
 * User: Timothy Zhang
 * Date: 14-6-21
 * Time: 下午6:24
 */


return array(

    'group_level_1' => array(
        'Admin' => 'CMS管理',
        'Weixin' => '微信管理',

    ),


    'admin_level_2' => array(
        'Addons' => '插件',
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


        'Addons' => array(
            'execute' => '执行插件',

        ),
        'Index' => array(
            'index' => '首页基本信息',
            'main' => '返回home',
            'checkversion' => '检查版本',
            'ajaxcron' => 'ajax定时计划触发',
            'profile' => '用户信息',
            'sns' => '社交账号绑定',
            'changepass' => '修改密码',
            'changepasshandle' => '修改密码处理',
            'updatecomplete' => '升级完成',
            'checkTodo' => '检查',
        ),

        'Posts' => array(

            'index' => '所有文章',
            'page' => '页面列表',

            'category' => '分类管理',
            'tag' => '标签管理',
            'add' => '添加文章',
            'reverify' => '未通过',
            'unverified' => '待审核',
            'recycle' => '回收站',

//todo            'stats' => '信息统计',

        ),


//        'Comments' => array(
//            'Comments/index' => '留言',
//
//        ),


        'Data' => array(
            'db' => '数据库设置',
            'index' => '数据库备份',
            'restore' => '数据库导入',
            'zipList' => '数据库压缩',
            'repair' => '数据库优化',
            'cache' => '缓存设置',
            'clear' => '缓存清理',


        ),

        'Custom' => array(
            'plugin' => '插件管理',
            'theme' => '主题管理',
            'menu' => '菜单管理',
//            'links' => '链接管理',
            'hooks' => '钩子管理',
            'linkgroup' => '链接管理',
            //      'slider'  => '轮播管理',

        ),


        'Media' => array(
            'file' => '文件管理',
            'backupFile' => '文件备份',
            'restoreFile' => '文件恢复',
        ),

        'Access' => array(
            'index' => '用户管理',
            'guest' => '游客管理',
            'nodelist' => '节点管理',
            'rolelist' => '角色管理',
            'addUser' => '添加用户',
            'addNode' => '添加节点',
            'addRole' => '添加角色',

            'loginlog' => '登陆记录',
            'log' => '操作记录',

        ),

        'Tools' => array(
            'index' => '可用工具',
            'wordpress' => '从wordpress导入',
            // 'rss'    => '从rss导入',
            // 'export'    => '导出',

        ),


        'System' => array(
            'index' => '站点设置',
            'user' => '用户设置',

            'post' => '文章设置',
            'url' => '链接设置',
            'safe' => '安全选项',
            'email' => '邮箱配置',
//            'kvset'  => '其他设置',
            'sns' => '社交登录设置',
            'green' => '强制设置',
            'update' => '系统升级',
            'info' => '系统信息',

        )
    ),


);


