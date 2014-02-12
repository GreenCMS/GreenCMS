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

    //TODO menu
    public function menu()
    {
        $cat = new Category ('Menu', array('menu_id', 'menu_pid', 'menu_name', 'menu_construct'));

        $menu = $cat->getList(); // 获取分类结构
       // dump($menu);

        $this->assign('menu',$menu);

        $this->display();
    }

    public function menuDel($id)
    {



    }

    public function menuEdit($id)
    {



    }
    //TODO plugin
    public function theme()
    {
        $tpl_view = File::scanDir(WEB_ROOT . 'Application/Home/View');
        $tpl_static = File::scanDir(WEB_ROOT . 'Public');

        $tpl = array_intersect($tpl_view, $tpl_static);
        //dump($tpl);
        $this->display();
    }

    public function plugin()
    {

        // 安全验证
        // $this->checksafeauth();
        if (isset ($_GET ['title']))
            $this->assign("title", I('get.title'));
        if (!empty ($_GET ['status']))
            $map ['status'] = I('get.status');
        $map ['id'] = array('gt', 0);

        //$install = $this->_get('install', false);
        $install = I('get.install');
        if ($install != 1) {
            $Plugin = M('plugin');
            $count = $Plugin->where($map)->count();
            $fenye = 20;
            $p = new GreenPage ($count, get_opinion('pager'));
            $list = $Plugin->where($map)->order('pubdate desc')->limit($p->firstRow . ',' . $p->listRows)->select();
            // echo $model->getLastSql();exit;
            /* $p->setConfig('prev', '上一页');
             $p->setConfig('header', '条记录');
             $p->setConfig('first', '首 页');
             $p->setConfig('last', '末 页');
             $p->setConfig('next', '下一页');
             $p->setConfig('theme', "%first%%upPage%%linkPage%%downPage%%end%<li><span>共<font color='#009900'><b>%totalRow%</b></font>条记录 " . $fenye . "条/每页</span></li>");
             */
            $this->assign('page', $p->show());
            $this->assign("list", $list);

            $this->display();
        } else {
            $Plugin = M('plugin');
            $pluginlist = $Plugin->field('title')->select();
            $plist = array();
            foreach ($pluginlist as $v) {
                $plist [] = $v ['title'];
            }
            // 未安装插件
            $path = Plugin_PATH;
            $dir = File::get_dirs($path);
            foreach ($dir ['dir'] as $k => $v) {
                if (!in_array($v, $plist) && $v != '.' && $v != '..') {
                    $list ['title'] = $v;
                    if (file_exists($path . '/' . $v . '/plugin.xml')) {
                        $tag = simplexml_load_file($path . '/' . $v . '/plugin.xml');
                        $list ['author'] = ( string )$tag->author;
                        $list ['description'] = ( string )$tag->description;
                        $list ['copyright'] = ( string )$tag->copyright;
                    }
                    $list2 [] = $list;
                }
            }
            $this->assign("list", $list2);
            $this->display('plugin2');
        }
    }

    public function pluginManage()
    {
        // 为方便调试, 插件功能模块编译不缓存
        C('TMPL_CACHE_ON', false);
        $name = I('get.name');
        if (empty ($name))
            $this->die('参数错误!');
        $method = I('get.method');
        $method = empty ($method) ? 'index' : $method;
        $path = Plugin_PATH . $name . '/admin.php';
        if (file_exists($path)) {
            $model = M('plugin');
            $map ['title'] = $name;
            $list = $model->where($map)->find();
            if (!$list)
                $this->error('当前插件没有注册!');
            if ($list ['status'] == 1)
                $this->error('当前插件没有启用!');
        } else {
            $this->error('当前插件无管理功能!');
        }
        $this->plugin = plugin($name, $method);
        $this->action = '插件管理';
        $this->display();
    }

    // 插件导入
    public function pluginImport()
    {
        // 安全验证
        //$this->checksafeauth();
        $this->display();
    }

    // 执行插件导入
    public function pluginDoimport()
    {
        $filename = I('post.filename');
        $checkdir = I('post.checkdir');
        if (strtolower(substr($filename, -4)) != '.zip')
            $this->error('仅支持后缀为zip的压缩包');
        $path = ltrim($filename, __ROOT__ . '/');
        $filename = substr(ltrim(strrchr($path, '/'), '/'), 0, -4);
        $tplpath = Plugin_PATH . $filename;
        if (is_dir($tplpath) && $checkdir != 1)
            $this->error('插件目录已存在!');
        if (!is_file($path))
            $this->error('文件包不存在!');
        import('ORG.PclZip');
        $zip = new PclZip ($path);
        $zip->extract(PCLZIP_OPT_PATH, $tplpath);
        $this->success('操作成功!', U('Admin/Custom/plugin?install=1'));
    }

    // 插件安装
    public function pluginInstall()
    {
        $title = I('get.title');
        if (empty ($title))
            $this->error('插件名不存在!');
        $data ['description'] = '';
        $data ['author'] = '';
        $data ['copyright'] = '';
        $xmlpath = Plugin_PATH . $title . '/plugin.xml';
        if (file_exists($xmlpath)) {
            $tag = simplexml_load_file($xmlpath);
            $data ['author'] = ( string )$tag->author;
            $data ['copyright'] = ( string )$tag->copyright;
            $data ['description'] = ( string )$tag->description;
        }
        $data ['status'] = 1;
        $data ['title'] = $title;
        $data ['pubdate'] = time();
        $model = M('plugin');
        $model->add($data);
        $path = Plugin_PATH . $title . '/admin.php';
        if (file_exists($path)) {
            set_include_path(__ROOT__);
            include($path);
            call_user_func(array(
                                $title . 'Plugin',
                                '__install'
                           ));
        }
        $this->success('操作成功!', U('Admin/Custom/plugin?status=0'));
    }

    // 卸载插件
    public function pluginUninstall()
    {
        $map ['id'] = I('get.id');
        $model = M('plugin');
        $list = $model->field('title,status')->where($map)->find();
        if (!$list)
            $this->error('插件信息不存在!');
        if ($list ['status'] == 0)
            $this->error('请先禁用当前插件!');
        $model->where($map)->delete();
        $path = Plugin_PATH . $list['title'] . '/admin.php';
        if (file_exists($path)) {
            set_include_path(__ROOT__);
            include($path);
            call_user_func(array(
                                $list['title'] . 'Plugin',
                                '__uninstall'
                           ));
        }
        $this->success('操作成功!', U('Admin/Custom/plugin'));
    }

    public function pluginDel()
    {
        // 安全验证
        // $this->checksafeauth();
        $map ['title'] = I('get.title');
        $model = M('plugin');
        if ($model->where($map)->find())
            $this->error('请先卸载当前插件!');
        $path = Plugin_PATH . $map ['title'];
        File::del_dir($path);
        $this->success('操作成功!', U('Admin/Custom/plugin'));
    }

    // 插件开启和关闭(ajax处理)
    public function pluginStatus()
    {
        $map ['id'] = I('id');
        $Plugin = M('Plugin');
        $list = $Plugin->where($map)->find();
        if (!$list)
            die ('插件信息不存在!');
        $map ['status'] = $list ['status'] == 1 ? 0 : 1;
        $Plugin->save($map);
        die ('1');
    }

    public function pluginEdit()
    {
        $name = I('get.name');
        $path = Plugin_PATH . '/' . $name;
        if (empty ($name) or strpos($name, '/'))
            $this->error('参数不正确!');
        if (!is_dir($path))
            $this->error('插件目录不存在!');
        $configfile = $path . '/plugin.xml';
        $cachefile = $path . '/plugin.php';
        if (!is_file($cachefile)) {
            if (!is_file($configfile))
                $this->error('当前插件无扩展配置信息!');
            $this->assign("field", $this->parsexml($configfile, ''));
        } else {
            $cache = F('plugin', '', $path . '/');
            $this->assign("field", $this->parsexml($configfile, $cache));
        }
        C('TMPL_PARSE_STRING.__ROOT__', '__ROOT__');
        C('TMPL_PARSE_STRING.__APP__', '__APP__');
        C('TMPL_PARSE_STRING.__PUBLIC__', '__PUBLIC__');
        $this->display();
    }

    public function parsexml($file, $cache = '')
    {
        $xml = simplexml_load_file($file);
        $field = array();
        $field ['basic'] = $this->parsethemexml($xml, 'basic', $cache);
        $field ['advance'] = $this->parsethemexml($xml, 'advance', $cache);
        $field ['extend'] = $this->parsethemexml($xml, 'extend', $cache);
        return $field;
    }

    private function parsethemexml($xml, $node, $cache = '')
    {
        $field = array();
        foreach ($xml->$node->field as $k => $v) {
            $tag ['tag'] = ( string )$v->attributes()->tag;
            $tag ['name'] = ( string )$v->attributes()->name;
            $tag ['alt'] = ( string )$v->attributes()->alt;
            $tag ['value'] = ( string )$v->attributes()->value;
            $tag ['extend'] = ( string )$v->attributes()->extend;
            $tag ['editor'] = ( string )$v->attributes()->editor;
            $tag ['fullvalue'] = ( string )$v->attributes()->fullvalue;
            $tag ['before'] = ( string )$v->attributes()->before;
            $tag ['after'] = ( string )$v->attributes()->after;
            // html直接输出
            if ($tag ['tag'] == 'html')
                $tag ['data'] = ( string )$v;
            // cache判断
            if (!empty ($cache [$tag ['name']]))
                $tag ['value'] = $cache [$tag ['name']];
            if ($tag ['editor'] == 'image') {
                $id = uniqid();
                $tag ['editor'] = "<script src='" . __ROOT__ . "/Public/Editor/kindeditor/editor.php?fm=true&mode=plugin&type=image&buttonid={$id}&tag={$tag['tag']}&name={$tag['name']}'></script><input type='button' id='{$id}' value='选择图片'/>";
            }
            if ($tag ['editor'] == 'file') {
                $id = uniqid();
                $tag ['editor'] = "<script src='" . __ROOT__ . "/Public/Editor/kindeditor/editor.php?fm=true&mode=plugin&type=file&buttonid={$id}&tag={$tag['tag']}&name={$tag['name']}'></script><input type='button' id='{$id}' value='选择文件'/>";
            }
            if ($tag ['tag'] == 'editor') {
                $tag ['uniqid'] = uniqid();
                $tag ['select'] = "<script>var editor_{$tag['uniqid']};KindEditor.ready(function(K) {editor_{$tag['uniqid']} = K.create('#editor_{$tag['uniqid']}',{allowPreviewEmoticons : false,allowFileManager : true,resizeType : 1,items : ['source', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline','removeformat', '|', 'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist','insertunorderedlist', '|','table', 'image','insertfile','link','baidumap','fullscreen']});});</script>";
            } elseif ($tag ['tag'] == 'select') {
                if (empty ($tag ['value']))
                    $tag ['value'] = 0;
                $tag ['select'] = '';
                $values = explode(',', $tag ['fullvalue']);
                foreach ($values as $kk => $vv) {
                    if ($tag ['value'] == $kk) {
                        $tag ['select'] .= "<option value='" . $kk . "' selected='selected'>" . $vv . "</option>";
                    } else {
                        $tag ['select'] .= "<option value='" . $kk . "'>" . $vv . "</option>";
                    }
                }
            } elseif ($tag ['tag'] == 'radio') {
                if (empty ($tag ['value']))
                    $tag ['value'] = 0;
                $tag ['select'] = '';
                $values = explode(',', $tag ['fullvalue']);
                foreach ($values as $kk => $vv) {
                    if ($tag ['value'] == $kk) {
                        $tag ['select'] .= "<input name='{$tag['name']}' type='radio' value='" . $kk . "' class='noborder' checked='checked'/>" . $vv;
                    } else {
                        $tag ['select'] .= "<input name='{$tag['name']}' type='radio' value='" . $kk . "' class='noborder'/>" . $vv;
                    }
                }
            }
            $field [] = $tag;
        }
        return $field;
    }

    public function pluginDoedit()
    {
        $name = I('get.name');
        if (empty ($name))
            $this->error('参数不正确!');
        $tplpath = Plugin_PATH . $name . '/';
        if (!is_dir($tplpath))
            $this->error('插件目录不存在!');
        // 防止服务器反转义
        if (MAGIC_QUOTES_GPC) {
            foreach ($_POST as $k => $v) {
                $_POST [$k] = stripslashes($v);
            }
        }
        F('plugin', $_POST, $tplpath);
        $this->success('操作成功!', U('Admin/Custom/plugin'));
    }

    public function pluginDownload()
    {
        $dir = I('get.name');
        if (strpos($dir, '/') or empty ($dir))
            $this->error('参数不正确!');
        $path = Plugin_PATH . $dir;
        if (!is_dir($path))
            $this->error('目录不存在!');
        import('@.ORG.PclZip');
        $zippath = $dir . '.zip';
        $zip = new PclZip ($zippath);
        $zip->create($path, PCLZIP_OPT_REMOVE_PATH, $path);
        // 导出下载
        if (file_exists($zippath)) {
            $filename = $filename ? $filename : basename($zippath);
            $filetype = trim(substr(strrchr($filename, '.'), 1));
            $filesize = filesize($zippath);
            ob_end_clean();
            header('Cache-control: max-age=31536000');
            header('Expires: ' . gmdate('D, d M Y H:i:s', time() + 31536000) . ' GMT');
            header('Content-Encoding: none');
            header('Content-Length: ' . $filesize);
            header('Content-Disposition: attachment; filename=' . $filename);
            header('Content-Type: ' . $filetype);
            readfile($zippath);
            // 删除源文件
            unlink($zippath);
            exit ();
        } else {
            $this->error('导出失败!');
        }
    }

    // 远程安装插件
    public function pluginRemoteinstall()
    {
        // 安全验证 $this->checksafeauth();
        $url = I('get.url');
        $ext = strtolower(strrchr($url, '.'));
        $filepath = ltrim(strrchr($url, '/'), '/');
        if ($ext != '.zip') {
            // 兼容旧版本
            $url = xbase64_decode($url);
            $ext = strtolower(strrchr($url, '.'));
            $filepath = ltrim(strrchr($url, '/'), '/');
            if ($ext != '.zip')
                $this->error('远程文件格式必须为.zip');
        }
        $content = fopen_url($url);
        if (empty ($content)) {
            $this->assign('waitSecond', 20);
            $this->error('远程获取文件失败!,<a href="' . $url . '" target="_blank">本地下载安装</a>');
        }
        $filename = substr($filepath, 0, -4);
        $tplpath = Plugin_PATH . $filename;
        if (is_dir($tplpath))
            $this->error('插件目录已存在!');
        File::write_file($filepath, $content);
        import('ORG.PclZip');
        $zip = new PclZip ($filepath);
        $zip->extract(PCLZIP_OPT_PATH, $tplpath);
        @unlink($filepath); // 删除安装文件
        $this->success('操作成功!', U('Admin/Custom/plugin?install=1'));
    }


}