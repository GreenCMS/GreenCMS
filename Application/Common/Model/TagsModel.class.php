<?php
/**
 * Created by GreenStudio GCS Dev Team.
 * File: TagsModel.class.php
 * User: Timothy Zhang
 * Date: 14-1-16
 * Time: 上午12:36
 */

namespace Common\Model;

use Think\Model\RelationModel;

/**
 * 标签模型定义
 * Class TagsModel
 * @package Home\Model
 */
class TagsModel extends RelationModel
{

    /**
     * @var array
     */
    public $_link = array(
        'Post_tags' => array(

            'mapping_type' => self::HAS_MANY,

            'class_name' => 'Post_tag',

            'mapping_name' => 'in_post',

            'foreign_key' => 'tag_id',

            'parent_key' => 'tag_id',

            'mapping_order' => 'tag_id',

            'mapping_limit' => 0,


        ),

    );

}