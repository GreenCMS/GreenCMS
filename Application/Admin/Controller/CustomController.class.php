<?php
/**
 * Created by Green Studio.
 * File: CustomController.class.php
 * User: TianShuo
 * Date: 14-1-26
 * Time: 下午5:26
 */

namespace Admin\Controller;

use Common\Util\Category;
use Common\Util\File;
use Common\Util\GreenPage;

class CustomController extends AdminBaseController
{

    public function index()
    {


    }

    //TODO menu
    public function menu()
    {
        $Menu = new Category ('Menu', array('menu_id', 'menu_pid', 'menu_name', 'menu_construct'));

        $menu_list = $Menu->getList(); // 获取分类结构

        $this->assign('menu', $menu_list);

        $this->display();
    }

    public function menuDel($id, $child = false)
    {
        $Menu = D('Menu');

        $Menu->where(array('menu_id' => $id))->delete();
        if ($child) {
            $res = $Menu->where(array('menu_pid' => $id))->delete();
        } else {
            $data = array('menu_pid' => 0);
            $res = $Menu->where(array('menu_pid' => $id))->setField($data);
        }
        //TODO 判断
        $this->success('删除成功', 'Admin/Custom/menu');

    }

    public function menuAdd()
    {

        $action = '添加菜单';
        $action_url = U('Admin/Custom/menuAdd');
        $form_url = U('Admin/Custom/menuAddHandle');

        $Menu = new Category ('Menu', array('menu_id', 'menu_pid', 'menu_name', 'menu_construct'));
        $menu_list = $Menu->getList(); // 获取分类结构


        $this->assign('menu', $menu_list);
        $this->assign('action', $action);
        $this->assign('action_url', $action_url);
        $this->assign('form_url', $form_url);

        $this->display();

    }

    public function menuAddHandle()
    {
        $data = $_POST;
        $Menu = D('Menu');
        $result = $Menu->data($data)->add();
        if ($result) $this->success('添加成功', 'Admin/Custom/menu');
    }

    public function menuEdit($id)
    {
        $action = '编辑菜单';
        $action_url = U('Admin/Custom/menuEdit', array('id' => $id));
        $form_url = U('Admin/Custom/menuEditHandle', array('id' => $id));

        $Menu = new Category ('Menu', array('menu_id', 'menu_pid', 'menu_name', 'menu_construct'));
        $menu_list = $Menu->getList(); // 获取分类结构

        $m = D('Menu')->where(array('menu_id' => $id))->find();
        $this->assign('info', $m);


        $this->assign('menu', $menu_list);
        $this->assign('action', $action);
        $this->assign('action_url', $action_url);
        $this->assign('form_url', $form_url);

        $this->display();

    }

    public function menuEditHandle($id)
    {
        $data = $_POST;
        $Menu = D('Menu');
        $result = $Menu->where(array('menu_id' => $id))->data($data)->save();
        if ($result) $this->success('编辑成功', 'Admin/Custom/menu');

    }


    private function themeStatus($theme_name = 'Vena')
    {
        $res = get_kv('theme_' . $theme_name, false);
        if ($res == null) {
            set_kv('theme_' . $theme_name, 'disabled');
            return 'disabled';
        }

        return $res;
    }


    public function theme()
    {
        $tpl_view = File::scanDir(WEB_ROOT . 'Application/Home/View');
        $tpl_static = File::scanDir(WEB_ROOT . 'Public');
        $tpl = array_intersect($tpl_view, $tpl_static);

        $theme_list = array();
        foreach ($tpl as $value) {
            $tpl_static_path = WEB_ROOT . 'Public/' . $value . '/';
            $theme_temp = array();
            if (file_exists($tpl_static_path . 'theme.xml')) {
                $theme = simplexml_load_file($tpl_static_path . '/theme.xml');

                $theme_temp = (array)$theme;
                if ($theme_temp['name'] == get_kv('home_theme')) {
                    $theme_temp['status_name'] = '正在使用';
                    $theme_temp['status_url'] = '#';
                    $theme_temp['using_color'] = 'green';
                    $theme_temp['action_name2'] = '使用中';
                    $theme_temp['action_url2'] = '#';
                } elseif ($this->themeStatus($theme_temp['name']) == 'enabled') {

                    $theme_temp['status_name'] = '立即使用';
                    $theme_temp['status_url'] = U('Admin/Custom/themeChangeHandle', array('theme_name' => $theme_temp['name']));

                    $theme_temp['action_name2'] = '禁用';
                    $theme_temp['action_url2'] = U('Admin/Custom/themeDisableHandle', array('theme_name' => $theme_temp['name']));

                } else {
                    $theme_temp['status_name'] = '禁用中';
                    $theme_temp['status_url'] = '#';

                    $theme_temp['action_name2'] = '启用';
                    $theme_temp['action_url2'] = U('Admin/Custom/themeEnableHandle', array('theme_name' => $theme_temp['name']));

                }

                /**
                 *     <a class="btn " href="{:U('Admin/Custom/themeEnableHandle',array('theme_name'=>$vo['name']))}">启用</a>
                <a class="btn purple" href="{:U('Admin/Custom/themeDisableHandle',array('theme_name'=>$vo['name']))}">禁用</a>

                 */
                array_push($theme_list, $theme_temp);
            }

        }
        $this->assign('theme_list', $theme_list);

        $this->display();
    }


    public function themeAdd()
    {
        $this->display();
    }

    //todo 需要检查是否真的成功
    public function themeDisableHandle($theme_name = 'Vena')
    {
        if (get_kv('home_theme') == $theme_name) $this->error('正在使用的主题不可以禁用');
        set_kv('theme_' . $theme_name, 'disabled');
        $this->success('禁用成功', 'Admin/Custom/theme');
    }

    public function themeEnableHandle($theme_name = 'Vena')
    {

        set_kv('theme_' . $theme_name, 'enabled');
        $this->success('启用成功', 'Admin/Custom/theme');
    }



    public function themeChangeHandle($theme_name = 'Vena')
    {
        if (get_kv('home_theme') == $theme_name) $this->error('无需切换');

        if ($this->themeStatus($theme_name) == 'disabled') {
            $this->error('请先启用主题');
        }


        $res = set_kv('home_theme', $theme_name);
        if ($res) {
            $cache_control=new \Common\Event\SystemEvent();
            $cache_control->clearCacheAll();
            $this->success('切换成功');
        } else {
            $this->error('切换失败');
        }
    }

    public function themeDelHandle($theme_name = '')
    {
        if ($this->themeStatus($theme_name) == 'enabled') {
            $this->error('请先禁用主题');
        }

        $tpl_view_path = WEB_ROOT . 'Application/Home/View/' . $theme_name . '/';
        $tpl_static_path = WEB_ROOT . 'Public/' . $theme_name . '/';
        File::delAll($tpl_view_path, true);
        File::delAll($tpl_static_path, true);
        $this->success('删除成功');
    }


    public function plugin()
    {
        $Addons = M('Addons');

        $list = D('Addons')->getList();
        $count = count($list);
        $fenye = 20;
        $p = new GreenPage ($count, 10);
        $list = $Addons->order('create_time')->limit($p->firstRow . ',' . $p->listRows)->select(); //->where($where)

        $this->assign('page', $p->show());
        $this->assign('list', $list);
        $this->display();

    }

    //创建向导首页
    public function create()
    {
        if (!is_writable(GREENCMS_ADDON_PATH))
            $this->error('您没有创建目录写入权限，无法使用此功能');

        $hooks = M('Hooks')->field('name,description')->select();
        $this->assign('Hooks', $hooks);
        $this->meta_title = '创建向导';
        $this->display('create');
    }

    //预览
    public function preview($output = true)
    {
        $data = $_POST;
        $data['info']['status'] = (int)$data['info']['status'];
        $extend = array();
        $custom_config = trim($data['custom_config']);
        if ($data['has_config'] && $custom_config) {
            $custom_config = <<<str


        public \$custom_config = '{$custom_config}';
str;
            $extend[] = $custom_config;
        }

        $admin_list = trim($data['admin_list']);
        if ($data['has_adminlist'] && $admin_list) {
            $admin_list = <<<str


        public \$admin_list = array(
            {$admin_list}
        );
str;
            $extend[] = $admin_list;
        }

        $custom_adminlist = trim($data['custom_adminlist']);
        if ($data['has_adminlist'] && $custom_adminlist) {
            $custom_adminlist = <<<str


        public \$custom_adminlist = '{$custom_adminlist}';
str;
            $extend[] = $custom_adminlist;
        }

        $extend = implode('', $extend);
        $hook = '';
        foreach ($data['hook'] as $value) {
            $hook .= <<<str
        //实现的{$value}钩子方法
        public function {$value}(\$param){

    }

str;
        }

        $tpl = <<<str
<?php

namespace Addons\\{$data['info']['name']};
use Common\Controller\Addon;

/**
 * {$data['info']['title']}插件
 * @author {$data['info']['author']}
 */

    class {$data['info']['name']}Addon extends Addon{

        public \$info = array(
            'name'=>'{$data['info']['name']}',
            'title'=>'{$data['info']['title']}',
            'description'=>'{$data['info']['description']}',
            'status'=>{$data['info']['status']},
            'author'=>'{$data['info']['author']}',
            'version'=>'{$data['info']['version']}'
        );{$extend}

        public function install(){
            return true;
    }

        public function uninstall(){
            return true;
    }

{$hook}
    }
str;
        if ($output)
            exit($tpl);
        else
            return $tpl;
    }

    public function checkForm()
    {
        $data = $_POST;
        $data['info']['name'] = trim($data['info']['name']);
        if (!$data['info']['name'])
            $this->error('插件标识必须');
        //检测插件名是否合法
        $addons_dir = ONETHINK_ADDON_PATH;
        if (file_exists("{$addons_dir}{$data['info']['name']}")) {
            $this->error('插件已经存在了');
        }
        $this->success('可以创建');
    }

    public function build()
    {
        $data = $_POST;
        $data['info']['name'] = trim($data['info']['name']);
        $addonFile = $this->preview(false);
        $addons_dir = ONETHINK_ADDON_PATH;
        //创建目录结构
        $files = array();
        $addon_dir = "$addons_dir{$data['info']['name']}/";
        $files[] = $addon_dir;
        $addon_name = "{$data['info']['name']}Addon.class.php";
        $files[] = "{$addon_dir}{$addon_name}";
        if ($data['has_config'] == 1) ; //如果有配置文件
        $files[] = $addon_dir . 'config.php';

        if ($data['has_outurl']) {
            $files[] = "{$addon_dir}Controller/";
            $files[] = "{$addon_dir}Controller/{$data['info']['name']}Controller.class.php";
            $files[] = "{$addon_dir}Model/";
            $files[] = "{$addon_dir}Model/{$data['info']['name']}Model.class.php";
        }
        $custom_config = trim($data['custom_config']);
        if ($custom_config)
            $data[] = "{$addon_dir}{$custom_config}";

        $custom_adminlist = trim($data['custom_adminlist']);
        if ($custom_adminlist)
            $data[] = "{$addon_dir}{$custom_adminlist}";

        create_dir_or_files($files);

        //写文件
        file_put_contents("{$addon_dir}{$addon_name}", $addonFile);
        if ($data['has_outurl']) {
            $addonController = <<<str
<?php

namespace Addons\\{$data['info']['name']}\Controller;
use Home\Controller\AddonsController;

class {$data['info']['name']}Controller extends AddonsController{

        }

str;
            file_put_contents("{$addon_dir}Controller/{$data['info']['name']}Controller.class.php", $addonController);
            $addonModel = <<<str
<?php

namespace Addons\\{$data['info']['name']}\Model;
use Think\Model;

/**
 * {$data['info']['name']}模型
 */
class {$data['info']['name']}Model extends Model{

    }

str;
            file_put_contents("{$addon_dir}Model/{$data['info']['name']}Model.class.php", $addonModel);
        }

        if ($data['has_config'] == 1)
            file_put_contents("{$addon_dir}config.php", $data['config']);

        $this->success('创建成功', U('index'));
    }

    /**
     * 插件后台显示页面
     * @param string $name 插件名
     */
    public function adminList($name)
    {
        $class = get_addon_class($name);
        if (!class_exists($class))
            $this->error('插件不存在');
        $addon = new $class();
        $this->assign('addon', $addon);
        $param = $addon->admin_list;
        if (!$param)
            $this->error('插件列表信息不正确');
        $this->meta_title = $addon->info['title'];
        extract($param);
        $this->assign('title', $addon->info['title']);
        if ($addon->custom_adminlist)
            $this->assign('custom_adminlist', $this->fetch($addon->addon_path . $addon->custom_adminlist));
        $this->assign($param);
        if (!isset($fields))
            $fields = '*';
        if (!isset($map))
            $map = array();
        if (isset($model))
            $list = $this->lists(D("Addons://{$model}/{$model}")->field($fields), $map);
        $this->assign('_list', $list);
        $this->display();
    }

    /**
     * 启用插件
     */
    public function enable()
    {
        $id = I('id');
        M('Addons')->where(array('id' => $id))->setField('status', 1);
        S('hooks', null);
        $this->json_return(1, "启用成功", U('Admin/Custom/plugin'));
    }

    /**
     * 禁用插件
     */
    public function disable()
    {
        $id = I('id');
        M('Addons')->where(array('id' => $id))->setField('status', 0);
        S('hooks', null);
        $this->json_return(1, "禁用成功", U('Admin/Custom/plugin'));
    }

    /**
     * 设置插件页面
     */
    public function config(){
        $id     =   (int)I('id');
        $addon  =   M('Addons')->find($id);
        if(!$addon)
            $this->error('插件未安装');
        $addon_class = get_addon_class($addon['name']);
        if(!class_exists($addon_class))
            trace("插件{$addon['name']}无法实例化,",'ADDONS','ERR');
        $data  =   new $addon_class;
        $addon['addon_path'] = $data->addon_path;
        $addon['custom_config'] = $data->custom_config;
        $this->meta_title   =   '设置插件-'.$data->info['title'];
        $db_config = $addon['config'];
        $addon['config'] = include $data->config_file;
        if($db_config){
            $db_config = json_decode($db_config, true);
            foreach ($addon['config'] as $key => $value) {
                if($value['type'] != 'group'){
                    $addon['config'][$key]['value'] = $db_config[$key];
                }else{
                    foreach ($value['options'] as $gourp => $options) {
                        foreach ($options['options'] as $gkey => $value) {
                            $addon['config'][$key]['options'][$gourp]['options'][$gkey]['value'] = $db_config[$gkey];
                        }
                    }
                }
            }
        }
        $this->assign('data',$addon);
//        dump($addon);
        if($addon['custom_config'])
            $this->assign('custom_config', $this->fetch($addon['addon_path'].$addon['custom_config']));
        $this->assign('action','插件配置');
        $this->display();
    }


    /**
     * 保存插件设置
     */
    public function saveConfig()
    {
        $id = (int)I('id');
        $config = I('config');
        $flag = M('Addons')->where("id={$id}")->setField('config', json_encode($config));
        if ($flag !== false) {
            $this->success('保存成功', Cookie('__forward__'));
        } else {
            $this->error('保存失败');
        }
    }

    /**
     * 安装插件
     */
    public function install()
    {
        $addon_name = trim(I('addon_name'));
        $class = get_addon_class($addon_name);
        if (!class_exists($class))
            $this->error('插件不存在', U('Admin/Custom/plugin'));
        $addons = new $class;
        $info = $addons->info;
        if (!$info || !$addons->checkInfo()) //检测信息的正确性
        $this->error('插件信息缺失');
        session('addons_install_error', null);
        $install_flag = $addons->install();
        if (!$install_flag) {
            $this->error('执行插件预安装操作失败' . session('addons_install_error'), U('Admin/Custom/plugin'));
        }
        $addonsModel = D('Addons');
        $data = $addonsModel->create($info);
        if (is_array($addons->admin_list) && $addons->admin_list !== array()) {
            $data['has_adminlist'] = 1;
        } else {
            $data['has_adminlist'] = 0;
        }
        if (!$data)
            $this->error($addonsModel->getError());
        if ($addonsModel->add($data)) {
            $config = array('config' => json_encode($addons->getConfig()));
            $addonsModel->where("name='{$addon_name}'")->save($config);
            $hooks_update = D('Hooks')->updateHooks($addon_name);
            if ($hooks_update) {
                S('hooks', null);
                $this->success('安装成功', U('Admin/Custom/plugin'));
            } else {
                $addonsModel->where("name='{$addon_name}'")->delete();
                $this->error('更新钩子处插件失败,请卸载后尝试重新安装', U('Admin/Custom/plugin'));
            }

        } else {
            $this->error('写入插件数据失败', U('Admin/Custom/plugin'));
        }
    }

    /**
     * 卸载插件
     */
    public function uninstall()
    {
        $addonsModel = M('Addons');
        $id = trim(I('id'));
        $db_addons = $addonsModel->find($id);
        $class = get_addon_class($db_addons['name']);
        $this->assign('jumpUrl', U('index'));
        if (!$db_addons || !class_exists($class))
            $this->error('插件不存在', U('Admin/Custom/plugin'));
        session('addons_uninstall_error', null);
        $addons = new $class;
        $uninstall_flag = $addons->uninstall();
        if (!$uninstall_flag)
            $this->error('执行插件预卸载操作失败' . session('addons_uninstall_error'), U('Admin/Custom/plugin'));
        $hooks_update = D('Hooks')->removeHooks($db_addons['name']);
        if ($hooks_update === false) {
            $this->error('卸载插件所挂载的钩子数据失败', U('Admin/Custom/plugin'));
        }
        S('hooks', null);
        $delete = $addonsModel->where("name='{$db_addons['name']}'")->delete();
        if ($delete === false) {
            $this->error('卸载插件失败', U('Admin/Custom/plugin'));
        } else {
            $this->success('卸载成功', U('Admin/Custom/plugin'));
        }
    }

    /**
     * 钩子列表
     */
    public function hooks()
    {
        $this->meta_title = '钩子列表';
        $map = $fields = array();
        $list = $this->lists(D("Hooks")->field($fields), $map);
        int_to_string($list, array('type' => C('HOOKS_TYPE')));
        // 记录当前列表页的cookie
        Cookie('__forward__', $_SERVER['REQUEST_URI']);
        $this->assign('list', $list);
        $this->display();
    }

    public function addhook()
    {
        $this->assign('data', null);
        $this->meta_title = '新增钩子';
        $this->display('edithook');
    }

    //钩子出编辑挂载插件页面
    public function edithook($id)
    {
        $hook = M('Hooks')->field(true)->find($id);
        $this->assign('data', $hook);
        $this->meta_title = '编辑钩子';
        $this->display('edithook');
    }

    //超级管理员删除钩子
    public function delhook($id)
    {
        if (M('Hooks')->delete($id) !== false) {
            $this->success('删除成功');
        } else {
            $this->error('删除失败');
        }
    }

    public function updateHook()
    {
        $hookModel = D('Hooks');
        $data = $hookModel->create();
        if ($data) {
            if ($data['id']) {
                $flag = $hookModel->save($data);
                if ($flag !== false)
                    $this->success('更新成功', Cookie('__forward__'));
                else
                    $this->error('更新失败');
            } else {
                $flag = $hookModel->add($data);
                if ($flag)
                    $this->success('新增成功', Cookie('__forward__'));
                else
                    $this->error('新增失败');
            }
        } else {
            $this->error($hookModel->getError());
        }
    }

    public function execute($_addons = null, $_controller = null, $_action = null)
    {
        if (C('URL_CASE_INSENSITIVE')) {
            $_addons = ucfirst(parse_name($_addons, 1));
            $_controller = parse_name($_controller, 1);
        }

        if (!empty($_addons) && !empty($_controller) && !empty($_action)) {
            $Addons = A("Addons://{$_addons}/{$_controller}")->$_action();
        } else {
            $this->error('没有指定插件名称，控制器或操作！');
        }
    }


}