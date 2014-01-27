<?php
/**
 * Created by Green Studio.
 * File: ArchiveController.class.php
 * User: TianShuo
 * Date: 14-1-21
 * Time: 下午9:33
 */

namespace Home\Controller;
use Common\Util\GreenPage;

/**
 * Class ArchiveController
 * @package Home\Controller
 */
class ArchiveController extends HomeBaseController
{

    /**
     *初始化
     */
    function __construct()
    {
        parent::__construct();

    }

    /**
     * @param string $keyword 关键字
     */
    public function search($keyword = '')
    {
        $info['post_content|post_title'] = array('like', "%$keyword%");

        $PostsList = D('Posts', 'Logic');
        $count = $PostsList->countAll('all', $info); // 查询满足要求的总记录数

        ($count == 0) ? $res404 = 0 : $res404 = 1;
        if (!empty($posts_id)) {
            $Page = new GreenPage($count, C('PAGER')); // 实例化分页类 传入总记录数
            $pager_bar = $Page->show();
            $limit = $Page->firstRow . ',' . $Page->listRows;

            $res = $PostsList->getList($limit, 'all', 'post_id desc', true, $info);
        }
        $this->assign('title', '关于"' . $keyword . '"文章搜索结果');
        $this->assign('res404', $res404);
        $this->assign('postslist', $res);
        $this->assign('page', $pager_bar);

        $this->display('single-list');

    }

    /**
     * 文章归档
     */
    public function single()
    {
        //TODO year/month/day 按日月归档
        $PostsList = D('Posts', 'Logic');

        $count = $PostsList->countAll(); // 查询满足要求的总记录数
        ($count == 0) ? $res404 = 0 : $res404 = 1;
        if (!empty($posts_id)) {
            $Page = new GreenPage($count, C('PAGER'));
            $pager_bar = $Page->show();
            $limit = $Page->firstRow . ',' . $Page->listRows;

            $res = $PostsList->getList($limit, 'single', 'post_id desc', true);
        }
        $this->assign('title', '所有文章');
        $this->assign('res404', $res404); // 赋值数据集
        $this->assign('postslist', $res); // 赋值数据集
        $this->assign('page', $pager_bar); // 赋值分页输出

        $this->display('single-list');
    }


    /**
     * 页面归档
     */
    public function page()
    {
        //TODO year/month/day  按日月归档

        $PostsList = D('Posts', 'Logic');

        $count = $PostsList->countAll('page'); // 查询满足要求的总记录数
        ($count == 0) ? $res404 = 0 : $res404 = 1;

        $Page = new GreenPage($count, C('PAGER'));
        $pager_bar = $Page->show();
        $limit = $Page->firstRow . ',' . $Page->listRows;

        $res = $PostsList->getList($limit, 'page', 'post_id desc', true);

        $this->assign('title', '所有页面');
        $this->assign('res404', $res404); // 赋值数据集
        $this->assign('postslist', $res); // 赋值数据集
        $this->assign('page', $pager_bar); // 赋值分页输出

        $this->display('single-list');
    }

}