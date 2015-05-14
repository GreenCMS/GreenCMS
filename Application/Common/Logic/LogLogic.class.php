<?php
/**
 * Created by PhpStorm.
 * User: Timothy Zhang
 * Date: 14-6-21
 * Time: 下午8:17
 */

namespace Common\Logic;


use Common\Model\LogModel;
use Think\Model\RelationModel;

class LogLogic extends RelationModel
{

    public function countAll($where)
    {
        $Log = D('Log');

        return $Log->where($where)->count();
    }

    public function addLog($group_name = '', $module_name = '', $action_name = '', $message = '', $log_type = 1)
    {

        echo "addLog";
        $Log = D('Log');

        $log_data['user_id'] = get_current_user_id();
        $log_data['group_name'] = $group_name;
        $log_data['module_name'] = $module_name;
        $log_data['action_name'] = $action_name;
        $log_data['message'] = $message;
        $log_data['log_type'] = $log_type;
        $insert_res = $Log->data($log_data)->add();
        return $insert_res;
    }

    public function getList($limit = 0, $where = array(), $relation = true)
    {

        $Log = D('Log');
        $log_list = $Log->where($where)->limit($limit)->relation($relation)->select();

        return $log_list;
    }
}