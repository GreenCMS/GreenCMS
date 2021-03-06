<?php

namespace Addons\SocialComment;
use Common\Controller\Addon;

/**
 * 通用社交化评论插件
 */

class SocialCommentAddon extends Addon
{

    /**
     * @var array $info 描述信息
     */
    public $info = array(
        'name'        => 'SocialComment',
        'title'       => '通用社交化评论',
        'description' => '集成了各种社交化评论插件，轻松集成到系统中。',
        'status'      => 1,
        'author'      => 'thinkphp',
        'version'     => '0.1'
    );


    /**
     * @function install 安装
     * @return bool
     */
    public function install()
    {
        return true;
    }

    /**
     * @function uninstall 卸载
     * @return bool
     */
    public function uninstall()
    {
        return true;
    }

     /**
     * @function documentDetailAfter 实现的documentDetailAfter钩子方法
     * @param $param
     */
    public function documentDetailAfter($param)
    {
        $this->assign('addons_config', $this->getConfig());
        $this->display('comment');
    }
}