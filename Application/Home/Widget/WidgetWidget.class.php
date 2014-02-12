<?php
/**
 * Created by Green Studio.
 * File: WidgetWidget.class.php
 * User: TianShuo
 * Date: 14-1-24
 * Time: 上午9:50
 */

namespace Home\Widget;

use Think\Controller;
use Common\Logic\CatsLogic;
use Common\Logic\TagsLogic;

class WidgetWidget extends Controller
{

    public function search()
    {

        $this->display('Widget:search');

    }

    public function aboutUs()
    {

        $about_us = get_opinion('widget_about_us');

        $this->assign('widget_about_us', $about_us);
        $this->display('Widget:aboutus');
    }

    public function categories()
    {
        $CatList =  new CatsLogic();

        $this->assign('list', $CatList->category());

        $this->display('Widget:categories');
    }

    public function tag()
    {

        $TagList = new TagsLogic();

        $tag_res = $TagList->getList(50, false);

        $this->assign('tagClouds', $tag_res);

        $this->display('Widget:tag');

    }


}