<?php
/**
 * Created by Green Studio.
 * File: FormController.class.php
 * User: TianShuo
 * Date: 14-1-23
 * Time: 下午7:23
 */

namespace Home\Controller;


class FormController extends HomeBaseController
{

    public function apply()
    {
        if (IS_POST) {

            $Form_apply = D('Form');
            $Form_apply->create();

            if (!$Form_apply->create()) {
                // 如果创建失败 表示验证没有通过 输出错误提示信息
                $this->error('提交信息出错:' . $Form_apply->getError());;
            }

            if ($Form_apply->add()) {
                $this->success("提交成功", U('/'));
            } else {
                $this->error('提交信息出错');
            }


        } else
            $this->display();
    }


}