<?php
namespace Think\Template\TagLib;

use Think\Template\TagLib;

class Green extends TagLib
{


    // 标签定义
    protected $tags = array(
        // 标签定义： attr 属性列表 close 是否闭合（0 或者1 默认1） alias 标签别名 level 嵌套层次
        'catlist' => array(
            'attr' => 'cat_id,num,start,length,li_attr,ul_attr',
        ),
        'taglist' => array(
            'attr' => 'tag_id,num,start,length,li_attr,ul_attr',
        ),
        'green'   => array(),


    );

    public function _green($tag, $content)
    {
        $parseStr = '<?php ' . $content . ' ?>';
        return $parseStr;
    }

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

    public function _taglist($tag, $content)
    {
        $tag_id = isset ($tag ['tag_id']) ? ( int )$tag ['tag_id'] : 1;
        $num = isset ($tag ['num']) ? ( int )$tag ['num'] : 5;
        $start = isset ($tag ['start']) ? $tag ['start'] : 0;
        $length = isset ($tag ['length']) ? ( int )$tag ['length'] : 20;
        $li_attr = isset ($tag ['li_attr']) ? $tag ['li_attr'] : '';
        $ul_attr = isset ($tag ['ul_attr']) ? $tag ['ul_attr'] : '';

        $post_list = D('Tags', 'Logic')->getPostsByTag($tag_id, $num,$start);

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

}
	
	