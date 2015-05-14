<?php
/**
 * Created by GreenStudio GCS Dev Team.
 * File: FormController.class.php
 * User: Timothy Zhang
 * Date: 14-1-23
 * Time: 下午7:23
 */

namespace Home\Controller;


use Home\Model\FormModel;

/**
 * 绿荫网专用申请控制器
 * Class FormController
 * @package Home\Controller
 */
class FormController extends HomeBaseController
{

    /**
     *  请无视
     */
    public function apply()
    {
        if (IS_POST) {

            $Form_apply = new FormModel();
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