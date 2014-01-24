<?php
/**
 * Created by Green Studio.
 * File: LinksLogic.class.php
 * User: TianShuo
 * Date: 14-1-16
 * Time: 上午12:29
 */

namespace Common\Logic;
use Think\Model;

/**
 * Class LinksLogic
 * @package Home\Logic
 */
class LinksLogic extends Model
{

    /**
     * @param $data 数据
     * @return bool 是否添加成功
     */
    public function addLink($data)
    {
        $Links = D("Links"); // 实例化User对象

        if ($Links->data($data)->add()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param int $limit 限制
     * @param int $tag 标签
     * @param string $order 顺序
     * @return mixed 如果找到返回数组
     */
    public function getList($limit = 10, $tag = 1, $order = 'link_sort desc ,link_id asc')
    {
        $link_list = $this->where($tag)->order($order)->limit($limit)->select();
        return $link_list;
    }


    /**
     * @param $id 需要删除的id
     * @return bool 是否删除成功
     */
    public function del($id)
    {
        if ($this->where(array('link_id' => $id))->delete()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param $id 需要查询id
     * @return mixed 详细信息
     */
    public function detail($id)
    {
        $link_list = $this->where(array('link_id' => $id))->find();
        return $link_list;
    }
}