<?php
/**
 * Created by Green Studio.
 * File: TagsModel.class.php
 * User: TianShuo
 * Date: 14-1-16
 * Time: 上午12:36
 */

namespace Home\Model;
use \Think\Model\RelationModel;

class TagsModel extends  RelationModel {
    public $_link = array(
        'Post_tags'=> array(

            'mapping_type'=>HAS_MANY,

            'class_name'=>'Post_tag',

            'mapping_name'=>'in_post',

            'foreign_key'=>'tag_id',
            //post_user
            'parent_key'=>'tag_id'),

    );

}