<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2013 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------
namespace Common\Util;

    /**
     * Class GreenPage
     * @package Common\Util
     */
/**
 * Class GreenPage
 * @package Common\Util
 */
class GreenPage
{

    /**
     * 分页栏每页显示的页数
     * @var int
     */
    public $rollPage = 5;

    /**
     * 页数跳转时要带的参数
     * @var array|string
     */

    public $parameter;
    /**
     * 分页URL地址
     * @var string
     */
    public $url = '';

    /**
     *  默认列表每页显示行数
     * @var int
     */
    public $listRows = 20;

    /**
     * 起始行数
     * @var int
     */
    public $firstRow;

    /**
     * 分页总页面数
     * @var float
     */
    protected $totalPages;

    /**
     * 总行数
     * @var array
     */
    protected $totalRows;

    /**
     * 当前页数
     * @var float
     */
    protected $nowPage;

    /**
     * 分页的栏的总页数
     * @var float
     */
    protected $coolPages;

    /**
     * 分页显示定制/现在第%nowPage%页
     * @var array
     */
    protected $config =
        array('header' => '条记录', 'prev' => '« 上一页', 'next' => '下一页 »',
            'first' => '第一页', 'last' => '最后一页',
            'theme' => ' %first%  %prePage%  %upPage% %linkPage% %downPage%   %nextPage% %end%');

    /**
     *  默认分页变量名
     * @var mixed|string
     */
    protected $varPage;

    /**
     * 架构函数
     * @access public
     *
     * @param int $totalRows 总的记录数
     * @param array|string $listRows 每页显示记录数
     * @param array|string $parameter 分页跳转的参数
     * @param string $url
     * @param string $url
     */
    public function __construct($totalRows, $listRows = '', $parameter = '', $url = '')
    {
        $this->totalRows = $totalRows;
        $this->parameter = $parameter;
        $this->varPage = C('VAR_PAGE') ? C('VAR_PAGE') : 'p';
        if (!empty($listRows)) {
            $this->listRows = intval($listRows);
        }
        $this->totalPages = ceil($this->totalRows / $this->listRows); //总页数
        $this->coolPages = ceil($this->totalPages / $this->rollPage);
        $this->nowPage = !empty($_GET[$this->varPage]) ? intval($_GET[$this->varPage]) : 1;
        if ($this->nowPage < 1) {
            $this->nowPage = 1;
        } elseif (!empty($this->totalPages) && $this->nowPage > $this->totalPages) {
            $this->nowPage = $this->totalPages;
        }
        $this->firstRow = $this->listRows * ($this->nowPage - 1);
    }

    /**
     * @param $name
     * @param $value
     */
    public function setConfig($name, $value)
    {
        if (isset($this->config[$name])) {
            $this->config[$name] = $value;
        }
    }

    /**
     * 分页显示输出
     * @access public
     */
    public function show()
    {
        if (0 == $this->totalRows) return '';
        $p = $this->varPage;
        $nowCoolPage = ceil($this->nowPage / $this->rollPage);

        // 分析分页参数
        if ($this->url) {
            $depr = C('URL_PATHINFO_DEPR');
            $url = rtrim(U('/' . $this->url, '', false), $depr) . $depr . '__PAGE__';
        } else {
            if ($this->parameter && is_string($this->parameter)) {
                parse_str($this->parameter, $parameter);
            } elseif (is_array($this->parameter)) {
                $parameter = $this->parameter;
            } elseif (empty($this->parameter)) {
                unset($_GET[C('VAR_URL_PARAMS')]);
                $var = !empty($_POST) ? $_POST : $_GET;
                if (empty($var)) {
                    $parameter = array();
                } else {
                    $parameter = $var;
                }
            }
            $parameter[$p] = '__PAGE__';
            $url = U('', $parameter);
        }
        //上下翻页字符串
        $upRow = $this->nowPage - 1;
        $downRow = $this->nowPage + 1;
        if ($upRow > 0) {
            $upPage = "<li><a href='" . str_replace('__PAGE__', $upRow, $url) . "'>" . $this->config['prev'] . "</a></li>";
        } else if ($this->totalPages != 1) {
            $upPage = "<li><a  >第一页</a></li>";
        } else {
            $upPage = "";
        }

        if ($downRow <= $this->totalPages) {
            $downPage = "<li><a href='" . str_replace('__PAGE__', $downRow, $url) . "'>" . $this->config['next'] . "</a></li>";
        } else if ($this->totalPages != 1) {
            $downPage = "<li><a>最后一页</a></li>";
        } else {
            $upPage = "";
        }
        // << < > >>
        if ($nowCoolPage == 1) {
            $theFirst = '';
            $prePage = '';
        } else {
            $preRow = $this->nowPage - $this->rollPage;
            $prePage = "<li><a href='" . str_replace('__PAGE__', $preRow, $url) . "' >上" . $this->rollPage . "页</a></li>";
            $theFirst = "<li><a href='" . str_replace('__PAGE__', 1, $url) . "' >" . $this->config['first'] . "</a></li>";
        }
        if ($nowCoolPage == $this->coolPages) {
            $nextPage = '';
            $theEnd = '';
        } else {
            $nextRow = $this->nowPage + $this->rollPage;
            $theEndRow = $this->totalPages;
            $nextPage = "<li><a href='" . str_replace('__PAGE__', $nextRow, $url) . "' >下" . $this->rollPage . "页</a></li>";
            $theEnd = "<li><a href='" . str_replace('__PAGE__', $theEndRow, $url) . "' >" . $this->config['last'] . "</a></li>";
        }
        // 1 2 3 4 5
        $linkPage = "";
        for ($i = 1; $i <= $this->rollPage; $i++) {
            $page = ($nowCoolPage - 1) * $this->rollPage + $i;
            if ($page != $this->nowPage) {
                if ($page <= $this->totalPages) {
                    $linkPage .= "<li><a  class='number'  href='" . str_replace('__PAGE__', $page, $url) . "'>" . $page . "</a></li>";
                } else {
                    break;
                }
            } else {
                if ($this->totalPages != 1) {
                    $linkPage .= "<li><a class='number current'>" . $page . "</a></li>";
                }
            }
        }
        $pageStr = str_replace(
            array('%header%', '%nowPage%', '%totalRow%', '%totalPage%', '%upPage%', '%downPage%', '%first%', '%prePage%', '%linkPage%', '%nextPage%', '%end%'),
            array($this->config['header'], $this->nowPage, $this->totalRows, $this->totalPages, $upPage, $downPage, $theFirst, $prePage, $linkPage, $nextPage, $theEnd), $this->config['theme']);
        return $pageStr;
    }

}