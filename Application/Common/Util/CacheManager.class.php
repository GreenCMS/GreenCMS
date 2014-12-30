<?php
/**
 * Created by PhpStorm.
 * User: zts1993
 * Date: 2014/12/30
 * Time: 13:20
 */

namespace Common\Util;


use Think\Cache;

class CacheManager
{

    /**
     * List of GreenCMS Cache managed by CacheManager
     *
     * Widget_mainMenu
     * Widget_tag
     * Widget_categories
     *
     */

    static function clearMenu()
    {

        S("Widget_mainMenu", null);
        S("MenuList", null);

    }

    static function clearTag()
    {

        S("Widget_tag", null);
        S("TagList", null);

    }

    static function clearCat()
    {

        S("Widget_categories", null);

    }

    static function clearPost()
    {

        S("PostList", null);

    }


    static function clearLink()
    {

        S("LinkList", null);

    }




    static function clearAll()
    {
        $Cache = new Cache();
        $caches = $Cache->connect();
        $caches->clear();
    }

    static function clear($key)
    {
        S($key, null);
    }


}