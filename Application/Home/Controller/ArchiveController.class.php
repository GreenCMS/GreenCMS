<?php
/**
 * Created by GreenStudio GCS Dev Team.
 * File: ArchiveController.class.php
 * User: Timothy Zhang
 * Date: 14-1-21
 * Time: 下午9:33
 */

namespace Home\Controller;

use Common\Logic\PostsLogic;
use Common\Util\File;
use Common\Util\GreenPage;

/**
 * 文章归档控制器
 * Class ArchiveController
 * @package Home\Controller
 */
class ArchiveController extends HomeBaseController
{

    /**
     * 初始化
     */
    function __construct()
    {
        parent::__construct();

    }

    /**
     * 用于搜索的关键字关键字 同时支持年月日参数传递
     * @param string $keyword 用于搜索的关键字关键字
     *
     */
    public function search($keyword = '')
    {
        $where['post_date'] = array('like', I('get.year', '%') . '-' . I('get.month', '%') . '-' . I('get.day', '%') . '%');
        $where['post_content|post_title'] = array('like', "%$keyword%");

        $PostsLogic = new PostsLogic();
        $count = $PostsLogic->countAll('all', $where); // 查询满足要求的总记录数

        ($count == 0) ? $res404 = 0 : $res404 = 1;
        if ($count != 0) {
            $Page = new GreenPage($count, get_opinion('PAGER')); // 实例化分页类 传入总记录数
            $pager_bar = $Page->show();
            $limit = $Page->firstRow . ',' . $Page->listRows; //获取分页信息

            $posts_list = $PostsLogic->getList($limit, 'all', 'post_date desc', true, $where);
        }
        $this->assign('title', '关于"' . $keyword . '"文章搜索结果');
        $this->assign('res404', $res404);
        $this->assign('postslist', $posts_list);
        $this->assign('pager', $pager_bar);


        $this->assign('breadcrumbs', get_breadcrumbs('关于"' . $keyword . '"文章搜索结果'));
        $this->display('single-list');

    }


    public function index()
    {
        $this->single();
        die();
    }


    /**
     * 文章归档 支持年月日参数传递 和用户id
     * @param null 文章归档
     */
    public function single()
    {
        $where['post_date'] = array('like', I('get.year', '%') . '-' . I('get.month', '%') . '-' . I('get.day', '%') . '%');
        //  if (I('get.year')== ''&&I('get.month')== ''&&I('get.day')== ''){unset($where);}

        if (I('get.uid') != '') $where['user_id'] = I('get.uid');

        $title_prefix = (I('get.year', '') ? I('get.year', '') . '年' : '') .
            (I('get.month', '') ? I('get.month', '') . '月' : '') . (I('get.day', '') ? I('get.day', '') . '日' : '');


        $PostsLogic = new PostsLogic();
        $count = $PostsLogic->countAll('single', $where); // 查询满足要求的总记录数

        ($count == 0) ? $res404 = 0 : $res404 = 1;
        if ($count != 0) {
            $Page = new GreenPage($count, get_opinion('PAGER')); // 实例化分页类 传入总记录数
            $pager_bar = $Page->show();
            $limit = $Page->firstRow . ',' . $Page->listRows; //获取分页信息
            $posts_list = $PostsLogic->getList($limit, 'single', 'post_date desc', true, $where);
        }
        $this->assign('title', $title_prefix . '所有文章');
        $this->assign('res404', $res404); // 赋值数据集
        $this->assign('postslist', $posts_list); // 赋值数据集
        $this->assign('pager', $pager_bar); // 赋值分页输出
        $this->assign('breadcrumbs', get_breadcrumbs('所有文章'));

        $this->display('single-list');
    }


    /**
     * 页面归档 支持年月日参数传递 和用户id
     * @param null 页面归档
     */
    public function page()
    {
        $where['post_date'] = array('like', I('get.year', '%') . '-' . I('get.month', '%') . '-' . I('get.day', '%') . '%');

        $title_prefix = (I('get.year', '') ? I('get.year', '') . '年' : '') .
            (I('get.month', '') ? I('get.month', '') . '月' : '') . (I('get.day', '') ? I('get.day', '') . '日' : '');


        $PostsLogic = new PostsLogic();

        $count = $PostsLogic->countAll('page', $where); // 查询满足要求的总记录数
        ($count == 0) ? $res404 = 0 : $res404 = 1;
        if ($count != 0) {
            $Page = new GreenPage($count, get_opinion('PAGER')); // 实例化分页类 传入总记录数
            $pager_bar = $Page->show();
            $limit = $Page->firstRow . ',' . $Page->listRows; //获取分页信息

            $posts_list = $PostsLogic->getList($limit, 'page', 'post_date desc', true, $where);
        }
        $this->assign('title', $title_prefix . '所有页面');
        $this->assign('res404', $res404); // 赋值数据集
        $this->assign('postslist', $posts_list); // 赋值数据集
        $this->assign('pager', $pager_bar); // 赋值分页输出
        $this->assign('breadcrumbs', get_breadcrumbs('所有页面'));

        $this->display('single-list');
    }


    /**
     *  未知类型归档  支持年月日参数传递 和用户id
     * @param $method 未知类型
     * @param array $args 参数
     */
    public function _empty($method, $args)
    {

        $title_prefix = (I('get.year', '') ? I('get.year', '') . '年' : '') .
            (I('get.month', '') ? I('get.month', '') . '月' : '') . (I('get.day', '') ? I('get.day', '') . '日' : '');


        //TODO 通用类型


        $post_type = $method;


        $map['post_date'] = array('like', I('get.year', '%') . '-' . I('get.month', '%') . '-' . I('get.day', '%') . '%');
        if (I('get.uid') != '') $map['user_id'] = I('get.uid');

        $PostsLogic = new PostsLogic();

        $count = $PostsLogic->countAll($post_type, $map); // 查询满足要求的总记录数

        ($count == 0) ? $res404 = 0 : $res404 = 1;
        if ($count != 0) {
            $Page = new GreenPage($count, get_opinion('PAGER'));
            $pager_bar = $Page->show();
            $limit = $Page->firstRow . ',' . $Page->listRows;
            $posts_list = $PostsLogic->getList($limit, $post_type, 'post_date desc', true, $map);
        }
        $this->assign('title', $title_prefix . '所有' . $post_type);
        $this->assign('res404', $res404); // 赋值数据集
        $this->assign('postslist', $posts_list); // 赋值数据集
        $this->assign('pager', $pager_bar); // 赋值分页输出


        if (File::file_exists(T('Home@Archive/' . $post_type . '-list'))) {

            $this->display($post_type);
        } else {
            //TODO   这里怎么处理却决于你自己了。
            $this->error404('缺少对应的模版而不能显示');
            //  $this->display('single-list');
        }


    }


}