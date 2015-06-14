<?php
/**
 * Created by GreenStudio GCS Dev Team.
 * File: Category.class.php
 * User: Timothy Zhang
 * Date: 14-1-23
 * Time: 上午11:22
 * Use: Category Class For ThinkPHP 3.2
 */

namespace Common\Util;

/**
 * Class Category
 * @package Common\Util
 */
class Category
{

    /**
     * 分类的数据表模型
     * @var object
     */

    private $model;
    /**
     * 原始的分类数据
     * @var array
     */

    public $rawList = array();
    /**
     * 格式化后的分类
     * @var array
     */
    private $formatList = array();

    /**
     * 错误信息
     * @var string
     */
    private $error = "";

    /**
     * 格式化的字符
     * @var array
     */
    private $icon = array('&nbsp;&nbsp;│', '&nbsp;&nbsp;├ ', '&nbsp;&nbsp;└ ');

    /**
     * 字段映射，分类id，上级分类cat_father,分类名称name,格式化后分类名称cat_name
     * @var array
     */
    private $fields = array();

    //
    /**
     * 构造函数，对象初始化
     * @param string $model
     * @param array $fields ,object  $model 数组或对象，基于TP3.2的数据表模型名称
     *
     * @internal param array $field 字段映射，分类cat_id，上级分类cat_father,分类名称,格式化后分类名称cat_name* 字段映射，分类cat_id，上级分类cat_father,分类名称,格式化后分类名称cat_name
     */
    public function __construct($model = '', $fields = array())
    {
        if (is_string($model) && (!empty($model))) {
            if (!$this->model = D($model))
                $this->error = $model . "模型不存在！";
        }
        if (is_object($model))
            $this->model = &$model;

        $this->fields['cat_id'] = $fields['0'] ? $fields['0'] : 'cat_id';
        $this->fields['cat_father'] = $fields['1'] ? $fields['1'] : 'cat_father';
        $this->fields['cat_slug'] = $fields['2'] ? $fields['2'] : 'cat_slug';
        $this->fields['cat_name'] = $fields['3'] ? $fields['3'] : 'cat_name';
    }

    /**
     * 获取分类信息数据
     * @param array ,string  $condition  查询条件
     * @param string $orderby 排序
     */
    private function _findAllCat($condition, $orderby = null)
    {
        $this->rawList = empty($orderby) ? $this->model->where($condition)->select() : $this->model->where($condition)->order($orderby)->select();
    }

    /**
     * 返回给定上级分类$cat_father的所有同一级子分类
     * @param   int $cat_father 传入要查询的cat_father
     *
     * @return  array           返回结构信息
     */
    public function getChild($cat_father)
    {
        $childs = array();
        foreach ($this->rawList as $Category) {
            if ($Category[$this->fields['cat_father']] == $cat_father)
                $childs[] = $Category;
        }
        return $childs;
    }

    /**
     * 递归格式化分类前的字符
     * @param   int $cat_id 分类cat_id
     * @param   string $space
     */
    private function _searchList($cat_id = 0, $space = "")
    {
        $childs = $this->getChild($cat_id);
        //下级分类的数组
        //如果没下级分类，结束递归
        if (!($n = count($childs)))
            return;
        $m = 1;
        //循环所有的下级分类
        for ($i = 0; $i < $n; $i++) {
            $pre = "";
            $pad = "";
            if ($n == $m) {
                $pre = $this->icon[2];
            } else {
                $pre = $this->icon[1];
                $pad = $space ? $this->icon[0] : "";
            }
            $childs[$i][$this->fields['cat_name']] = ($space ? $space . $pre : "") . $childs[$i][$this->fields['cat_slug']];
            $this->formatList[] = $childs[$i];
            $this->_searchList($childs[$i][$this->fields['cat_id']], $space . $pad . "&nbsp;"); //递归下一级分类 &nbsp;
            $m++;
        }
    }


    /**
     * 递归格式化分类前的字符
     * @param   int $cat_id 分类cat_id
     * @param   string $space
     */
    private function _searchListWithCount($cat_id = 0, $space = "")
    {
        $childs = $this->getChild($cat_id);
        //下级分类的数组
        //如果没下级分类，结束递归
        if (!($n = count($childs)))
            return;
        $m = 1;
        //循环所有的下级分类
        for ($i = 0; $i < $n; $i++) {
            $pre = "";
            $pad = "";
            if ($n == $m) {
                $pre = $this->icon[2];
            } else {
                $pre = $this->icon[1];
                $pad = $space ? $this->icon[0] : "";
            }

            $childs[$i][$this->fields['cat_name']] = ($space ? $space . $pre : "") . $childs[$i][$this->fields['cat_slug']];
            $childs[$i]['post_count'] = D("Post_cat")->where(array("cat_id" => $childs[$i]["cat_id"]))->cache(true,1)->count();
            $this->formatList[] = $childs[$i];
            $this->_searchListWithCount($childs[$i][$this->fields['cat_id']], $space . $pad . "&nbsp;"); //递归下一级分类 &nbsp;
            $m++;
        }
    }

    /**
     * 不采用数据模型时，可以从外部传递数据，得到递归格式化分类
     * @param   array ,string     $condition    条件
     * @param   int $cat_id 起始分类
     * @param   string $orderby 排序
     *
     * @return  array           返回结构信息
     */
    public function getList($condition = null, $cat_id = 0, $orderby = null)
    {
        unset($this->rawList, $this->formatList);
        $this->_findAllCat($condition, $orderby, $orderby);
        $this->_searchList($cat_id);
        return $this->formatList;
    }


    /**
     * 不采用数据模型时，可以从外部传递数据，得到递归格式化分类
     * @param   array ,string     $condition    条件
     * @param   int $cat_id 起始分类
     * @param   string $orderby 排序
     *
     * @return  array           返回结构信息
     */
    public function getListWithCount($condition = null, $cat_id = 0, $orderby = null)
    {
        unset($this->rawList, $this->formatList);
        $this->_findAllCat($condition, $orderby, $orderby);
        $this->_searchListWithCount($cat_id);
        return $this->formatList;
    }


    /**
     * 获取结构
     * @param   array $data 二维数组数据
     * @param   int $cat_id 起始分类
     *
     * @return  array           递归格式化分类数组
     */
    public function getTree($data, $cat_id = 0)
    {
        unset($this->rawList, $this->formatList);
        $this->rawList = $data;
        $this->_searchList($cat_id);
        return $this->formatList;
    }

    /**
     * 获取错误信息
     * @return  string           错误信息字符串
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * 检查分类参数$cat_id,是否为空
     * @param   int $cat_id 起始分类
     *
     * @return  boolean           递归格式化分类数组
     */
    private function _checkCatID($cat_id)
    {
        if (intval($cat_id)) {
            return true;
        } else {
            $this->error = "参数分类ID为空或者无效！";
            return false;
        }
    }

    /**
     * 检查分类参数$cat_id,是否为空
     * @param   int $cat_id 分类cat_id
     * @return bool
     */
    private function _searchPath($cat_id)
    {
        //检查参数
        if (!$this->_checkCatID($cat_id))
            return false;
        $rs = $this->model->find($cat_id); //初始化对象，查找上级Id；
        $this->formatList[] = $rs; //保存结果
        $this->_searchPath($rs[$this->fields['cat_father']]);
    }

    /**
     * 查询给定分类cat_id的路径
     *
     * @param   int $cat_id 分类cat_id
     *
     * @return  array                   数组
     */
    public function getPath($cat_id)
    {
        unset($this->rawList, $this->formatList);
        $this->_searchPath($cat_id); //查询分类路径
        return array_reverse($this->formatList);
    }

    /**
     * 添加分类
     * @param   array $data 一维数组，要添加的数据，$data需要包含上级分类ID。
     *
     * @return  boolean                    添加成功，返回相应的分类ID,添加失败，返回FALSE；
     */
    public function add($data)
    {
        if (empty($data))
            return false;
        return $this->model->data($data)->add();
    }

    /**
     * 修改分类
     * @param   array $data 一维数组，$data需要包含要修改的分类cat_id。
     *
     * @return  boolean                 组修改成功，返回相应的分类ID,修改失败，返回FALSE；
     */
    public function edit($data)
    {
        if (empty($data))
            return false;
        return $this->model->data($data)->save();
    }

    /**
     * 删除分类
     * @param   int $cat_id 分类cat_id
     *
     * @return  boolean                 删除成功，返回相应的分类ID,删除失败，返回FALSE
     */
    public function del($cat_id)
    {
        $cat_id = intval($cat_id);
        if (empty($cat_id))
            return false;
        $conditon[$this->fields['cat_id']] = $cat_id;
        return $this->model->where($conditon)->delete();
    }

}
