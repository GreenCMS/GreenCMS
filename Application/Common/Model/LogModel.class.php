<?php
/**
 * Created by PhpStorm.
 * User: Timothy Zhang
 * Date: 14-6-21
 * Time: 下午8:23
 */

namespace Common\Model;


use Think\Model\RelationModel;

class LogModel extends RelationModel
{

    /**
     * @var array
     */
    public $_link = array(


        'User' => array(

            'mapping_type' => self::BELONGS_TO,

            'class_name' => 'User',

            'foreign_key' => 'user_id',

            'mapping_name' => 'log_user',

            'parent_key' => 'user_id',

            'mapping_order' => 'user_id',

            'mapping_limit' => 0,

        )
    );
} 