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
 * Class TagsLogic
 * @package Home\Logic
 */
class TagsLogic extends RelationModel
{


    public function detail($id, $relation = true)
    {
        $map = array();
        $map['tag_id|tag_slug'] = $id;
        return D('Tags')->where($map)->relation($relation)->find();
    }

    /**
     * @param $info 输入tag_id|tag_slug
     *
     * @param string $post_status
     * @return mixed 找到的话返回post_id数组集合
     */
    public function getPostsId($info, $post_status = 'publish')
    {
        $tag_info ['tag_id'] = $info;
        $tag = D('Post_tag')->field('post_id')->where($tag_info)->select();
        $ids = array();

        foreach ($tag as $key => $value) {

            $posts = D('Posts')->field('post_status')->where(array('post_id' => $tag[$key]['post_id']))->cache(true)->find();


            if ($posts['post_status'] == $post_status) {
                $ids[] = $tag[$key]['post_id'];
            }
        }
        return $ids;
    }



    /**
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
        $posts = D('Posts', 'Logic')->getList($num, 'single', 'post_id desc', true, array(), $tag);
        return $posts;
    }

    /**
     * @param int $limit
     * @param bool $relation
     *
     * @return mixed
     */
    public function getList($limit = 20, $relation = true)
    {
        return D('Tags')->limit($limit)->relation($relation)->select();
    }






}