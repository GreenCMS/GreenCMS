<?php
/**
 * Created by GreenStudio GCS Dev Team.
 * File: function.php
 * User: Timothy Zhang
 * Date: 14-1-14
 * Time: 下午11:09
 */


/**
 * @param $data
 * @param array $map
 * @return array
 */
function int_to_string(&$data, $map = array('status' => array(1 => '正常', -1 => '删除', 0 => '禁用', 2 => '未审核', 3 => '草稿')))
{
    if ($data === false || $data === null) {
        return $data;
    }
    $data = (array)$data;
    foreach ($data as $key => $row) {
        foreach ($map as $col => $pair) {
            if (isset($row[$col]) && isset($pair[$row[$col]])) {
                $data[$key][$col . '_text'] = $pair[$row[$col]];
            }
        }
    }
    return $data;
}


/**
 * @param $i
 * @return mixed
 */
function int_to_status($i)
{
    $map = array('status' => array(1 => '启用', -1 => '删除', 0 => '禁用', 99 => '未安装'));

    return $map['status'][$i];
}


/**
 * @param $i
 * @return mixed
 */
function int_to_login_type($i)
{
    $map = array('status' => array(1 => '登录成功', -1 => '帐号不存在或已禁用', 2 => 'cookie自动登录', 3 => 'Oauth第三方登陆', 0 => '密码错误或者帐号已禁用'));

    return $map['status'][$i];
}


/**
 * @param $string
 * @return mixed
 */
function get_real_string($string)
{

    $map = array('single' => '文章', 'page' => '页面');

    return $map[$string];
}


function arr_merge($data){
    $arr=array();
    foreach ($data as $k => $v) {
        $arr[$v['user_id']][$v['user_id']]++;
    }
    return $arr;
}

