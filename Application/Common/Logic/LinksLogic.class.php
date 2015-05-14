<?php
/**
 * Created by GreenStudio GCS Dev Team.
 * File: LinksLogic.class.php
 * User: Timothy Zhang
 * Date: 14-1-16
 * Time: 上午12:29
 */

namespace Common\Logic;

use Think\Model\RelationModel;

/**
 * 链接逻辑定义
 * Class LinksLogic
 * @package Home\Logic
 */
class LinksLogic extends RelationModel
{

    /**
     * 添加链接
     * @param $data 数据
     *
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
     * 获取list
     * @param int $limit 限制
     * @param int $link_group_id
     * @param string $order 顺序
     *
     * @internal param int $tag 标签
     * @return mixed 如果找到返回数组
     */
    public function getList($limit = 10, $link_group_id = 0, $order = 'link_sort desc ,link_id asc')
    {
        $condition['link_group_id'] = $link_group_id;

        //兼容旧版本
        if ($link_group_id == 0) {
            $condition = "link_group_id is null or link_group_id=0";
        }
        $link_list = D('Links')->where($condition)->order($order)->limit($limit)->relation(true)->select();

        return $link_list;
    }


    /**
     * 删除链接
     * @param $id 需要删除的id
     *
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
     * 获取链接详细详细
     * @param $id 需要查询id
     *
     * @return mixed 详细信息
     */
    public function detail($id)
    {
        $link_list = $this->where(array('link_id' => $id))->find();
        return $link_list;
    }
}