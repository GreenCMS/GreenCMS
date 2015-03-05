<?php
/**
 * Created by PhpStorm.
 * User: zts1993
 * Date: 2014/11/20
 * Time: 13:00
 */

namespace Common\Util;


class GreenMail
{


    public $config = array();


    function __construct()
    {
        include Extend_PATH . 'PHPMailer/phpmailer.class.php'; //从PHPMailer目录导phpmailer.class.php类文件


        $this->config = array(
            'smtp_host' => get_opinion('smtp_host'),
            'smtp_port' => get_opinion('smtp_port'),
            'smtp_user' => get_opinion('smtp_user'),
            'smtp_pass' => get_opinion('smtp_pass'),
            'from_email' => get_opinion('from_email'),
            'from_name' => get_opinion('title'),
            'mailer' => get_opinion('mail_method', true, 'mail')
        );
    }


    /**
     * 邮件发送函数
     * @param string $to 接收邮件者邮箱
     * @param string $name 接收邮件者名称
     * @param string $subject 邮件主题
     * @param string $body 邮件内容
     * @param string $attachment 附件列表
     *
     * @param string $config
     *
     * @return boolean
     */
    public function sendMail($to, $name, $subject = '', $body = '', $attachment = null, $config = '')
    {
        $config = is_array($config) ? $config : $this->config; //从数据库读取smtp配置

        $mail = new \PHPMailer(); //PHPMailer对象
        $mail->CharSet = 'UTF-8'; //设定邮件编码，默认ISO-8859-1，如果发中文此项必须设置，否则乱码


        if ($this->config['mailer'] == 'smtp') {
            $mail->IsSMTP(); // 设定使用SMTP服务
            $mail->SMTPDebug = 0; // 关闭SMTP调试功能 1 = errors and messages 2 = messages only
            $mail->SMTPAuth = true; // 启用 SMTP 验证功能
            if ($config['smtp_port'] == 465) $mail->SMTPSecure = 'ssl'; // 使用安全协议
            $mail->Host = $config['smtp_host']; // SMTP 服务器
            $mail->Port = $config['smtp_port']; // SMTP服务器的端口号
            $mail->Username = $config['smtp_user']; // SMTP服务器用户名
            $mail->Password = $config['smtp_pass']; // SMTP服务器密码

        } else if ($this->config['mailer'] == 'mail') {
            $mail->IsMail(); // 设定使用Mail服务
        } else {
            $mail->IsMail(); // 设定使用Mail服务
        }
//        echo $mail->Mailer;
        //    $mail->IsHTML(true);

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

        $sendInfo['statue'] = $mail->Send() ? true : false;
        $sendInfo['info'] = $sendInfo['statue'] ? "发送成功" : $mail->ErrorInfo;

        return $sendInfo;
    }


    public function send(GreenMailContent $greenEmail)
    {
        return $this->sendMail($greenEmail->to, "", $greenEmail->subject, $greenEmail->body, $greenEmail->attachment);
    }


}