<?php
/**
 * Created by GreenStudio GCS Dev Team.
 * File: CatsLogic.class.php
 * User: Timothy Zhang
 * Date: 14-1-16
 * Time: 上午12:34
 */

namespace Common\Logic;

use Common\Util\Category;
use Think\Model\RelationModel;

/**
 * 分类逻辑定义
 * Class CatsLogic
 * @package Home\Logic
 */
class CatsLogic extends RelationModel
{

    /**
     * @param array $info_with
     * @param array $ids 需要限制的id
     *
     * @internal param string $type
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
     * 获取分类列表
     * @param int $limit limit
     * @param bool $relation 是否关联
     *
     * @return mixed
     */
    public function getList($limit = 20, $relation = true)
    {
        return D('Cats')->limit($limit)->relation($relation)->select();
    }

    /**
     * 获取分类所有父类
     * @param int $id 分类id
     *
     * @param bool $relation
     * @return mixed 找到所有父类
     */
    public function getFather($id = 0, $relation = false)
    {
        $info = $this->detail($id, $relation);
        if ($info['cat_father'] != 0) {
            $info['cat_father_detail'] = $this->getFather($info['cat_father']);
        }
        return $info;
    }

    /**
     * 获取分类详细
     * @param int $id 分类id
     * @param bool $relation 是否关联
     * @return mixed 找到之后返回数组
     */
    public function detail($id, $relation = true)
    {
        $map = array();
        $map['cat_id|cat_slug'] = urlencode($id);
        return D('Cats')->where($map)->relation($relation)->find();
    }

    /**
     * 分类所有子类
     * @param int $id 分类id
     * @param bool $relation
     *
     * @return mixed  找到所有子节点
     */
    public function getChildren($id = 0, $relation = false)
    {
        $info = $this->detail($id, $relation);

        if ($id) {
            $children = $this->getChild($id);

            foreach ($children as $key => $value) {
                $info['cat_children'] [$key]['cat_children'] = $this->getChildren($value['cat_id']);
            }
            return $info;

        }

        return false;
    }

    /**
     * 得到子节点
     * @param int $id 分类id
     *
     * @return mixed 返回子节点
     */
    public function getChild($id = 0)
    {
        if ($id) {
            $info = D('Cats')->where(array("cat_father" => $id))->order("cat_order")->select();
            if ($info != null) return $info;
        }
        return false;
    }

    /**
     * 获取分类的文章
     * @param $cat_id int 分类id
     * @param int $num 数量
     *
     * @param int $start
     * @param bool $relation
     * @param string $except_field
     * @return mixed
     */
    public function getPostsByCat($cat_id, $num = 5, $start = 0, $relation = true, $except_field = '')
    {
        $cat = $this->getPostsId($cat_id, 'publish', $start . ',' . $num);

        if ($cat != null) {
            $posts = D('Posts', 'Logic')->getList($num, 'single', 'post_date desc', $relation, array(), $cat, $except_field);
            return $posts;
        } else {
            return false;
        }

    }


    /**
     * @param $cat_id
     * @param int $num
     * @param int $start
     * @param bool $relation
     * @param string $except_field
     * @return bool
     */
    public function getPostsByCatWithChildren($cat_id, $num = 5, $start = 0, $relation = true, $except_field = '')
    {
//        $getChild = $this->getChild($cat_id, 'publish', $start . ',' . $num);
//
//        $cat_id_list = $cat_id."','";
//        foreach ($getChild as $value) {
//            $cat_id_list .= $value['cat_id'];
//            $cat_id_list .= "',";
//        }
//
//        echo $cat_id_list;

        $cat = $this->getPostsIdWithChildren($cat_id, 'publish' );

        if ($cat != null) {
            $posts = D('Posts', 'Logic')->getList($num, 'single', 'post_date desc', $relation, array(), $cat, $except_field);
            return $posts;
        } else {
            return false;
        }

    }


    /**
     * 获取指定分类的post id
     * 使用原生SQL
     * @param $info int 分类info
     * @param string $post_status
     *
     * @param $limit
     * @return mixed 找到的话返回post_id数组集合
     */
    public function getPostsId($info, $post_status = 'publish', $limit = 99999999)
    {

        $cat_info ['cat_id'] = $info;
        $ids = array();

        $res = D('Post_cat')
            ->table(GreenCMS_DB_PREFIX . 'post_cat as pc,' . GreenCMS_DB_PREFIX . 'posts as ps')
            ->field('ps.post_id')
            ->where("cat_id IN ('%s') and pc.post_id=ps.post_id and ps.post_status ='%s'", $cat_info ['cat_id'], $post_status)
            ->limit($limit)
            ->order('ps.post_top desc,ps.post_date desc')
            ->select();


        foreach ($res as $key => $value) {
            $ids[] = $res[$key]['post_id'];

        }
        return $ids;
    }
    /**
     * 获取指定分类的post id
     * 使用原生SQL
     * @param $info int 分类info
     * @param string $post_status
     *
     * @param $limit
     * @return mixed 找到的话返回post_id数组集合
     */
    public function getPostsIdWithChildren($info, $post_status = 'publish', $limit = 0)
    {

        $getChild = $this->getChild($info);

        $cat_id_list = "'".$info."'";
        foreach ($getChild as $child) {
            $cat_id_list.=",'";
            $cat_id_list .= $child['cat_id'];
            $cat_id_list .= "'";
        }

        $ids = array();

        $res = D('Post_cat')
            ->table(GreenCMS_DB_PREFIX . 'post_cat as pc,' . GreenCMS_DB_PREFIX . 'posts as ps')
            ->field('ps.post_id')
            ->where("cat_id IN (".$cat_id_list.") and pc.post_id=ps.post_id and ps.post_status ='%s'", $post_status)
            ->limit($limit)
            ->order('ps.post_top desc,ps.post_date desc')
            ->select();

        foreach ($res as $key => $child) {
            $ids[] = $res[$key]['post_id'];

        }

        return $ids;
    }

    /**
     * @param $cat_id int 分类id
     *
     * @return mixed
     */
//    public function getPostIdsByCat($cat_id)
//    {
//        $cat = $this->getPostsByCat($cat_id);
//        foreach ($cat as $key => $value) {
//            $cat[$key] = $cat[$key]['post_id'];
//        }
//        return $cat;
//    }

    /**
     * 获取结构化分类
     * @return array
     */
    public function category()
    {

        $Cat = new Category ('Cats', array(
            'cat_id',
            'cat_father',
            'cat_name',
            'cat_slug'
        )); // , array('cid', 'pid', 'name', 'fullname')

        return $Cat->getList(null,0,'cat_order');

    }


    /**
     * 获取结构化分类With 文章数量统计
     * 此操作消耗资源，仅限后台使用
     * @return array
     */
    public function selectWithPostsCount()
    {

        $Cat = new Category ('Cats', array(
            'cat_id',
            'cat_father',
            'cat_name',
            'cat_slug'
        )); // , array('cid', 'pid', 'name', 'fullname')

        return $Cat->getListWithCount(null,0,'cat_order');

    }


}