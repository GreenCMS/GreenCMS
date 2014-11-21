<?php
/**
 * Created by GreenStudio GCS Dev Team.
 * File: Weixinlog.class.php
 * User: Timothy Zhang
 * Date: 14-2-25
 * Time: 上午11:10
 */

namespace Weixin\Model;


use Think\Model\RelationModel;

/**
 * Class WeixinlogModel
 * @package Weixin\Model
 */
class WeixinlogModel extends RelationModel
{
    /**
     * @var array
     */
    public $_link = array(

        'user' => array(

            'mapping_type'        => self::BELONGS_TO,

            'class_name'          => 'Weixinuser',

            'mapping_name'        => 'user',

            'foreign_key'         => 'FromUserName', //当前表字段

            'mapping_key'         => 'openid',

            'mapping_foreign_key' => 'openid',
        )
    );


}