<?php
/**
 * Created by Green Studio.
 * File: CatsModel.class.php
 * User: TianShuo
 * Date: 14-1-16
 * Time: 上午12:34
 */

namespace Home\Model;
use \Think\Model\RelationModel;
use Common\Model;
class CatsModel extends RelationModel  {
    public $_link = array (
        'Post_cat' => array (

            'mapping_type' => HAS_MANY,

            'class_name' => 'Post_cat',

            'mapping_name' => 'cat_post',

            'foreign_key' => 'cat_id',
            // post_user
            'parent_key' => 'cat_id'
        ),


    );
}