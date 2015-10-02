<?php
/**
 * Created by PhpStorm.
 * User: TianShuo
 * Date: 2015/3/1
 * Time: 21:09
 */

namespace Common\Util;

use Common\Logic\PostsLogic;
use Think\Template\TagLib;


class GreenCMSTemplate extends TagLib
{


    // 标签定义
    /**
     * @var array
     */
    protected $tags = array(
        // 标签定义： attr 属性列表 close 是否闭合（0 或者1 默认1） alias 标签别名 level 嵌套层次
        'catlist' => array(
            'attr' => 'cat_id,num,start,length,li_attr,ul_attr',
            'alias' => 'cli'
        ),
        'taglist' => array(
            'attr' => 'tag_id,num,start,length,li_attr,ul_attr',
            'alias' => 'tli'
        ),
        'recentlist' => array(
            'attr' => 'num,type,order,relation,length,li_attr,ul_attr',
            'alias' => 'rli'
        ),
        'friendlist' => array(
            'attr' => 'length,order,num,link_tag,link_group_id,li_attr,ul_attr',
            'alias' => 'fli'
        ),
        'accesscontrol' => array(
            'attr' => 'access',
            'alias' => 'control'
        ),
        'opinioncontrol' => array(
            'attr' => 'realtime,opinion_name,default,judge',
            'alias' => 'opctl'
        ),


        'optionfriendlist' => array(
            'attr' => 'cat,num,link_group_id,order',
            'alias' => 'opfli'
        ),


        'switchopinioncontrol' => array(
            'attr' => 'opinion_name,default,value_true,text_true,value_false,text_false,value_judge',
            'alias' => 'sopctl'
        ),


        'dump' => array()


    );

    /**
     * @param $tag
     * @param string $content 原生php变量
     * @usage  <dump>原生php变量</dump>
     * @return string
     */
    public function _dump($tag, $content)
    {
        $parseStr = '<?php dump(' . $content . ') ?>';
        return $parseStr;
    }

    /**
     * @param $tag
     * @param $content
     * @usage <catlist cat_id="分类id" num="数量" start="起始偏移"  length="字长度" li_attr='li属性' ul_attr="ul属性"></catlist>
     * @return string
     */
    public function _catlist($tag, $content)
    {
        $cat_id = isset ($tag ['cat_id']) ? ( int )$tag ['cat_id'] : 1;
        $num = isset ($tag ['num']) ? ( int )$tag ['num'] : 5;
        $start = isset ($tag ['start']) ? $tag ['start'] : 0;
        $length = isset ($tag ['length']) ? ( int )$tag ['length'] : 20;
        $li_attr = isset ($tag ['li_attr']) ? $tag ['li_attr'] : '';
        $ul_attr = isset ($tag ['ul_attr']) ? $tag ['ul_attr'] : '';

        $post_list = D('Cats', 'Logic')->getPostsByCat($cat_id, $num, $start);

        $parseStr = '<ul ' . $ul_attr . '>';
        foreach ($post_list as $value) {
            $parseStr .= '<li ' . $li_attr . '><a href="' . getSingleURLByID($value['post_id'], 'single') . '" title="' . $value['post_title'] . '"> ' . mb_substr($value['post_title'], 0, $length, 'UTF-8') . ' </a></li>';
        }
        $parseStr .= '</ul>';

        if (!empty ($parseStr)) {
            return $parseStr;
        }
        return;
    }

    /**
     * @param $tag
     * @param $content
     * @usage <taglist tag_id="标签id" num="数量" start="起始偏移"  length="字长度" li_attr='li属性' ul_attr="ul属性"></taglist>
     * @return string
     */
    public function _taglist($tag, $content)
    {
        $tag_id = isset ($tag ['tag_id']) ? ( int )$tag ['tag_id'] : 1;
        $num = isset ($tag ['num']) ? ( int )$tag ['num'] : 5;
        $start = isset ($tag ['start']) ? $tag ['start'] : 0;
        $length = isset ($tag ['length']) ? ( int )$tag ['length'] : 20;
        $li_attr = isset ($tag ['li_attr']) ? $tag ['li_attr'] : '';
        $ul_attr = isset ($tag ['ul_attr']) ? $tag ['ul_attr'] : '';

        $post_list = D('Tags', 'Logic')->getPostsByTag($tag_id, $num, $start);

        $parseStr = '<ul ' . $ul_attr . '>';
        foreach ($post_list as $value) {
            $parseStr .= '<li ' . $li_attr . '><a href="' . getSingleURLByID($value['post_id'], 'single') . '" title="' . $value['post_title'] . '"> ' . mb_substr($value['post_title'], 0, $length, 'UTF-8') . ' </a></li>';
        }
        $parseStr .= '</ul>';

        if (!empty ($parseStr)) {
            return $parseStr;
        }
        return;
    }

    /**
     * @param $tag
     * @param $content
     * @usage <friendlist link_group_id="link_group_id" num="数量" order="排序"  length="字长度" li_attr='li属性' ul_attr="ul属性"></friendlist>
     * @return string
     */
    public function _friendlist($tag, $content)
    {
        $num = isset ($tag ['num']) ? ( int )$tag ['num'] : 5;
        $link_tag = isset ($tag ['link_tag']) ? $tag ['link_tag'] : '';
        $link_group_id = isset ($tag ['link_group_id']) ? $tag ['link_group_id'] : '';
        $order = isset ($tag ['order']) ? $tag ['order'] : 'link_sort desc ,link_id asc';
        $li_attr = isset ($tag ['li_attr']) ? $tag ['li_attr'] : '';
        $ul_attr = isset ($tag ['ul_attr']) ? $tag ['ul_attr'] : '';
        $length = isset ($tag ['length']) ? ( int )$tag ['length'] : 20;
        $condition = array();
        if ($link_tag != '') {

            $condition['link_tag'] = $link_tag;
        } else if ($link_group_id != '') {
            $condition['link_group_id'] = $link_group_id;
        } else {
        }
        $link_list = D('Links', 'Logic')->cache(true, 2)->where($condition)->order($order)->limit($num)->select();
        $parseStr = '<ul ' . $ul_attr . '>';
        foreach ($link_list as $value) {
            if ($value['link_img'] == "") {
                $parseStr .= '<li ' . $li_attr . '><a href="' . $value['link_url'] . '" title="' .
                    $value['link_name'] . '"> ' . mb_substr($value['link_name'], 0, $length, 'UTF-8') . ' </a></li>';

            } else {
                $parseStr .= '<li ' . $li_attr . '><a href="' . $value['link_url'] . '" title="' .
                    $value['link_name'] . '">  <img src="' . $value['link_img'] . '"/> </a></li>';

            }


        }
        $parseStr .= '</ul>';
        if (!empty ($parseStr)) {
            return $parseStr;
        }
    }


    /**
     * @param $tag
     * @param $content
     * @usage <recentlist type="文章类型" num="数量"  order="排序" relation="是否关联"  length="字长度" li_attr='li属性' ul_attr="ul属性"></recentlist>
     * @return string
     */
    public function _recentlist($tag, $content)
    {
        $PostsLogic = new PostsLogic();

        $num = isset ($tag ['num']) ? ( int )$tag ['num'] : 5;
        $post_type = isset ($tag ['type']) ? $tag ['type'] : 'single';
        $order = isset ($tag ['order']) ? $tag ['order'] : 'post_date desc';
        $relation = isset ($tag ['relation']) ? $tag ['relation'] : false;
        $li_attr = isset ($tag ['li_attr']) ? $tag ['li_attr'] : '';
        $ul_attr = isset ($tag ['ul_attr']) ? $tag ['ul_attr'] : '';
        $length = isset ($tag ['length']) ? ( int )$tag ['length'] : 20;

        $info['post_type'] = $post_type;

        $post_list = $PostsLogic->getList($num, $post_type, $order, $relation, $info);

        $parseStr = '<ul ' . $ul_attr . '>';
        foreach ($post_list as $value) {
            $parseStr .= '<li ' . $li_attr . '>
            <a href="' . getSingleURLByID($value['post_id']) . '" title="' .
                $value['post_title'] . '">
                ' . mb_substr($value['post_title'], 0, $length, "UTF-8") . ' </a></li>';
        }
        $parseStr .= '</ul>';

        if (!empty ($parseStr)) {
            return $parseStr;
        }


    }


    /**
     * @param $tag
     * @param $content
     * @usage <control access="Admin/Posts/NOVERIFY">需要权限而显示的内容</control>
     * access="Group/Controller/action"
     * @return bool
     */
    public function _accesscontrol($tag, $content)
    {

        $access = isset ($tag ['access']) ? $tag ['access'] : "";
        if (check_access($access)) {
            return $content;
        } else {
            return false;
        }


    }


    /**
     * @param $tag
     * @param $content
     * @return bool
     * 'opinioncontrol' => array(
     * 'attr' => 'realtime,opinion_name,default,judge',
     * 'alias' => 'opctl'
     * ),

     */
    public function _opinioncontrol($tag, $content)
    {
        $opinion_name = isset ($tag ['opinion_name']) ? $tag ['opinion_name'] : "";

        $realtime = isset ($tag ['realtime']) ? $tag ['realtime'] : false;
        $judge = isset ($tag ['judge']) ? $tag ['judge'] : true;

        $default = isset ($tag ['default']) ? $tag ['default'] : "";


        if (get_opinion($opinion_name, $realtime, $default) == $judge) {
            return $content;
        } else {
            return false;
        }


    }

    /**
     * @param $tag
     * @param $content
     * @return bool
     *
     *'attr' => 'opinion_name,default,value_true,text_true,value_false,text_false,value_judge',

     */
    public function _switchopinioncontrol($tag, $content)
    {
        $default = isset ($tag ['default']) ? $tag ['default'] : 0;

        $opinion_name = isset ($tag ['opinion_name']) ? $tag ['opinion_name'] : "";
        $opinion_value = get_opinion($opinion_name, false, $default);

        $value_true = isset ($tag ['value_true']) ? $tag ['value_true'] : 1;
        $value_false = isset ($tag ['value_false']) ? $tag ['value_false'] : 0;

        $text_true = isset ($tag ['text_true']) ? $tag ['text_true'] : '开';
        $text_false = isset ($tag ['text_false']) ? $tag ['text_false'] : '关';

        $value_judge = isset ($tag ['value_judge']) ? $tag ['value_judge'] : 1;


        $content = '';
        $content .= ' <label class="radio">';

        if ($opinion_value == $value_true) {
            // true
            $content .= '<input type="radio" name="' . $opinion_name . '" checked="checked" value="' . $value_true . '"/>';

        } elseif ($opinion_value == $value_false) {
            // false
            $content .= '<input type="radio" name="' . $opinion_name . '" value="1' . $value_true . '"/>';

        } else {
            $content .= '<input type="radio" name="' . $opinion_name . '" checked="checked" value="' . $value_true . '"/>';
        }
        $content .= $text_true;
        $content .= '</label>';


        $content .= ' <label class="radio">';

        if ($opinion_value == $value_true) {
            // true
            $content .= '<input type="radio" name="' . $opinion_name . '"   value="' . $value_false . '"/>';

        } elseif ($opinion_value == $value_false) {
            // false
            $content .= '<input type="radio" name="' . $opinion_name . '"  checked="checked"  value="' . $value_false . '"/>';

        } else {
            $content .= '<input type="radio" name="' . $opinion_name . '"  value="' . $value_false . '"/>';
        }
        $content .= $text_false;
        $content .= '</label>';


        return $content;
    }


    /**
     * @param $tag
     * @param $content
     * @usage
     * @return string
     */
    public function _optionfriendlist($tag, $content)
    {

        $num = isset ($tag ['num']) ? ( int )$tag ['num'] : 0;
        $link_tag = isset ($tag ['cat']) ? $tag ['cat'] : '';
        $order = isset ($tag ['order']) ? $tag ['order'] : 'link_sort desc ,link_id asc';
        $link_group_id = isset ($tag ['link_group_id']) ? $tag ['link_group_id'] : '';


        if ($link_tag != '') {
            $condition['link_tag'] = $link_tag;
        } else if ($link_group_id != '') {
            $condition['link_group_id'] = $link_group_id;
        } else {
            $condition = array();
        }

        $link_list = D('Links', 'Logic')->cache(true, 2)->where($condition)->order($order)->limit($num)->select();


        $parseStr = '';
        foreach ($link_list as $value) {
            $parseStr .= '<option value="' . $value['link_url'] . '"  >' . $value['link_name'] . ' </option>';
        }
        $parseStr .= '</ul>';

        if (!empty ($parseStr)) {
            return $parseStr;
        }

    }

}