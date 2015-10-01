<?php
/**
 * Created by GreenStudio GCS Dev Team.
 * File: PostsLogic.class.php
 * User: Timothy Zhang
 * Date: 14-1-15
 * Time: 下午11:59
 */

namespace Common\Logic;

use Common\Util\CacheManager;
use Think\Model\RelationModel;

/**
 * 文章逻辑定义
 * Class PostsLogic
 * @package Home\Logic
 */
class PostsLogic extends RelationModel
{

    /**
     * 获取文章详细
     * @param $id int 文章id或者其他识别符
     * @param bool $relation 是否关联其他信息
     * @param array $info_with 强制传入的判断条件
     * @return mixed 如果找到返回数组
     */
    public function detail($id, $relation = true, $info_with = array())
    {
        $info = $info_with;
        $info['post_id|post_name'] = urlencode($id);

        if (!array_key_exists('post_status', $info)) $info['post_status'] = 'publish';

        $post_res = D('Posts')->where($info)->relation($relation)->find();


        return $post_res;
    }

    public function preview($id, $relation = true, $info_with = array())
    {
        $info = $info_with;
        $info['post_id|post_name'] = urlencode($id);

        $post_res = D('Posts')->where($info)->relation($relation)->find();


        return $post_res;
    }

    /**
     * 判断文章是否存在
     * @param $id int 文章id或者其他识别符
     * @return mixed 如果找到返回true
     */
    public function has($id)
    {
        $info['post_id|post_name'] = urlencode($id);
        $post_res = $this->where($info)->relation(false)->find();
        if ($post_res) return true;
        return false;
    }


    /**
     * @param int $limit 数量限制 默认20 一般我们会传入(string)limit用于分页
     * @param string $type 文章类型默认是single
     * @param string $order 排序方式默认是id
     * @param bool $relation 是否关联其他信息
     * @param array $info_with 强制传入的判断条件
     *
     * @param array $ids 需要限制的id
     * @param string $except_field
     * @return mixed 返回文章列表
     */
    public function getList($limit = 20, $type = 'single', $order = 'post_date desc',
                            $relation = true, $info_with = array(), $ids = array(), $except_field = '')
    {
        $info = $info_with;
        if ($type != 'all') $info['post_type'] = $type;
        //if ($type != 'all') $info['post_type|post_template'] = $type;
        if (!array_key_exists('post_status', $info)) $info['post_status'] = 'publish';
        if (!empty($ids)) $info['post_id'] = array('in', $ids);

        $post_list = D('Posts')->field($except_field, true)->where($info)->order('post_top desc ,' . $order)
            ->limit($limit)->relation($relation)->select();
        return $post_list;
    }

    /**
     * 统计数量
     * @param string $type
     * @param array $info_with
     * @param array $ids 需要限制的id
     *
     * @return int
     */
    public function countAll($type = 'single', $info_with = array(), $ids = array())
    {

        $info = $info_with;
        if ($type != 'all') $info['post_type'] = $type; //post_template
        if (!array_key_exists('post_status', $info)) $info['post_status'] = 'publish';

        if (!empty($ids)) $info['post_id'] = array('in', $ids);

        $count = $this->where($info)->count();
        return $count;
    }


    /**
     * 删除文章
     * @param $id int 需要预删除的id
     * @param string $relation 是否删除关联(脑残才不删除关联呢。。。。)
     *
     * @return bool 是否删除成功
     */
    public function del($id)
    {
        CacheManager::clearPostCacheById($id);

        $info['post_id'] = $id;

         if ($this->where($info)->relation(true)->delete()){
            return true;
        }
        else{
            return false;

        }

    }

    /**
     * 预删除文章
     * @param $post_id int 需要预删除的id
     * @return bool 是否删除成功
     */
    public function preDel($post_id)
    {
        if ($this->changePostStatue($post_id, 'preDel'))
            return true;
        else
            return false;
    }

    /**
     * 修改文章状态
     * @param $post_id
     * @param $post_status
     * @return mixed
     */
    public function changePostStatue($post_id, $post_status)
    {
        CacheManager::clearPostCacheById($post_id);
        return $this->where(array('post_id' => $post_id))->setField(array("post_status" => $post_status));
    }


    /**
     * 修改为publish
     * @param $post_id
     * @return bool
     */
    public function restore($post_id)
    {
        if ($this->changePostStatue($post_id, 'publish'))
            return true;
        else
            return false;

    }


    /**
     * 修改为unverified
     * @param $post_id
     * @return bool
     */
    public function verify($post_id)
    {
        if ($this->changePostStatue($post_id, 'unverified'))
            return true;
        else
            return false;

    }

    /**
     * 文章计数+1
     * @param $post_id int 需要计数的id
     * @return bool 返回是否成功
     */
    public function viewInc($post_id)
    {
        $info['post_id'] = $post_id;

        if ($this->where($info)->setInc('post_view_count'))
            return true;
        else
            return false;
    }

    /**
     * 清空指定状态文章
     * @param $post_status
     * @return mixed
     */
    public function emptyPostHandleByStatus($post_status)
    {
        return $this->where(array('post_status' => $post_status))->relation(true)->delete();
    }

    /**
     * 清空指定状态文章
     * @param $post_status
     * @return mixed
     */
    public function emptyPostDraft($post_status)
    {
        return $this->where(array('post_status' => $post_status,'post_title'=>'未命名'))->relation(true)->delete();
    }


}