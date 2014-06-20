<?php

namespace Common\Behavior;

// 初始化钩子信息

use Common\Util\File;
use Think\Behavior;
use Think\Hook;

/**
 * Class InitHookBehavior
 * @package Common\Behavior
 */
class InitHookBehavior extends Behavior
{
    /**
     *
     */
    public function __construct()
    {

    }


    // 行为扩展的执行入口必须是run
    /**
     * @param mixed $content
     */
    public function run(&$content)
    {

        /**
         *  //真不知道说什么好。。。
         *      这里      原因是tp 不能把tag放在项目配置中，只能放在common中，而common模块先于install 初始化
         *     so。。。。。
         *
         */

        if ((C('DB_TYPE') == 'GreenCMS_DB_TYPE')) {

        } else {
            if (isset($_GET['m']) && strtolower($_GET['m']) == 'install') return;

            $data = S('hooks');

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

                            Hook::add($key, $addons);
                        }
                    }
                }
                S('hooks', Hook::get());
            } else {
                Hook::import($data, false);
            }


        }

    }
}