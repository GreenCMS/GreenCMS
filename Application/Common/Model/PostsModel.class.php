<?php
/**
 * Created by GreenStudio GCS Dev Team.
 * File: PostsModel.class.php
 * User: Timothy Zhang
 * Date: 14-1-15
 * Time: 下午11:54
 */

namespace Common\Model;

use Think\Model\RelationModel;

/**
 * 文章模型定义
 * Class PostsModel
 * @package Home\Model
 */
class PostsModel extends RelationModel
{


    /**
     * @var array
     */
    public $_link = array(

        'Tag' => array(

            'mapping_type' => self::MANY_TO_MANY,

            'class_name' => "Tags",

            'mapping_name' => 'post_tag',

            'foreign_key' => 'post_id',

            'relation_foreign_key' => 'tag_id',

            'relation_table' => 'post_tag',

            'mapping_order' => 'tag_id',

            'mapping_limit' => 0,

        ),

        'Cat' => array(

            'mapping_type' => self::MANY_TO_MANY,

            'class_name' => 'Cats',

            'mapping_name' => 'post_cat',

            'foreign_key' => 'post_id',

            'relation_foreign_key' => 'cat_id',

            'relation_table' => 'post_cat',


            'mapping_order' => 'cat_id',

            'mapping_limit' => 0,

        ),

        'User' => array(

            'mapping_type' => self::BELONGS_TO,

            'class_name' => 'User',

            'foreign_key' => 'user_id',

            'mapping_name' => 'post_user',

            'parent_key' => 'user_id',

            'mapping_order' => 'user_id',

            'mapping_limit' => 0,

        )
    );


}