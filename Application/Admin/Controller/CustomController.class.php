<?php
/**
 * Created by GreenStudio GCS Dev Team.
 * File: CustomController.class.php
 * User: Timothy Zhang
 * Date: 14-1-26
 * Time: 下午5:26
 */

namespace Admin\Controller;

use Admin\Model\AddonsModel;
use Common\Event\SystemEvent;
use Common\Event\ThemeEvent;
use Common\Event\UpdateEvent;
use Common\Logic\CatsLogic;
use Common\Logic\PostsLogic;
use Common\Logic\TagsLogic;
use Common\Util\CacheManager;
use Common\Util\Category;
use Common\Util\File;
use Common\Util\GreenPage;

use Common\Util\PHPZip;
use Think\Upload;

/**
 * 用户自定义模块
 * Class CustomController
 * @package Admin\Controller
 */
class CustomController extends AdminBaseController
{

    public function __construct()
    {

        parent::__construct();

        CacheManager::clearLink();
        CacheManager::clearMenu();


    }


    /**
     *
     */
    public function index()
    {
        $this->theme();
    }

    //TODO menu

    public function theme()
    {

        $ThemeEvent = new ThemeEvent();
        $theme_exist = $ThemeEvent->getThemeNameList();
        $theme_not_installed = $ThemeEvent->getThemeNotInstalledNameList();
        $theme_installed = $ThemeEvent->getThemeInstalledNameList();


        $theme_list_installed = $ThemeEvent->getThemeInstalledList();
        foreach ($theme_list_installed as $key => $theme_list_installed_value) {
            if ($theme_list_installed_value['theme_name'] == get_kv('home_theme', true)) {
                $theme_list_installed[$key]['using_color'] = ' bg-green';
                $theme_list_installed[$key]['status_name'] = '正在使用';
                $theme_list_installed[$key]['status_url'] = "#";

            } else {
                $theme_list_installed[$key]['using_color'] = ' btn-warning';
                $theme_list_installed[$key]['status_name'] = '准备就绪';
                $theme_list_installed[$key]['status_url'] = U('Admin/Custom/themeChangeHandle',
                    array('theme_name' => $theme_list_installed_value['theme_name']));

            }
        }
        $this->assign('theme_list_installed', $theme_list_installed);


        $theme_list = array();
        foreach ($theme_not_installed as $value) {
            $tpl_static_path = WEB_ROOT . 'Public/' . $value . '/';
            $theme_temp = array();
            if (file_exists($tpl_static_path . 'theme.xml')) {
                $theme = simplexml_load_file($tpl_static_path . '/theme.xml');

                $theme_temp = (array)$theme;
                if ($theme_temp['name'] == get_kv('home_theme', true)) {
                    $theme_temp['using_color'] = ' bg-green';
                    $theme_temp['status_name'] = '正在使用';

                } else {
                    $theme_temp['using_color'] = ' btn-warning';
                    $theme_temp['status_name'] = '待安装';

                }

                array_push($theme_list, $theme_temp);
            }

        }
        $this->assign('theme_list_not_installed', $theme_list);


        $this->display();


    }

    /**
     * 首页菜单显示
     */
    public function menu()
    {
        $Menu = new Category ('Menu', array('menu_id', 'menu_pid', 'menu_name', 'menu_construct'));

        $menu_list = $Menu->getList(null, 0, 'menu_sort desc'); // 获取分类结构
        $this->assign('menu', $menu_list);

        $this->display();
    }

    //todo
    public function menuInc($id)
    {
        $menu_item = D('Menu')->where(array('menu_id' => $id))->find();
        if (!$menu_item) {
            $this->error('不存在这个菜单项');
        }


        $map['menu_sort'] = array('GT', $menu_item['menu_sort']);
        $menu_item_target = D('Menu')->where($map)->order("menu_sort")->find();
        duMP($menu_item_target);


        $map['menu_sort'] = array('GT', $menu_item_target['menu_sort']);


        $Menu = D('Menu');
        $res = $Menu->where($map)->setInc('menu_sort');


        $menu_item['menu_sort'] = $menu_item_target['menu_sort'] + 1;

        $res = D('Menu')->data($menu_item)->save();


        dump($res);
    }

    //todo
    public function menuDec($id)
    {
        $menu_item = D('Menu')->where(array('menu_id' => $id))->find();
        if (!$menu_item) {
            $this->error('不存在这个菜单项');
        }


    }

    /**
     * 菜单删除
     * @param $id
     * @param bool $child
     */
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


        $this->success('删除成功', U('Admin/Custom/menu'));

    }

    /**
     * 添加菜单
     */
    public function menuAdd()
    {
        $CatsLogic = new CatsLogic();
        $TagsLogic = new TagsLogic();
        $PostsLogic = new PostsLogic();

        $cat_list = $CatsLogic->category();
        $tag_list = $TagsLogic->field('tag_id,tag_name')->select();
        $post_list = $PostsLogic->field('post_id,post_title')->select();

        $cat_list = array_column_5($cat_list, 'cat_slug', 'cat_id');
        $tag_list = array_column_5($tag_list, 'tag_name', 'tag_id');
        $post_list = array_column_5($post_list, 'post_title', 'post_id');


        $action = '添加菜单';
        $action_url = U('Admin/Custom/menuAdd');
        $form_url = U('Admin/Custom/menuAddHandle');

        $Menu = new Category ('Menu', array('menu_id', 'menu_pid', 'menu_name', 'menu_construct'));
        $menu_list = $Menu->getList(); // 获取分类结构


        $url_function = get_opinion('url_function');
        $this->assign('url_function', gen_opinion_list($url_function));

        $url_open = get_opinion('url_open');
        $this->assign('url_open', gen_opinion_list($url_open));


        $this->assign('cat_list', gen_opinion_list($cat_list));
        $this->assign('tag_list', gen_opinion_list($tag_list));
        $this->assign('post_list', gen_opinion_list($post_list));


        $this->assign('menu', $menu_list);
        $this->assign('action', $action);
        $this->assign('action_url', $action_url);
        $this->assign('form_url', $form_url);

        $this->display();

    }

    /**
     * 添加菜单处理
     */
    public function menuAddHandle()
    {


        $post_data = I('post.');

        $map['menu_sort'] = array('EGT', $post_data['menu_sort']);

        $Menu = D('Menu');
        $res = $Menu->where($map)->setInc('menu_sort');

        $result = $Menu->data($post_data)->add();
        if ($result) {
            $this->success('添加成功', U('Admin/Custom/menu'));
        } else {
            $this->error('添加失败');
        }
    }

    /**
     * 编辑菜单
     * @param $id
     */
    public function menuEdit($id)
    {

        $menu_item = D('Menu')->where(array('menu_id' => $id))->find();
        if (!$menu_item) {
            $this->error('不存在这个菜单项');
        }

        $this->assign('info', $menu_item);


        $CatsLogic = new CatsLogic();
        $TagsLogic = new TagsLogic();
        $PostsLogic = new PostsLogic();

        /**
         *  文章分类标签 start
         */
        $cat_list = $CatsLogic->category();
        $tag_list = $TagsLogic->field('tag_id,tag_name')->select();
        $post_list = $PostsLogic->field('post_id,post_title')->select();
        $cat_list = array_column_5($cat_list, 'cat_slug', 'cat_id');
        $tag_list = array_column_5($tag_list, 'tag_name', 'tag_id');
        $post_list = array_column_5($post_list, 'post_title', 'post_id');
        $this->assign('cat_list', gen_opinion_list($cat_list));
        $this->assign('tag_list', gen_opinion_list($tag_list));
        $this->assign('post_list', gen_opinion_list($post_list));
        /**
         *  文章分类标签 end
         */

        $action = '编辑菜单';
        $action_url = U('Admin/Custom/menuEdit', array('id' => $id));
        $form_url = U('Admin/Custom/menuEditHandle', array('id' => $id));

        $Menu = new Category ('Menu', array('menu_id', 'menu_pid', 'menu_name', 'menu_construct'));
        $menu_list = $Menu->getList(); // 获取分类结构

        $url_function = get_opinion('url_function');
        $this->assign('url_function', gen_opinion_list($url_function, $menu_item["menu_function"]));

        $url_open = get_opinion('url_open');
        $this->assign('url_open', gen_opinion_list($url_open, $menu_item["menu_action"]));

        //父级节点
        $menu_list2 = array_column_5($menu_list, 'menu_construct', 'menu_id');
        $this->assign('menu_list2', gen_opinion_list($menu_list2, $menu_item['menu_pid']));

        //显示排序
        $menu_list3 = array_column_5($menu_list, 'menu_construct', 'menu_sort');
        $this->assign('menu_list3', gen_opinion_list($menu_list3, $menu_item['menu_sort']));


        $this->assign('menu', $menu_list);
        $this->assign('action', $action);
        $this->assign('action_url', $action_url);
        $this->assign('form_url', $form_url);

        $this->display();

    }

    /**
     * 菜单编辑处理
     * @param $id
     */
    public function menuEditHandle($id)
    {

        $post_data = I('post.');

        $map['menu_sort'] = array('EGT', $post_data['menu_sort']);

        $Menu = D('Menu');
        $res = $Menu->where($map)->setInc('menu_sort');


        if ($post_data['menu_pid'] == $post_data['menu_id']) {
            $this->error('父类不能是自己');
        }
        $Menu = D('Menu');
        $result = $Menu->where(array('menu_id' => $id))->data($post_data)->save();
        if ($result) {
            $this->success('编辑成功', U('Admin/Custom/menu'));
        } else {
            $this->error('编辑失败');

        }
    }

    /**
     * 插件页面
     */
    public function plugin()
    {
        //$page = I('get.page', get_opinion('PAGER'));

        $Addons = new AddonsModel();

        $list = $Addons->getList(); //这里得到是未安装的
//        $count = count($list);


        // $p = new GreenPage ($count, $page);
        //这里得到是已安装的  =_=+++++


        //$this->assign('page', $p->show());
        $this->assign('list', $list);
        $this->display();

    }


    public function pluginAdd()
    {

        $this->assign('action', '插件添加');
        $this->assign('action_name', 'pluginAdd');


        $this->display("pluginadd");
    }


    public function pluginAddLocal()
    {

        File::mkDir(WEB_CACHE_PATH);


        $config = array(
            'rootPath' => WEB_CACHE_PATH,
            "savePath" => '',
            "maxSize" => 100000000, // 单位B
            "exts" => array('zip'),
            "subName" => array(),
        );

        $upload = new Upload($config);
        $info = $upload->upload();
        if (!$info) { // 上传错误提示错误信息
            $this->error($upload->getError());
        } else { // 上传成功 获取上传文件信息

            $file_path_full = $info['file']['fullpath'];

            //dump($info);die($file_path_full);
            if (File::file_exists($file_path_full)) {

                $Update = new UpdateEvent();
                $applyRes = $Update->applyPatch($file_path_full);
                $applyInfo = json_decode($applyRes, true);

                if ($applyInfo['status']) {
                    $this->success($applyInfo['info'], U('Admin/Custom/plugin'));
                } else {
                    $this->error($applyInfo['info']);
                }

            } else {
                $this->error('文件不存在');

            }
        }


    }


    public function pluginDelHandle($plugin_name = '')
    {

        $plugin_path = Addon_PATH . $plugin_name . '/';

        if (File::delAll($plugin_path, true)) {
            $this->success('删除成功');
        } else {
            $this->error("删除失败");
        }

    }


    /**
     * 创建向导首页
     */
    public function create()
    {
        if (!is_writable(Addon_PATH))
            $this->error('您没有创建目录写入权限，无法使用此功能');

        $hooks = M('Hooks')->field('name,description')->select();
        $this->assign('Hooks', $hooks);
        $this->meta_title = '创建向导';
        $this->model = '插件管理';
        $this->action = '创建插件';
        $this->display();
    }

    /**
     * 检查form
     */
    public function checkForm()
    {
        $data = $_POST;
        $data['info']['name'] = trim($data['info']['name']);
        if (!$data['info']['name'])
            $this->error('插件标识必须');
        //检测插件名是否合法
        $addons_dir = Addon_PATH;
        if (file_exists("{$addons_dir}{$data['info']['name']}")) {
            $this->error('插件已存在');
        }
        $this->success('可以创建');
    }

    /**
     * 创建插件
     */
    public function build()
    {
        $data = $_POST;
        $data['info']['name'] = trim($data['info']['name']);
        $addonFile = $this->preview(false);
        $addons_dir = Addon_PATH;
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

        $this->success('创建成功', U('Admin/Custom/plugin'));
    }

    /**
     * 插件预览
     * @param bool $output
     * @return string
     */
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

    /**
     *
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
            $this->assign('custom_adminlist', $this->fetch($addon->Addon_PATH . $addon->custom_adminlist));


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
    public function pluginEnable()
    {
        $id = I('post.id');
        D('Addons')->where(array('id' => $id))->setField('status', 1);
        S('hooks', null);
        $this->jsonReturn(1, "启用成功", U('Admin/Custom/plugin'));

    }

    /**
     * 禁用插件
     */
    public function pluginDisable()
    {
        $id = I('post.id');
        D('Addons')->where(array('id' => $id))->setField('status', 0);
        S('hooks', null);
        $this->jsonReturn(1, "禁用成功", U('Admin/Custom/plugin'));
    }

    /**
     * 设置插件页面
     */
    public function config()
    {
        $this->assign('action', '插件配置');


        $id = (int)I('id');
        $addon = M('Addons')->find($id);
        if (!$addon) $this->error('插件未安装');
        $addon_class = get_addon_class($addon['name']);
        if (!class_exists($addon_class))
            trace("插件{$addon['name']}无法实例化,", 'ADDONS', 'ERR');
        $data = new $addon_class;
        $addon['Addon_PATH'] = $data->Addon_PATH;
        $addon['custom_config'] = $data->custom_config;
        $this->meta_title = '设置插件-' . $data->info['title'];
        $db_config = $addon['config'];
        $addon['config'] = include $data->config_file;
        if ($db_config) {
            $db_config = json_decode($db_config, true);
            foreach ($addon['config'] as $key => $value) {
                if ($value['type'] != 'group') {
                    $addon['config'][$key]['value'] = $db_config[$key];
                } else {
                    foreach ($value['options'] as $gourp => $options) {
                        foreach ($options['options'] as $gkey => $value) {
                            $addon['config'][$key]['options'][$gourp]['options'][$gkey]['value'] = $db_config[$gkey];
                        }
                    }
                }
            }
        }
        $this->assign('data', $addon);
//        dump($addon);


        $addons_dir = Addon_PATH;

        $file = $addons_dir . $addon['name'] . '/' . $data->custom_config;

        if ($addon['custom_config']) {
            $custom_configs = $this->fetch($file);
            $this->assign('custom_configs', $custom_configs);
            $this->display('custom_config');

        } else {
            $this->display();

        }
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
            $this->success('保存成功', U("Admin/Custom/plugin"));
        } else {
            $this->error('保存失败');
        }
    }

    /**
     * 安装插件
     */
    public function install($addon_name)
    {
//        $addon_name = trim(I('addon_name'));
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
        $Addons = D('Addons');
        $data = $Addons->create($info);
        if (is_array($addons->admin_list) && $addons->admin_list !== array()) {
            $data['has_adminlist'] = 1;
        } else {
            $data['has_adminlist'] = 0;
        }
        if (!$data)
            $this->error($Addons->getError());
        if ($Addons->add($data)) {
            $config = array('config' => json_encode($addons->getConfig()));
            $Addons->where("name='{$addon_name}'")->save($config);
            $hooks_update = D('Hooks')->updateHooks($addon_name);
            if ($hooks_update) {
                S('hooks', null);
                $this->success('安装成功', U('Admin/Custom/plugin'));
            } else {
                $Addons->where("name='{$addon_name}'")->delete();
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


        Cookie('__forward__', $_SERVER['REQUEST_URI']);

        $count = D("Hooks")->count();
        if ($count != 0) {
            $page = I('get.page', get_opinion('PAGER'));
            $Page = new GreenPage($count, $page); // 实例化分页类 传入总记录数
            $pager_bar = $Page->show();
            $limit = $Page->firstRow . ',' . $Page->listRows;
            $list = D("Hooks")->limit($limit)->field($fields)->select();
        }


        $this->assign('page', $pager_bar);
        $this->assign('list', $list);
        $this->display();
    }

    /**
     * 添加钩子
     */
    public function addhook()
    {
        $this->assign('info', null);
        $this->action = '添加钩子';
        $this->display('edithook');
    }

    /**
     * 钩子出编辑挂载插件页面
     * @param $id
     */
    public function edithook($id)
    {
        $hook = M('Hooks')->field(true)->find($id);
        $this->assign('info', $hook);
        $this->action = '编辑钩子';
        $this->display('edithook');
    }

    /**
     * 删除钩子
     * @param $id
     */
    public function delhook($id)
    {
        if (M('Hooks')->delete($id) !== false) {
            $this->success('删除成功');
        } else {
            $this->error('删除失败');
        }
    }

    /**
     * 更新钩子
     */
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

    /**
     * 执行
     * @param null $_addons
     * @param null $_controller
     * @param null $_action
     */
    public function execute($_addons = null, $_controller = null, $_action = null)
    {
        if (get_opinion('URL_CASE_INSENSITIVE')) {
            $_addons = ucfirst(parse_name($_addons, 1));
            $_controller = parse_name($_controller, 1);
        }

        if (!empty($_addons) && !empty($_controller) && !empty($_action)) {
            $Addons = A("Addons://{$_addons}/{$_controller}")->$_action();
        } else {
            $this->error('没有指定插件名称，控制器或操作！');
        }
    }

    /**
     * link 分组
     */
    public function linkgroup()
    {

        $link_group_list = D('Link_group', 'Logic')->select();

        $this->assign('link_group_list', $link_group_list);
        $this->display();
    }

    /**
     * 添加友情链接分组
     */
    public function addlinkgroup()
    {
        $this->assign('action', '添加链接分组');
        $this->assign('buttom', '添加');

        $this->assign('form_url', U('Admin/Custom/addlinkgroupHandle'));
        $this->display();
    }

    /**
     * 添加友情链接分组处理
     */
    public function addlinkgroupHandle()
    {
        $data['link_group_name'] = I('post.link_group_name');
        if (D('Link_group', 'Logic')->data($data)->add()) {
            $this->success('链接分组添加成功', U('Admin/Custom/linkgroup'));
        } else {
            $this->error('链接分组添加失败', U('Admin/Custom/linkgroup'));
        }
    }

    /**
     * 删除链接分组处理
     * @param $id
     */
    public function dellinkgroupHandle($group_id)
    {

        if (D('Link_group', 'Logic')->delete($group_id)) {
            $this->success('链接删除成功');
        } else {
            $this->error('链接删除失败');
        }
    }

    /**
     * 编辑链接分组
     * @param $id
     */
    public function editlinkgroup($group_id)
    {
        $this->assign('form_url', U('Admin/Custom/editlinkgroupHandle', array('group_id' => $group_id)));

        $link_group = D('Link_group', 'Logic')->where(array('link_group_id' => $group_id))->find();
        $this->assign('link_group', $link_group);


        $this->assign('action', '编辑链接分组');
        $this->assign('buttom', '辑加');

        $this->display('addlinkgroup');
    }

    /**
     * 编辑链接分组处理
     * @param $id
     */
    public function editlinkgroupHandle($group_id)
    {
        $data['link_group_name'] = I('post.link_group_name');


        if (D('Link_group', 'Logic')->where(array('link_group_id' => $group_id))->data($data)->save()) {
            $this->success('链接分组编辑成功', U('Admin/Custom/linkgroup'));
        } else {
            $this->error('链接分组编辑失败', U('Admin/Custom/linkgroup'));
        }

    }

    /**
     * 链接管理
     */
    public function links($group_id = 0)
    {

        $link_group = D('Link_group', 'Logic')->find($group_id);
        $this->assign('action', '链接管理:' . $link_group['link_group_name']);

        $linklist = D('Links', 'Logic')->getList(1000, $group_id);
        $this->assign('linklist', $linklist);

        $this->display();
    }

    /**
     * 添加链接
     */
    public function addlink()
    {

        if (IS_POST) {
            $data = I('post.');
            if ($_FILES['img']['size'] != 0) {

                $config = array(
                    "savePath" => 'Links/',
                    "maxSize" => 1000000, // 单位B
                    "exts" => array('jpg', 'bmp', 'png', 'jpeg'),
                    "subName" => array('date', 'Y/m-d'),
                );
                $upload = new Upload($config);
                $info = $upload->upload();

                if (!$info) { // 上传错误提示错误信息
                    $this->error($upload->getError());
                } else { // 上传成功 获取上传文件信息


                    $file_path_full = $info['img']['fullpath'];

                    $image = new \Think\Image();
                    $image->open($file_path_full);
                    $image->thumb(200, 150)->save($file_path_full);

                    $img_url = $info['img']['urlpath'];

                    //  $img_url = "http://" . $_SERVER['SERVER_NAME'] . str_replace('index.php', '', __APP__) . $file_path_full;
                    unset($data['img']);
                    $data['link_img'] = $img_url;

                };

            }

            if (D('Links', 'Logic')->addLink($data)) {
                $this->success('链接添加成功', U('Admin/Custom/links', array('id' => $data['link_group_id'])));
            } else {
                $this->error('链接添加失败');
            }
        } else {
            $this->assign('imgurl', __ROOT__ . '/Public/share/img/no+image.gif');

            $link_groups = D('Link_group', 'Logic')->select();
            $link_group_select = array_column_5($link_groups, 'link_group_name', 'link_group_id');
            $this->assign('link_group', gen_opinion_list($link_group_select));


            $this->assign('form_url', U('Admin/Custom/addlink'));
            $this->assign('action', '添加链接');
            $this->assign('buttom', '添加');

            $this->display('addlink');
        }
    }

    /**
     * 编辑链接
     * @param $id
     */
    public function editlink($id)
    {
        if (IS_POST) {
            $data = I('post.');

            if ($_FILES['img']['size'] != 0) {

                $config = array(
                    "savePath" => 'Links/',
                    "maxSize" => 1000000, // 单位B
                    "exts" => array('jpg', 'bmp', 'png', 'jpeg'),
                    "subName" => array('date', 'Y/m-d'),
                );
                $upload = new Upload($config);
                $info = $upload->upload();

                if (!$info) { // 上传错误提示错误信息
                    $this->error($upload->getError());
                } else { // 上传成功 获取上传文件信息

                    $file_path_full = $info['img']['fullpath'];

                    $image = new \Think\Image();
                    $image->open($file_path_full);
                    $image->thumb(200, 150)->save($file_path_full);

                    $img_url = $info['img']['urlpath'];

                    // $img_url = "http://" . $_SERVER['SERVER_NAME'] . str_replace('index.php', '', __APP__) . $file_path_full;
                    unset($data['img']);
                    $data['link_img'] = $img_url;

                };

            }


            if (D('Links', 'Logic')->where(array('link_id' => $id))->save($data)
            ) {
                $this->success('链接编辑成功', U('Admin/Custom/links', array('id' => $data['link_group_id'])));
            } else {
                $this->error('链接编辑失败', U('Admin/Custom/links', array('id' => $data['link_group_id'])));
            }
        } else {

            $this->form_url = U('Admin/Custom/editlink', array('id' => $id));
            $link = D('Links', 'Logic')->detail($id);

            $link_groups = D('Link_group', 'Logic')->select();
            $link_group_select = array_column_5($link_groups, 'link_group_name', 'link_group_id');
            $this->assign('link_group', gen_opinion_list($link_group_select, $link['link_group_id']));


            $this->assign('imgurl', $link['link_img']);
            $this->assign('link', $link);
            // print_array($this->link);
            $this->action = '编辑链接';
            $this->buttom = '编辑';
            $this->display('addlink');
        }
    }

    /**
     * 删除链接
     * @param $id
     */
    public function dellink($id)
    {
        if (D('Links', 'Logic')->del($id)) {
            $this->success('链接删除成功');
        } else {
            $this->error('链接删除失败');
        }
    }


    public function themeInstallHandle($theme_name)
    {
        $res = D("Theme")->where(array("theme_name" => $theme_name))->find();
        if ($res) $this->error("主题已经安装");


        $tpl_static_path = WEB_ROOT . 'Public/' . $theme_name . '/';
        $theme_temp = array();

        $theme_xml_path = $tpl_static_path . 'theme.xml';
        if (file_exists($theme_xml_path)) {

            $theme_xml = File::readFile($theme_xml_path);

            $theme = simplexml_load_string($theme_xml);
            $theme_temp["theme_name"] = (string)$theme->name;
            $theme_temp["theme_description"] = (string)$theme->description;
            $theme_temp["theme_build"] = (string)$theme->build;
            $theme_temp["theme_versioin"] = (string)$theme->version;
            $theme_temp["theme_preview"] = (string)$theme->preview;
            $theme_temp["theme_copyright"] = (string)$theme->copyright;
            $theme_temp["theme_xml"] = $theme_xml;


            $config = object_to_array($theme->config);
            $config['post_type'] = object_to_array($theme->post);


            $theme_temp["theme_config"] = (string)json_encode($config);


            $res = D("Theme")->data($theme_temp)->add();
            if ($res) {
                $this->success("主题安装成功");
            } else {
                $this->error("主题安装失败");

            }

        } else {
            $this->error("主题描述文件缺失");

        }

    }


    public function themeUninstallHandle($theme_name)
    {
        if (get_kv('home_theme') == $theme_name) $this->error('正在使用的主题不可以删除');


        $res = D("Theme")->where(array("theme_name" => $theme_name))->delete();

        if ($res) {
            $this->success("卸载成功");

        } else {
            $this->error("卸载失败");
        }


    }


    public function themeDetail($theme_name)
    {
        $theme = D("Theme")->where(array("theme_name" => $theme_name))->find();
        if (!$theme) $this->error("主题尚未安装");
        $config = json_decode($theme['theme_config'], true);

        $tpl_static_path = WEB_ROOT . 'Public/' . $theme_name . '/';
        $theme_xml_path = $tpl_static_path . 'theme.xml';
        if (file_exists($theme_xml_path)) {
            $theme_xml = File::readFile($theme_xml_path);
            $theme_obj = simplexml_load_string($theme_xml);
            $theme_temp = object_to_array($theme_obj);
            $this->assign("theme_xml", $theme_temp);
        }


        $this->assign("config", $config);
        $this->assign("theme", $theme);
        $this->assign("action", $theme['theme_name'] . "主题详细");
        $this->display("themedetail");

    }


    public function themeConfig($theme_name)
    {
        $theme = D("Theme")->where(array("theme_name" => $theme_name))->find();
        if (!$theme) $this->error("主题尚未安装");


        $config = json_decode($theme['theme_config'], true);

        $this->assign("handle", U("Admin/Custom/themeConfigHandle", array('theme_name' => $theme_name)));

        $this->assign("theme", $theme);
        $this->assign("config", $config);
        $this->assign("action", "主题配置");
        $this->display("themeconfig");

    }

    public function themeConfigHandle($theme_name)
    {
        $theme = D("Theme")->where(array("theme_name" => $theme_name))->find();
        if (!$theme) $this->error("主题尚未安装");

        $config = json_decode($theme['theme_config'], true);

        $new_config = I('post.config');
        foreach ($new_config as $config_key => $config_value) {
            $config['kv'][$config_key]["value"] = $config_value;
        }


        $theme['theme_config'] = json_encode($config);

        $res = D("Theme")->where(array("theme_name" => $theme_name))->data($theme)->save();


        if ($res) {
            $this->success("主题配置保存成功", U("Admin/Custom/theme"));
        } else {
            $this->error("主题配置保存失败或者未更改");
        }

    }


    /**
     * 主题添加
     */
    public function themeAdd()
    {

        $this->assign('action', '主题添加');
        $this->assign('action_name', 'themeAdd');

        $this->display();
    }

    /**
     * 添加本地上传主题
     */
    public function themeAddLocal()
    {
        File::mkDir(WEB_CACHE_PATH);


        $config = array(
            'rootPath' => WEB_CACHE_PATH,
            "savePath" => '',
            "maxSize" => 100000000, // 单位B
            "exts" => array('zip'),
            "subName" => array(),
        );

        $upload = new Upload($config);
        $info = $upload->upload();
        if (!$info) { // 上传错误提示错误信息
            $this->error($upload->getError());
        } else { // 上传成功 获取上传文件信息

            $file_path_full = $info['file']['fullpath'];

            //dump($info);die($file_path_full);
            if (File::file_exists($file_path_full)) {

                $Update = new UpdateEvent();
                $applyRes = $Update->applyPatch($file_path_full);
                $applyInfo = json_decode($applyRes, true);

                if ($applyInfo['status']) {
                    $this->success($applyInfo['info'], U('Admin/Custom/theme'));
                } else {
                    $this->error($applyInfo['info']);
                }

            } else {
                $this->error('文件不存在');

            }
        }
    }



    //todo 需要检查是否真的成功
    /**
     * @param string $theme_name
     */
    public function themeDisableHandle($theme_name = 'NovaGreenStudio')
    {
        if (get_kv('home_theme') == $theme_name) $this->error('正在使用的主题不可以禁用');
        set_kv('theme_' . $theme_name, 'disabled');
        $this->success('禁用成功');
    }

    /**
     * @param string $theme_name
     */
    public function themeEnableHandle($theme_name = 'NovaGreenStudio')
    {

        set_kv('theme_' . $theme_name, 'enabled');

        S('theme_config', null);

        $this->success('启用成功');
    }


    /**
     * @param string $theme_name
     */
    public function themeChangeHandle($theme_name = 'NovaGreenStudio')
    {
        if (get_kv('home_theme') == $theme_name) $this->error('无需切换');
        S('theme_config', null);


        $res = set_kv('home_theme', $theme_name);

        set_kv($theme_name . '_theme_config', null);

        if ($res) {
            $cache_control = new SystemEvent();
            $cache_control->clearCacheAll();
            $this->success('切换成功');
        } else {
            $this->error('切换失败');
        }
    }

    /**
     * @param string $theme_name
     */
    public function themeDelHandle($theme_name = '')
    {
        if (get_kv('home_theme') == $theme_name) $this->error('正在使用的主题不可以删除');


        $tpl_view_path = WEB_ROOT . 'Application/Home/View/' . $theme_name . '/';
        $tpl_static_path = WEB_ROOT . 'Public/' . $theme_name . '/';
        File::delAll($tpl_view_path, true);
        File::delAll($tpl_static_path, true);
        $this->success('删除成功');
    }


    /**
     * @param string $theme_name
     */
    public function themeExportHandle($theme_name = '')
    {
        $tpl_view_path = 'Application/Home/View/' . $theme_name . '/';
        $tpl_static_path = 'Public/' . $theme_name . '/';

        $temp_path = WEB_CACHE_PATH;

        File::delDir(WEB_CACHE_PATH);
        File::mkDir(WEB_CACHE_PATH);

        $file_path = $temp_path . "\\" . 'GCS_Theme-' . $theme_name . '-' . md5(time()) . '.zip';

        $zip = new \ZipArchive; //新建一个ZipArchive的对象
        $res = $zip->open($file_path, \ZipArchive::CREATE);

        if ($res == true) {
            PHPZip::folderToZip($tpl_view_path, $zip);
            PHPZip::folderToZip($tpl_static_path, $zip);
            $zip->close();
        }


        if (!File::file_exists($file_path)) {
            $this->error("该文件不存在，可能是被删除");
        }
        $filename = basename($file_path);
        header("Content-type: application/octet-stream");
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header("Content-Length: " . filesize($file_path));
        readfile($file_path);


    }

    /**
     */
    public function pluginExportHandle($plugin_name = '')
    {
        $plugin_path = 'Addons/' . $plugin_name . '/';

        $temp_path = WEB_CACHE_PATH;

        File::delDir(WEB_CACHE_PATH);
        File::mkDir(WEB_CACHE_PATH);

        $file_path = $temp_path . "\\" . 'GCS_Plugin-' . $plugin_name . '-' . md5(time()) . '.zip';

        $zip = new \ZipArchive; //新建一个ZipArchive的对象
        $res = $zip->open($file_path, \ZipArchive::CREATE);

        if ($res == true) {
            PHPZip::folderToZip($plugin_path, $zip);
            $zip->close();
        }


        if (!File::file_exists($file_path)) {
            $this->error("该文件不存在，可能是被删除");
        }
        $filename = basename($file_path);
        header("Content-type: application/octet-stream");
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header("Content-Length: " . filesize($file_path));
        readfile($file_path);


    }
}