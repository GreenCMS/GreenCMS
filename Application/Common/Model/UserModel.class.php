<?php
/**
 * Created by GreenStudio GCS Dev Team.
 * File: UserModel.class.php
 * User: Timothy Zhang
 * Date: 14-1-16
 * Time: 上午12:51
 */
namespace Common\Model;

use Think\Model\RelationModel;


/**
 * 用户模型定义
 * Class UserModel
 * @package Home\Model
 */
class UserModel extends RelationModel
{

    /**
     * @var array
     */
    public $_link = array(

        'Name' => array(

            'mapping_type' => self::MANY_TO_MANY,

            'class_name' => 'Role',

            'mapping_name' => 'role_info',

            'foreign_key' => 'user_id',

            'relation_foreign_key' => 'role_id',

            'relation_table' => 'role_users',


            'mapping_order' => 'user_id',

            'mapping_limit' => 0,


        ),

        'Role' => array(

            'mapping_type' => self::BELONGS_TO,

            'class_name' => 'Role_users',

            'mapping_name' => 'user_role',

            'mapping_key' => 'user_id',

            'foreign_key' => 'user_id',

            'parent_key' => 'user_id',


            'mapping_order' => 'user_id',

            'mapping_limit' => 0,

        )
    );


}