<?php

namespace Common\Behavior;

// 初始化钩子信息

use Common\Util\File;
use Think\Behavior;
use Think\Hook;

class InitHookBehavior extends Behavior
{
    public function __construct()
    {

    }


    // 行为扩展的执行入口必须是run
    public function run(&$content)
    {

        if ((C('DB_TYPE') == 'GreenCMS_DB_TYPE')) {
//真不知道说什么好。。。

        } else {
             if (isset($_GET['m']) && $_GET['m'] === 'Install') return;

         //   $data = S('hooks');


            if ((!$data)) {
                $hooks = M('Hooks')->getField('name,addons');


                foreach ($hooks as $key => $value) {
                    if ($value) {

                        $map['status'] = 1;
                        $names = explode(',', $value);
                        $map['name'] = array('IN', $names);
                        $data = M('Addons')->where($map)->getField('id,name');
                        if ($data) {
                            $addons = array_intersect($names, $data);
                           // dump($addons);
                           // $addons[0]= 'Addons\\'.$addons[0];

                            Hook::add($key,$addons);
                        }
                    }
                }
                S('hooks', Hook::get());
            } else {
                Hook::import($data, false);
            }
            Hook::add('test', "Test");
//        dump(Hook::get());
//        die;
//        dump(S('hooks'));
//        die;
        }

    }
}