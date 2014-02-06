<?php
/**
 * Created by Green Studio.
 * File: IndexController.class.php
 * User: TianShuo
 * Date: 14-2-6
 * Time: 上午10:07
 */

namespace Install\Controller;

use Think\Controller;

class IndexController extends Controller
{


    public function index()
    {
        $this->redirect('Install/Index/step1');
    }

    public function step1()
    {
        $this->display();
    }

    public function step2()
    {

    }
}