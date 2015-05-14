<?php
/**
 * Created by GreenStudio GCS Dev Team.
 * File: IndexController.class.php
 * User: Timothy Zhang
 * Date: 14-2-20
 * Time: 下午8:43
 */

namespace Weixin\Controller;


class IndexController extends \Think\Controller
{

    public function index()
    {
        $this->redirect('Weixin/Home/index');
    }

}