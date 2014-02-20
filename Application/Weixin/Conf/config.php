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
        'Home'   => '首页',
        'Reply'  => '回复管理',
//        'Menu'   => '菜单管理',
        'Rule'   => '回复规则',
        'User'  => '用户查看',
        'System' => '系统设置',
    ),

    'admin_sub_menu' => array(
        'Home'   => array(
            'Home/index'      => '仪表盘',
            'Home/changePass' => '修改密码',
            'Posts/add'       => '文章发布',
        ),

        'Reply'  => array(
            'Reply/index' => '回复管理',
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


        'User'  => array(
            'User/index' => '用户管理',
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

    'URL_MODEL'                => 0,
    'SHOW_PAGE_TRACE'          => false,
    'SHOW_CHROME_TRACE'        => false,

    'Weixin_reply_subscribe'   => '欢迎使用Z的博客微信服务平台！回复help获得使用帮助',
    'Weixin_reply_unsubscribe' => '欢迎下次关注',

    'Weixin_log'               => true,
    'Weixin_appid'             => 'wx7c7c42e93b3cae50',
    'Weixin_secret'            => 'a489beed09bb0d201d3e35396ef0cbc0',
    'Weixin_menu'              => '{   "button": [
                  {
                      "name": "品牌介绍",
                      "sub_button": [
                          {
                              "type": "click",
                              "name": "品牌介绍",
                              "key": "brand"
                          },
                          {
                              "type": "click",
                              "name": "产品分类",
                              "key": "category"
                          },
                          {
                              "type": "click",
                              "name": "努力时间轴",
                              "key": "timeline"
                          },
                          {
                              "type": "click",
                              "name": "TEST2",
                              "key": "mtest"
                          }
                      ]
                  },
                  {
                      "name": "最新活动",
                      "sub_button": [
                          {
                              "type": "click",
                              "name": "节日优惠",
                              "key": "discount"
                          },
                          {
                              "type": "click",
                              "name": "用户测评",
                              "key": "assess"
                          },
                          {
                              "type": "click",
                              "name": "礼品方案",
                              "key": "gifts"
                          }
                      ]
                  },
                  {
                      "name": "我的园艺",
                      "sub_button": [
                          {
                              "type": "click",
                              "name": "园艺心得",
                              "key": "gain"
                          },
                          {
                              "type": "click",
                              "name": "花友平台",
                              "key": "platform"
                          },
                          {
                              "type": "click",
                              "name": "售后反馈",
                              "key": "feedback"
                          },
                          {
                              "type": "click",
                              "name": "联系我们",
                              "key": "contact"
                          }
                      ]
                  }
              ]
          }',

);

$ra = array_merge($config_rbac, $menu_arr);
return array_merge($ra, $setting);