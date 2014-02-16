<?php
namespace Think\Template\TagLib;

use Think\Template\TagLib;

class Green extends TagLib
{


    // 标签定义
    protected $tags = array(
        // 标签定义： attr 属性列表 close 是否闭合（0 或者1 默认1） alias 标签别名 level 嵌套层次
        'catlist'    => array(
            'attr'  => 'cat_id,num,start,length,li_attr,ul_attr',
            'alias' => 'cli'
        ),
        'taglist'    => array(
            'attr'  => 'tag_id,num,start,length,li_attr,ul_attr',
            'alias' => 'tli'
        ),
        'friendlist' => array(
            'attr'  => 'order,num,link_tag,li_attr,ul_attr',
            'alias' => 'fli'
        ),
        'green'      => array()


    );

    /**
     * @param $tag
     * @param $content PHP代码
     * @usage  <green>原生php代码</green>
     * @return string
     */
    public function _green($tag, $content)
    {
        $parseStr = '<?php ' . $content . ' ?>';
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
            $parseStr .= '<li ' . $li_attr . '><a href="' . getSingleURLByID($value['post_id'], 'single') . '" title="' . $value['post_name'] . '"> ' . mb_substr($value['post_name'], 0, $length, 'UTF-8') . ' </a></li>';
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
            $parseStr .= '<li ' . $li_attr . '><a href="' . getSingleURLByID($value['post_id'], 'single') . '" title="' . $value['post_name'] . '"> ' . mb_substr($value['post_name'], 0, $length, 'UTF-8') . ' </a></li>';
        }
        $parseStr .= '</ul>';

        if (!empty ($parseStr)) {
            return $parseStr;
        }
        return;
    }

    public function _friendlist($tag, $content)
    {
        /**
         *   'friendlist' => array(
        'attr'  => 'num,link_tag,li_attr,ul_attr',
        'alias' => 'fli'
        ),
         */
        $num = isset ($tag ['num']) ? ( int )$tag ['num'] : 5;
        $link_tag = isset ($tag ['link_tag']) ? $tag ['link_tag'] : 'Home';
        $order = isset ($tag ['order']) ? $tag ['order'] : 'link_sort desc ,link_id asc';
        $li_attr = isset ($tag ['li_attr']) ? $tag ['li_attr'] : '';
        $ul_attr = isset ($tag ['ul_attr']) ? $tag ['ul_attr'] : '';

        $link_list = D('Links', 'Logic')->getList($num, $link_tag, $order);

        $parseStr = '<ul ' . $ul_attr . '>';
        foreach ($link_list as $value) {
            $parseStr .= '<li ' . $li_attr . '><a href="' . $value['link_url'] . '" title="' .
                $value['link_name'] . '"> ' . $value['link_name'] . ' </a></li>';
        }
        $parseStr .= '</ul>';

        if (!empty ($parseStr)) {
            return $parseStr;
        }

    }

}
	
	