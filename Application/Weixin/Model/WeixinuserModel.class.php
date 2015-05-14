<?php
/**
 * Created by GreenStudio GCS Dev Team.
 * File: WeixinuserModel.class.php
 * User: Timothy Zhang
 * Date: 14-2-25
 * Time: 上午11:27
 */

namespace Weixin\Model;


use Think\Model\RelationModel;

/**
 * Class WeixinuserModel
 * @package Weixin\Model
 */
class WeixinuserModel extends RelationModel
{
//    protected $fields = array('openid','_pk'=>'openid');
//   protected $fields = array('_pk'=>'openid');

    /**
     * @var array
     */
    public $_link = array(

        'log' => array(

            'mapping_type' => self::HAS_MANY,

            'class_name' => 'Weixinlog',

            'mapping_name' => 'log',

            'foreign_key' => 'FromUserName', //当前表字段

            'mapping_key' => 'openid',

            'mapping_foreign_key' => 'openid',

            'mapping_order' => 'CreateTime desc',
        )
    );

    /**
     * @param $openid
     * @param bool $relation
     * @return mixed
     */
    public function detail($openid, $relation = true)
    {

        $Users = D('Weixinuser');
        $user_detail = $Users->where(array('openid' => $openid))->relation($relation)->find();

        return $user_detail;
    }

}