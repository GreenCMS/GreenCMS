<?php
/**
 * Created by GreenStudio GCS Dev Team.
 * File: FormModel.class.php
 * User: Timothy Zhang
 * Date: 14-2-4
 * Time: 上午10:14
 */

namespace Home\Model;

use Think\Model;

/**
 * Class FormModel
 * @package Home\Model
 */
class FormModel extends Model
{
    /**
     * @var array
     */
    protected $_validate = array(
        array('name', 'require', '名字是必须的~~'),
        array('class', 'require', '添加一下学号吧~'),
        array('tel', 'require', '添加一下联系电话~~'),
        array('email', 'require', '留个邮箱~~~~'),
        array('direction', 'require', '选个方向~~'),


    );
}