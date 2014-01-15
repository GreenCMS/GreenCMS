<?php
/**
 * Created by Green Studio.
 * File: ArchiveController.class.php
 * User: TianShuo
 * Date: 14-1-15
 * Time: 下午11:39
 */

namespace Home\Controller;
use Home\Controller\HomeBaseController;

class ArchiveController  extends HomeBaseController
{
    function __construct()
    {
        parent::__construct();

    }


    public function single() {

        $post=D("Posts",'Logic');
       //print_array($post->detail(100));

        $link=D("Links",'Logic');
        //print_array($link->getList());

        $pc=D("Tags",'Logic');
        //print_array($pc->getPostId(8));

        $user=D("User",'Logic');
        print_array($user->detail(12));


        $this->show(" ");
    }



}