<?php
/**
 * Created by Green Studio.
 * File: WidgetWidget.class.php
 * User: TianShuo
 * Date: 14-1-24
 * Time: 上午9:50
 */

namespace Home\Widget;

use Common\Logic\CatsLogic;
use Common\Logic\TagsLogic;
use Think\Controller;

/**
 * Widget
 * Class WidgetWidget
 * @package Home\Widget
 */
class WidgetWidget extends Controller
{

    /**
     * 搜索框
     * @usage {:W('Widget/search')}
     */
    public function search()
    {

        $this->display('Widget:search');

    }

    /**
     * 关于我们
     * @usage {:W('Widget/aboutUs')}
     */
    public function aboutUs()
    {

        $about_us = get_opinion('widget_about_us');

        $this->assign('widget_about_us', $about_us);
        $this->display('Widget:aboutus');
    }

    /**
     * 所有分类
     * @usage {:W('Widget/categories')}
     */
    public function categories()
    {
        $CatList = new CatsLogic();

        $this->assign('list', $CatList->category());

        $this->display('Widget:categories');
    }

    /**
     * 所有标签
     * @usage {:W('Widget/tag')}
     */
    public function tag()
    {

        $TagList = new TagsLogic();

        $tag_res = $TagList->getList(50, false);

        $this->assign('tagClouds', $tag_res);

        $this->display('Widget:tag');

    }


    /**
     * 所有链接
     * @usage {:W('Widget/links')}
     */
    public function links()
    {
        $this->display('Widget:links');

    }


}