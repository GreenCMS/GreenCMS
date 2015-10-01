<?php
/**
 * Created by PhpStorm.
 * User: Timothy Zhang
 * Date: 14-8-21
 * Time: 下午11:08
 */

namespace Addons\Join2011\Controller;

use Addons\Join2011\Join2011Addon;
use Admin\Controller\AddonsController;
use Common\Util\GreenPage;

class Join2011adminController extends AddonsController
{


    public function __construct()
    {
        parent::__construct();

        $this->assign('module', '2011报名管理');


    }


    public function index()
    {
        $page = I('get.page', get_opinion('PAGER'));

        $Bmb = D('Bmb');
        //$stu = $Stu->select();
        $stu_count = $Bmb->count();

        if ($stu_count != 0) {
            $Page = new GreenPage($stu_count, $page); // 实例化分页类 传入总记录数
            $pager_bar = $Page->show();
            $limit = $Page->firstRow . ',' . $Page->listRows;
            $stu_list = $Bmb->join(GreenCMS_DB_PREFIX.'stu ON '.GreenCMS_DB_PREFIX.'stu.ksh = '.GreenCMS_DB_PREFIX.'bmb.ksh')->limit($limit)->select();
        }

        $this->assign('stu_count', $stu_count);
        $this->assign('stu_list', $stu_list);
        $this->assign('pager', $pager_bar);
        $this->assign('action', '2011报名管理');
        $this->display('');
    }


    public function manage()
    {

    }
} 