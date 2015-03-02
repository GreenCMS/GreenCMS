<?php
/**
 * Created by GreenStudio GCS Dev Team.
 * File: CatsModel.class.php
 * User: Timothy Zhang
 * Date: 14-1-16
 * Time: 上午12:34
 */

namespace Common\Model;

use Common\Model;
use Think\Model\RelationModel;

/**
 * 分类模型定义
 * Class CatsModel
 * @package Home\Model
 */
class CatsModel extends RelationModel
{

    /**
     * @var array
     */
    public $_link = array(
        'Post_cat' => array(

            'mapping_type' => self::HAS_MANY,

            'class_name' => 'Post_cat',

            'mapping_name' => 'cat_post',

            'foreign_key' => 'cat_id',

            'parent_key' => 'cat_id',

            'mapping_order' => 'cat_id',

            'mapping_limit' => 0,
        ),


    );
}