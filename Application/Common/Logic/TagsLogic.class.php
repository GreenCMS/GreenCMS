<?php
/**
 * Created by GreenStudio GCS Dev Team.
 * File: TagsLogic.class.php
 * User: Timothy Zhang
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
     * @param $id string id|slug ID
     * @param bool $relation 是否关联
     * @return mixed
     */
    public function detail($id, $relation = true)
    {
        //todo add cache

        $map = array();
        $map['tag_id|tag_slug'] = urlencode($id);
        return D('Tags')->where($map)->relation($relation)->find();
    }

    /**
     * @param array $info_with
     * @param array $ids 需要限制的id
     * @return int
     */
    public function countAll($info_with = array(), $ids = array())
    {
        $info = $info_with;

        if (!empty($ids)) $info['cat_id'] = array('in', $ids);

        $count = $this->where($info)->count();
        return $count;
    }

    /**
     * 获取指定tag的post id
     * @param $tag_id
     * @param int $num 数量
     *
     * @param int $start
     * @param bool $relation
     * @param string $except_field
     * @return mixed
     */
    public function getPostsByTag($tag_id, $num = 5, $start = 0, $relation = true, $except_field = '')
    {
        $tag = $this->getPostsId($tag_id, 'publish', $start . ',' . $num);
        if ($tag != null) {
            $posts = D('Posts', 'Logic')->getList($num, 'single', 'post_date desc', $relation, array(), $tag, $except_field);
            return $posts;
        } else {
            return false;
        }
    }

    /**
     * 输入
     * @param $info string tag_id|tag_slug
     * @param string $post_status
     * @param int $limit
     * @return mixed 找到的话返回post_id数组集合
     */
    public function getPostsId($info, $post_status = 'publish', $limit = 99999999)
    {

        $tag_info ['tag_id'] = $info;
        $ids = array();

        $res = D('Post_tag')
            ->table(GreenCMS_DB_PREFIX . 'post_tag as pt,' . GreenCMS_DB_PREFIX . 'posts as ps')
            ->field('ps.post_id')
            ->where("tag_id ='%s' and pt.post_id=ps.post_id and ps.post_status ='%s'", $tag_info ['tag_id'], $post_status)
            ->limit($limit)
            ->order('ps.post_top desc,ps.post_date desc')
            ->select();
//        dump( D('Post_tag')->getlastsql());

        foreach ($res as $key => $value) {
            $ids[] = $res[$key]['post_id'];

        }

        return $ids;
    }

    /**
     * 获取列表
     * @param int $limit
     * @param bool $relation
     * @param bool $cache
     * @param string $order
     * @return mixed
     */
    public function getList($limit = 20, $relation = true, $cache = false, $order = "")
    {
        return D('Tags')->cache($cache)->limit($limit)->relation($relation)->select();
    }


    /**
     * @param int $limit
     * @param bool $relation
     * @param array $where
     * @param string $order
     * @return mixed
     */
    public function selectWithPostsCount($limit = 0, $relation = false, $where = array(), $order = '')
    {

        $res = D('Tags')->where($where)->limit($limit)
            ->field(GreenCMS_DB_PREFIX . 'tags.tag_id,' .
                GreenCMS_DB_PREFIX . 'tags.tag_slug,' .
                GreenCMS_DB_PREFIX . 'tags.tag_name,' .
                'count( ' . GreenCMS_DB_PREFIX . 'post_tag.post_id) as post_count')
            ->join('LEFT JOIN  ' . GreenCMS_DB_PREFIX . 'post_tag ON ' . GreenCMS_DB_PREFIX .
                'tags.tag_id = ' . GreenCMS_DB_PREFIX . 'post_tag.tag_id')
            ->group(GreenCMS_DB_PREFIX . 'tags.tag_id')->relation($relation)->select();

        return $res;
    }


}