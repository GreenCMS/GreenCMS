<?php
/**
 * Created by Green Studio.
 * File: ArchiveController.class.php
 * User: TianShuo
 * Date: 14-1-21
 * Time: 下午9:33
 */

namespace Home\Controller;
use Home\Controller\BaseController;
use Common\Util\GreenPage;

class ArchiveController extends HomeBaseController {

    function __construct() {
        parent::__construct ();

       // import ( "@.ORG.Page" );
    }


    public function search($keyword='') {

        $info['post_content|post_title'] =  array('like',"%$keyword%");

        $PostsList = D ( 'Posts','Logic' );

        $count = $PostsList->countAll('all','publish',$info); // 查询满足要求的总记录数
        ($count==0)?$res404 = 0:$res404 = 1;

        $Page = new GreenPage ( $count,  C('PAGER')   ); // 实例化分页类 传入总记录数
        $pager_bar = $Page->show ();
        $limit=$Page->firstRow.','.$Page->listRows;

       // $res = $PostsList->post_list_page($Page, 'all', 'post_id desc','publish', $info);

        $res = $PostsList->getList('all', 'post_id desc',$limit,true,publish, array(),$info);


        $this->assign ( 'title', '关于"'.$keyword.'"文章搜索结果' );
        $this->assign ( 'res404', $res404 ); // 赋值数据集
        $this->assign ( 'postslist', $res ); // 赋值数据集
        $this->assign ( 'page', $pager_bar ); // 赋值分页输出

        $this->display ( 'single-list' );



    }


    public function feature($id) {

        $CatList = D ( 'Cats' );



        //print_array($CatList->childen($id));

        //二级分类目录
    }


    public function single() {
        $PostsList = D ( 'Posts','Logic' );

        $count = $PostsList->countAll(); // 查询满足要求的总记录数
        ($count==0)?$res404 = 0:$res404 = 1;

        $Page = new GreenPage( $count , C('PAGER')  );
        $pager_bar = $Page->show();
        $limit=$Page->firstRow.','.$Page->listRows;

        $res = $PostsList->getList('single', 'post_id desc',$limit,true);

        $this->assign ( 'title', '所有文章' );
        $this->assign ( 'res404', $res404 ); // 赋值数据集
        $this->assign ( 'postslist', $res ); // 赋值数据集
        $this->assign ( 'page', $pager_bar ); // 赋值分页输出

        $this->display ( 'single-list' );
    }





    public function page() {

        $PostsList = D ( 'Posts','Logic' );

        $count = $PostsList->countAll(); // 查询满足要求的总记录数
        ($count==0)?$res404 = 0:$res404 = 1;

        $Page = new GreenPage( $count , C('PAGER')  );
        $pager_bar = $Page->show();
        $limit=$Page->firstRow.','.$Page->listRows;

        $res = $PostsList->getList('page', 'post_id desc',$limit,true);

        $this->assign ( 'title', '所有页面' );
        $this->assign ( 'res404', $res404 ); // 赋值数据集
        $this->assign ( 'postslist', $res ); // 赋值数据集
        $this->assign ( 'page', $pager_bar ); // 赋值分页输出

        $this->display ( 'single-list' );
    }

}