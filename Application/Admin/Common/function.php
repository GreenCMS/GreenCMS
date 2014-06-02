<?php
/**
 * Created by Green Studio.
 * File: function.php
 * User: TianShuo
 * Date: 14-1-14
 * Time: 下午11:09
 */


/**
 * +----------------------------------------------------------
 * 功能：系统邮件发送函数
 * +----------------------------------------------------------
 * @param string $to 接收邮件者邮箱
 * @param string $name 接收邮件者名称
 * @param string $subject 邮件主题
 * @param string $body 邮件内容
 * @param string $attachment 附件列表
 * +----------------------------------------------------------
 * @param string $config
 *
 * @return boolean
+----------------------------------------------------------
 */
function send_mail($to, $name, $subject = '', $body = '', $attachment = null, $config = '')
{
    // $config = is_array($config) ? $config : C('SYSTEM_EMAIL');
    //从数据库读取smtp配置
    $config = array(
        'smtp_host' => C('smtp_host'),
        'smtp_port' => C('smtp_port'),
        'smtp_user' => C('smtp_user'),
        'smtp_pass' => C('smtp_pass'),
        'from_email' => C('from_email'),
        'from_name' => C('title')
    );

    include Extend_PATH . 'PHPMailer/phpmailer.class.php'; //从PHPMailer目录导phpmailer.class.php类文件
    $mail = new PHPMailer(); //PHPMailer对象
    $mail->CharSet = 'UTF-8'; //设定邮件编码，默认ISO-8859-1，如果发中文此项必须设置，否则乱码
    $mail->IsSMTP(); // 设定使用SMTP服务
//    $mail->IsHTML(true);
    $mail->SMTPDebug = 0; // 关闭SMTP调试功能 1 = errors and messages2 = messages only
    $mail->SMTPAuth = true; // 启用 SMTP 验证功能
    if ($config['smtp_port'] == 465)
        $mail->SMTPSecure = 'ssl'; // 使用安全协议
    $mail->Host = $config['smtp_host']; // SMTP 服务器
    $mail->Port = $config['smtp_port']; // SMTP服务器的端口号
    $mail->Username = $config['smtp_user']; // SMTP服务器用户名
    $mail->Password = $config['smtp_pass']; // SMTP服务器密码
    $mail->SetFrom($config['from_email'], $config['from_name']);
    $replyEmail = $config['reply_email'] ? $config['reply_email'] : $config['reply_email'];
    $replyName = $config['reply_name'] ? $config['reply_name'] : $config['reply_name'];
    $mail->AddReplyTo($replyEmail, $replyName);
    $mail->Subject = $subject;
    $mail->MsgHTML($body);
    $mail->AddAddress($to, $name);
    if (is_array($attachment)) { // 添加附件
        foreach ($attachment as $file) {
            if (is_array($file)) {
                is_file($file['path']) && $mail->AddAttachment($file['path'], $file['name']);
            } else {
                is_file($file) && $mail->AddAttachment($file);
            }
        }
    } else {
        is_file($attachment) && $mail->AddAttachment($attachment);
    }
    return $mail->Send() ? true : $mail->ErrorInfo;
}

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


function array_column_5($array, $col_value, $col_key)
{

    $res = array();
    foreach ($array as $item) {
        $res[$item[$col_key]] = $item[$col_value];

    }
    return $res;
}

