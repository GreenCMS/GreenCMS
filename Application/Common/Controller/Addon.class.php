<?php

namespace Common\Controller;

    /**
     * 插件类
     */
/**
 * Class Addon
 * @package Common\Controller
 */
abstract class Addon
{
    /**
     * 视图实例对象
     * @var view
     * @access protected
     */
    protected $view = null;

    /**
     * $info = array(
     *  'name'=>'Editor',
     *  'title'=>'编辑器',
     *  'description'=>'用于增强整站长文本的输入和显示',
     *  'status'=>1,
     *  'author'=>'xjh1994',
     *  'version'=>'0.1'
     *  )
     */
    public $info = array();
    /**
     * @var string
     */
    public $addon_path = '';
    /**
     * @var string
     */
    public $config_file = '';
    /**
     * @var string
     */
    public $custom_config = '';
    /**
     * @var array
     */
    public $admin_list = array();
    /**
     * @var string
     */
    public $custom_adminlist = '';
    /**
     * @var array
     */
    public $access_url = array();

    /**
     *
     */
    public function __construct()
    {
        $this->view = \Think\Think::instance('Think\View');
        $this->addon_path = Addon_PATH . $this->getName() . '/';
        $TMPL_PARSE_STRING = get_opinion('TMPL_PARSE_STRING');
        $TMPL_PARSE_STRING['__ADDONROOT__'] = __ROOT__ . '/Addons/' . $this->getName();
        get_opinion('TMPL_PARSE_STRING', $TMPL_PARSE_STRING);
        if (is_file($this->addon_path . 'config.php')) {
            $this->config_file = $this->addon_path . 'config.php';
        }
    }

    /**
     * 模板主题设置
     * @access protected
     * @param string $theme 模版主题
     * @return Action
     */
    final protected function theme($theme)
    {
        $this->view->theme($theme);
        return $this;
    }

    //显示方法
    /**
     * @param string $template
     */
    final protected function display($template = '')
    {
        if ($template == '')
            $template = CONTROLLER_NAME;
        echo($this->fetch($template));
    }

    /**
     * 模板变量赋值
     * @access protected
     * @param mixed $name 要显示的模板变量
     * @param mixed $value 变量的值
     * @return Action
     */
    final protected function assign($name, $value = '')
    {
        $this->view->assign($name, $value);
        return $this;
    }


    //用于显示模板的方法
    /**
     * @param mixed|string $templateFile
     * @return mixed
     * @throws \Exception
     */
    final protected function fetch($templateFile = CONTROLLER_NAME)
    {
        if (!is_file($templateFile)) {
            $templateFile = $this->addon_path . $templateFile . get_opinion('TMPL_TEMPLATE_SUFFIX');
            if (!is_file($templateFile)) {
                throw new \Exception("模板不存在:$templateFile");
            }
        }
        return $this->view->fetch($templateFile);
    }

    /**
     * @return string
     */
    final public function getName()
    {
        $class = get_class($this);
        return substr($class, strrpos($class, '\\') + 1, -5);
    }

    /**
     * @return bool
     */
    final public function checkInfo()
    {
        $info_check_keys = array('name', 'title', 'description', 'status', 'author', 'version');
        foreach ($info_check_keys as $value) {
            if (!array_key_exists($value, $this->info))
                return FALSE;
        }
        return TRUE;
    }

    /**
     * 获取插件的配置数组
     */
    final public function getConfig($name = '')
    {
        static $_config = array();
        if (empty($name)) {
            $name = $this->getName();
        }
        if (isset($_config[$name])) {
            return $_config[$name];
        }
        $config = array();
        $map['name'] = $name;
        $map['status'] = 1;
        $config = M('Addons')->where($map)->getField('config');
        if ($config) {
            $config = json_decode($config, true);
        } else {
            $temp_arr = include $this->config_file;
            foreach ($temp_arr as $key => $value) {
                $config[$key] = $temp_arr[$key]['value'];
            }
        }
        $_config[$name] = $config;
        return $config;
    }

    //必须实现安装
    /**
     * @return mixed
     */
    abstract public function install();

    //必须卸载插件方法
    /**
     * @return mixed
     */
    abstract public function uninstall();


    public function addNewHook($hook_name, $description)
    {

        $Hooks = D('Hooks');
        $new_hook = $Hooks->where(array('name' => $hook_name))->find();
        if (!$new_hook) {
            $data = array('name' => $hook_name, 'description' => $description, 'type' => 1);
            $Hooks->data($data)->add();
        }
    }

    public function excuteSql($sql)
    {

        M()->query($sql);

    }


}
