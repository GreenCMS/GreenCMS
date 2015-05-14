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
     * *******************************************
     * **********      Post       ****************
     * *******************************************
     * *******************************************
     */


    static function clearPost()
    {

        self::set("List_Post", null);

    }

    /**
     * List of GreenCMS Cache managed by CacheManager
     *
     * Widget_mainMenu
     * Widget_tag
     * Widget_categories
     *
     * 缓存命名规则
     *
     * post_detail key : post_detail_$post_id
     * cats_detail key : cats_detail_$cats_id
     * tags_detail key : tags_detail_$tags_id
     *
     */


    static function set($key, $data)
    {
        return S($key, $data, DEFAULT_EXPIRES_TIME);
//
//        if ($data == null) {
//            S($key, null);
//        }
//
//        if ($key) {
//            S($key, serialize($data));
//            return true;
//        } else {
//            return false;
//        }

    }

    static function clearPostCacheById($id)
    {

        self::clearPostCatRelation($id);
        self::clearPostTagRelation($id);

    }

    static function clearPostCatRelation($key)
    {

        return self::set("relation_post_cat_" . $key, null);

    }

    static function clearPostTagRelation($key)
    {

        return self::set("relation_post_tag_" . $key, null);

    }

    static function setPostCatRelation($key, $value)
    {

        return self::set("relation_post_cat_" . $key, $value);

    }

    static function getPostCatRelation($key)
    {
        return self::get("relation_post_cat_" . $key);
    }

    static function get($key)
    {
        return S($key);
//
//        $data = S($key);
//
//        if ($data) {
//            unserialize($data);
//        } else {
//            return false;
//        }
    }

    static function setPostTagRelation($key, $value)
    {

        return self::set("relation_post_tag_" . $key, $value);

    }

    static function getPostTagRelation($key)
    {
        return self::get("relation_post_tag_" . $key);
    }


    static function clearMenu()
    {

        self::set("Widget_mainMenu", null);
        self::set("List_Menu", null);

    }

    static function clearTag()
    {

        self::set("Widget_tag", null);
        self::set("List_Tag", null);

    }

    static function clearCat()
    {

        self::set("Widget_categories", null);

    }


    static function clearLink()
    {

        self::set("List_Link", null);

    }


    static function clearAll()
    {
        $Cache = new Cache();
        $caches = $Cache->connect();
        $caches->clear();
    }

    static function clear($key)
    {
        self::set($key, null);
    }


}