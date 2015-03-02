<?php
if (APP_DEBUG) {
    $DEFAULT_THEME = I('get.theme', get_kv('home_theme', false, 'NovaGreenStudio'));
} else {
    $DEFAULT_THEME = get_kv('home_theme', false, 'NovaGreenStudio');
}

$config = array(
    //静态缓存
    'HTML_CACHE_ON' => get_opinion('HTML_CACHE_ON', false, false), //生产环境设置为开启
    'HTML_CACHE_TIME' => get_opinion('HTML_CACHE_TIME', false, 60),
    'HTML_CACHE_RULES' => array(
        '*' => array('{$_SERVER.REQUEST_URI|md5}'), //全局静态缓存，第二个参数为时间单位秒
    ),

    'URL_MODEL' => get_opinion('home_url_model', false, 0),
    'DEFAULT_THEME' => $DEFAULT_THEME,

    'LOG_LEVEL' => get_opinion('LOG_LEVEL', false),
    'LOG_RECORD' => (bool)get_opinion('LOG_RECORD', false),

    //'DATA_CACHE_TYPE' => get_opinion('DATA_CACHE_TYPE', false, 'File'), // 数据缓存类型,支持:File||Memcache|Xcache

//    'DATA_CACHE_TYPE' => 'Memcache', // 数据缓存类型,支持:File||Memcache|Xcache
//    'MEMCACHE_HOST'   =>  '127.0.0.1',
//    'MEMCACHE_PORT'   =>  '11211',


    'DATA_CACHE_TIME' => get_opinion("DATA_CACHE_TIME", false, 10),
    'DEFAULT_FILTER' => get_opinion('DEFAULT_FILTER', false, 'htmlspecialchars'),
    'SHOW_PAGE_TRACE' => get_opinion('SHOW_PAGE_TRACE', false, false),
//    'SHOW_CHROME_TRACE' => get_opinion('SHOW_CHROME_TRACE', false, false),


    'COOKIE_PREFIX' => get_opinion('COOKIE_PREFIX', false, 'greencms_'),
    'COOKIE_EXPIRE' => get_opinion('COOKIE_EXPIRE', false, 3600),
    'COOKIE_DOMAIN' => get_opinion('COOKIE_DOMAIN', false),
    'COOKIE_PATH' => get_opinion('COOKIE_PATH', false),


    //COOKIE_DOMAIN in db is empty cause db hit when init


);

$config_router = array(

    'URL_HTML_SUFFIX' => get_opinion('URL_HTML_SUFFIX', false, 'html'),
    //URL模式

    //开启路由!!建议url模型选择2，否则的话建议使用native模式
    'URL_ROUTER_ON' => true,

    //路由定义
    'URL_ROUTE_RULES' => array(
        'Post/single/info/:info' => 'Post/single', //普通规则路由
        'Post/single/:year/:month/:day/:info' => 'Post/single', //年月日规则路由
        'Post/single/:year/:month/:info' => 'Post/single', //年月规则路由
        'Post/single/:year/:info' => 'Post/single', //年规则路由

        'Post/single/:info' => 'Post/single', //普通规则路由

        'Post/page/info/:info' => 'Post/page', //普通规则路由
        'Post/page/:year/:month/:day/:info' => 'Post/page', //年月日规则路由
        'Post/page/:year/:month/:info' => 'Post/page', //年月规则路由
        'Post/page/:year/:info' => 'Post/page', //年规则路由
        'Post/page/:info' => 'Post/page', //普通规则路由

        'Tag/:info' => 'Tag/detail', //普通规则路由


        'Cat/:father1^channel/:father2/:info' => 'Cat/detail', //普通规则路由
        'Cat/:father^channel/:info' => 'Cat/detail', //普通规则路由
        'Cat/:info^channel' => 'Cat/detail', //普通规则路由jius


    ),

    /**
     * 注意！顺序不能乱 只有不符合第一个条件才回去匹配第二个
     */
    /**
     * 文章链接模式
     * @var
     * native,
     * post_id,
     * post_name,
     * year/month/day/post_name,
     * year/month/day/post_id,
     * year/month/post_name,
     * year/month/post_id,
     * year/post_name,
     * year/post_id,
     * */
    'OUR_URL_MODEL' => "native", //Single单页路由模式


    /**
     * 标签链接模式
     * @var
     * native
     * ID
     * slug
     */
    //TODO 当前只有native模式可以使用
    'OUR_TAG_MODEL' => "native", //TAG标签路由模式.


    /**
     * 分类链接模式
     * @var
     * native
     * ID
     * slug
     */
    //TODO 当前只有native模式可以使用
    'OUR_CAT_MODEL' => "native", //TAG标签路由模式.


);


return array_merge($config, $config_router);