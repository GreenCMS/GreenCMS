<?php
/**
 * Created by PhpStorm.
 * User: Timothy Zhang
 * Date: 14-6-2
 * Time: 下午3:50
 */

namespace Oauth\Model;

use Think\Model\RelationModel;


class User_snsModel extends RelationModel
{

    public $_link = array(


        'User' => array(

            'mapping_type' => self::BELONGS_TO,

            'class_name' => 'User',

            'foreign_key' => 'user_id',

            'mapping_name' => 'User',

            'parent_key' => 'user_id'
        )


    );

} 