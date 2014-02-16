<?php
namespace Think\Template\TagLib;
use Think\Template\TagLib;

defined('THINK_PATH') or exit();
class GreenCMS extends TagLib
{

    // 标签定义
    protected $tags = array(
        // 标签定义： attr 属性列表 close 是否闭合（0 或者1 默认1） alias 标签别名 level 嵌套层次

        'plugin' => array('attr' => 'name,method,parameter,function', 'close' => 0),


        'catlist' => array(
            'attr' => 'cat_id,num,num,start,end,length,li_class,ul_class,li_style,ul_style', //

        ),


    );


    /**
     * catlist标签解析 输出自由文章列表
     *
     * @access public
     * @param string $attr
     *            标签属性
     * @param string $content
     *            标签内容
     * @return string void
     */
    public function _catlist($attr, $content)
    {

        global $_fields;

        $tag = $this->parseXmlAttr($attr, 'catlist');

        $cat_id = isset ($tag ['cat_id']) ? ( int )$tag ['cat_id'] : 20;
        $num = isset ($tag ['num']) ? ( int )$tag ['num'] : 5;
        $start = isset ($tag ['start']) ? $tag ['start'] : '';
        $length = isset ($tag ['length']) ? ( int )$tag ['length'] : 20;
        $li_class = isset ($tag ['li_class']) ? 'class="' . $tag ['li_class'] . '"' : '';
        $ul_class = isset ($tag ['ul_class']) ? 'class="' . $tag ['ul_class'] . '"' : '';
        $li_style = isset ($tag ['li_style']) ? 'style="' . $tag ['li_style'] . '"' : '';
        $ul_style = isset ($tag ['ul_style']) ? 'style="' . $tag ['ul_style'] . '"' : '';

        $post_list = D('Cats')->get_posts($cat_id, $num);

        $parseStr = '<ul ' . $ul_style . ' ' . $ul_class . '>';
        foreach ($post_list as $value) {
            $parseStr .= '<li ' . $li_style . ' ' . $li_class . '><a href="' . getSingleURLByID($value['post_id'], 'single') . '" title="' . $value['post_name'] . '"> ' . mb_substr($value['post_name'], 0, $length, 'UTF-8') . ' </a></li>';
        }
        $parseStr .= '</ul>';

        if (!empty ($parseStr)) {
            return $parseStr;
        }
        return;

    }


    /**
     * 插件挂载  输出插件相应方法执行结果
     * @access public
     * @param string $attr 标签属性
     * @internal param string $content 标签内容
     * @return string|void
     */


    public function _plugin($attr)
    {
        $tag = $this->parseXmlAttr($attr, 'plugin');
        $name = isset($tag['name']) ? $tag['name'] : '';
        $method = isset($tag['method']) ? $tag['method'] : 'index';
        $parameter = isset($tag['parameter']) ? $tag['parameter'] : '';
        if (empty($name) || empty($method)) return;
        $parseStrson = 'plugin(\'' . $name . '\',\'' . $method . '\',\'' . $parameter . '\')';
        $function = empty($tag['function']) ? '@me' : rtrim($tag['function'], ';');
        $function = strtr($function, array('@me' => $parseStrson));
        $parseStr = '<?php echo ' . $function . ';?>';
        return $parseStr;
    }


}
	
	