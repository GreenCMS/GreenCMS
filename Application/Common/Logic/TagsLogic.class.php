<?php
/**
 * Created by Green Studio.
 * File: TagsLogic.class.php
 * User: TianShuo
 * Date: 14-1-16
 * Time: 上午12:37
 */

namespace Common\Logic;

use Think\Model\RelationModel;

/**
 * 标签逻辑定义
 * Class TagsLogic
 * @package Home\Logic
 */
class TagsLogic extends RelationModel
{


    /**
     * 获取指定标签信息
     * @param $id id或者slug
     * @param bool $relation 是否关联
     * @return mixed
     */
    public function detail($id, $relation = true)
    {
        $map = array();
        $map['tag_id|tag_slug'] = urlencode($id);
        return D('Tags')->where($map)->relation($relation)->find();
    }

    /**
     *
     * @param $info 输入tag_id|tag_slug
     *
     * @param string $post_status
     * @return mixed 找到的话返回post_id数组集合
     */
    public function getPostsId($info, $post_status = 'publish')
    {

        $tag_info ['tag_id'] = $info;
        $ids = array();

        $res = D('Post_tag')
            ->table(GreenCMS_DB_PREFIX .'post_tag as pt,'.GreenCMS_DB_PREFIX .'posts as ps' )
            ->field('ps.post_id')
            ->where("tag_id ='%s' and pt.post_id=ps.post_id and ps.post_status ='%s'", $tag_info ['tag_id'],$post_status )
           ->select();

        foreach ($res as $key => $value) {
            $ids[] = $res[$key]['post_id'];

        }

        return $ids;
    }


    /**
     * 获取指定tag的post id
     * @param $tag_id
     * @param int $num 数量
     *
     * @param $start
     * @internal param $cat_id 分类id
     * @return mixed
     */
    public function getPostsByTag($tag_id, $num = 5, $start = -1)
    {
        $tag = $this->getPostsId($tag_id);
        if ($start != -1) {
            for ($i = 0; $i < $start; $i++) {
                unset($tag[sizeof($tag) - 1]);
            }
        }
        $posts = D('Posts', 'Logic')->getList($num, 'single', 'post_date desc', true, array(), $tag);
        return $posts;
    }

    /**
     * 获取列表
     * @param int $limit
     * @param bool $relation
     * @param $order
     * @return mixed
     */
    public function getList($limit = 20, $relation = true, $order)
    {
        return D('Tags')->limit($limit)->relation($relation)->select();
    }


    public function selectWithPostsCount($limit = 0, $relation = false, $where = array(), $order = '')
    {

        return D('Tags')->where($where)->limit($limit)->field('*,count( ' . GreenCMS_DB_PREFIX . 'post_tag.post_id) as post_count')
            ->join('LEFT JOIN  ' . GreenCMS_DB_PREFIX . 'post_tag ON ' . GreenCMS_DB_PREFIX .
                'tags.tag_id = ' . GreenCMS_DB_PREFIX . 'post_tag.tag_id')
            ->group(GreenCMS_DB_PREFIX . 'tags.tag_id')->relation($relation)->select();

    }


}