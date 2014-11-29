<?php
/**
 * Created by GreenStudio GCS Dev Team.
 * File: PostsController.class.php
 * User: Timothy Zhang
 * Date: 14-1-26
 * Time: 上午10:43
 */

namespace Admin\Controller;

use Admin\Event\PostsEvent;
use Common\Logic\PostsLogic;
use Common\Logic\TagsLogic;
use Common\Util\GreenPage;
use Org\Util\Rbac;

/**
 * Admin模块,文章控制器
 * Class PostsController
 * @package Admin\Controller
 */
class PostsController extends AdminBaseController
{
    /**
     * index页面操作筛选
     */
    public function indexHandle()
    {
        $PostsLogic = new PostsLogic();

        if (I('post.keyword') != '') {
            $this->redirect('Admin/Posts/' . I('post.post_type', 'single'), array('keyword' => I('post.keyword')));
        }


        if (I('post.delAll') == 1) {
            $post_ids = I('post.posts');
            is_string($post_ids) == true ? $num = 0 : $num = count($post_ids);

            $res_info = '';
            foreach ($post_ids as $post_id) {
                $res = $PostsLogic->preDel($post_id);
                if ($res == false) $res_info = $res_info . ' 文章ID：' . $post_id . '删除到回收站失败';
            }
            $this->success($num . '篇文章批量删除到回收站成功' . $res_info);
        }
        if (I('post.verifyAll') == 1) {
            $post_ids = I('post.posts');
            is_string($post_ids) == true ? $num = 0 : $num = count($post_ids);
            $res_info = '';
            foreach ($post_ids as $post_id) {
                $res = $PostsLogic->verify($post_id);
                if ($res == false) $res_info = $res_info . '文章ID：' . $post_id . '移至待审核列表失败';
            }
            $this->success($num . '篇文章批量移至待审核列表' . $res_info);
        }
        if (I('post.postAdd') == 1) {
            $this->redirect('Admin/Posts/add');
        }


    }





    /**
     * ==============================================================
     * ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
     *              页面
     * ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
     * ==============================================================
     */


    /**
     * 通用页面列表
     */
    public function single($post_type = 'single', $post_status = 'publish', $order = 'post_date desc', $keyword = '')
    {
        $this->index($post_type, $post_status, $order, $keyword);
    }

    /**
     * 列表显示，包括page和single
     * @param string $post_type 文章类型
     * @param string $post_status 文章状态
     * @param string $order 顺序
     * @param string $keyword 搜索关键词
     * @param string $tpl
     * @param string $name
     */
    public function index($post_type = 'single', $post_status = 'publish', $order = 'post_date desc',
                          $keyword = '', $tpl = 'index_no_js', $name = '')
    {

        //获取get参数
        $cat = I('get.cat');
        $tag = I('get.tag');
        $page = I('get.page', C('PAGER'));
        $where = array('post_status' => $post_status);
        $where['post_content|post_title'] = array('like', "%$keyword%");
        $post_ids = array();

        //投稿员只能看到自己的
        if (!$this->noVerify()) {
            $where['user_id'] = get_current_user_id();
        }

        //处理详细信息 搜索，指定TAG CAT文章
        if ($cat != '') {
            $post_ids = D('Cats', 'Logic')->getPostsId($cat);
            $post_ids = empty($post_ids) ? array('post_id' => 0) : $post_ids;
            $cat_detail = D('Cats', 'Logic')->detail($cat);
            $cat = '关于分类 ' . $cat_detail['cat_name'] . ' 的';
        } else if ($tag != '') {
            $post_ids = D('Tags', 'Logic')->getPostsId($tag);
            $post_ids = empty($post_ids) ? array('post_id' => 0) : $post_ids;
            $tag_detail = D('Tags', 'Logic')->detail($tag);
            $tag = '关于标签' . $tag_detail['tag_name'] . ' 的';
        } else if ($keyword != '') {
            $key = '关于' . $keyword . ' 的';

        }


        $PostsLogic = new PostsLogic();
        $count = $PostsLogic->countAll($post_type, $where, $post_ids); // 查询满足要求的总记录数

        if ($count != 0) {
            $Page = new GreenPage($count, $page); // 实例化分页类 传入总记录数
            $pager_bar = $Page->show();
            $limit = $Page->firstRow . ',' . $Page->listRows;
            $posts_list = $PostsLogic->getList($limit, $post_type, $order, true, $where, $post_ids);
        }

        $this->assign('post_type', $post_type);
        $this->assign('action', $name . $key . $cat . $tag . get_real_string($post_type) . '列表');
        $this->assign('posts', $posts_list);
        $this->assign('pager', $pager_bar);
        $this->display($tpl);
    }

    /**
     * 页面列表
     */
    public function page($post_type = 'page', $post_status = 'publish', $order = 'post_date desc', $keyword = '')
    {
        $this->index($post_type, $post_status, $order, $keyword);
    }

    /**
     * 回收站列表
     * @param string $post_type
     */
    public function recycle($post_type = "all")
    {
        $this->index($post_type, 'preDel', 'post_date desc', '', 'recycle', "回收站");
        die();


    }

    /**
     * 未通过文章列表
     * @param string $post_type
     */
    public function reverify($post_type = "all")
    {

        $this->index($post_type, 'reverify', 'post_date desc', '', 'reverify', "未通过");
        die();

    }

    /**
     * 草稿箱列表
     * @param string $post_type
     */
    public function draft($post_type = "all")
    {
        $this->index($post_type, 'draft', 'post_date desc', '', 'draft', "草稿箱");
        die();

    }

    /**
     * 待审核列表
     * @param string $post_type
     */
    public function unverified($post_type = "all")
    {
        $this->index($post_type, 'unverified', 'post_date desc', '', 'unverified', "待审核");
        die();
    }





    /**
     * ==============================================================
     * ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
     *              文章操作
     * ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
     * ==============================================================
     */


    /**
     * 添加文章的操作
     */
    public function add()
    {
        $PostsEvent = new PostsEvent();
        $post_id = $PostsEvent->insertEmpty();


        //       $this->redirect(U("Admin/Posts/posts",array("id"=>$post_id)));
//      $post_restored = $PostEvent->restoreFromCookie();

        $this->posts($post_id);

        die();


    }

    /**
     * TODO 自动保存
     *
     */
    public function autoSave()
    {

        $data = $this->dataHandle();
        cookie('post_add', gzcompress(json_encode($data)), 3600000);
    }

    /**
     * @return mixed
     */
    private function dataHandle()
    {
        $data['post_type'] = I('post.post_type', 'single');
        $data['post_title'] = I('post.post_title', '', '');
        $data['post_content'] = I('post.post_content', '', '');
        $data['post_template'] = I('post.post_template', $data['post_type']);
        $data['post_name'] = urlencode(I('post.post_name', $data['post_title'], ''));

        $data['post_date'] = I('post.post_date') ? I('post.post_date') : date("Y-m-d H:m:s", time());
        $data['post_modified'] = I('post.post_modified') ? I('post.post_modified') : date("Y-m-d H:m:s", time());

        $data['user_id'] = I('post.post_user') ? I('post.post_user') : $_SESSION [C('USER_AUTH_KEY')];

        $data['post_tag'] = I('post.tags', array());
        $data['post_cat'] = I('post.cats', array());

        //TODO hook here to modifty the post data
        return $data;
    }

    /**
     * 文章添加处理
     */
    public function addHandle()
    {
        $post_data = $this->dataHandle();

        if (($this->noverify() == false) || (I('post.post_status') == 'unverified')) {
            $post_data['post_status'] = 'unverified';
        } else {
            $post_data['post_status'] = 'publish';
        }


        if ($post_id = D('Posts')->relation(true)->add($post_data)) { //, 'Logic'

            cookie('post_add', null);

            if ($post_data['post_type'] == 'single') {
                $this->jsonReturn(1, "发布成功", U('Admin/Posts/index'));
            } elseif ($post_data['post_type'] == 'page') {
                $this->jsonReturn(1, "发布成功", U('Admin/Posts/page'));
            } else {
                //TODO hook here to process the unknown post type
            }

        } else {
            cookie('post_add', gzcompress(json_encode($post_data)), 3600000);
            //支持大约2.8万个字符 Ueditor计算方法，所有中文和英文数字都算一个字符计算
            $this->jsonReturn(0, "发布失败");
        }


    }


    /**
     * 通用文章状态修改器
     * @param $id
     * @param string $post_status
     * @param string $message
     */
    private function changePostStatue($id, $post_status = "publish", $message = "")
    {
        $PostEvent = new PostsEvent();

        if ($PostEvent->hasPost($id) == false) {
            $this->error("不存在该记录");
        }

        if ($PostEvent->changePostStatue($id, $post_status)) {
            $this->success($message . '成功');
        } else {
            $this->error($message . '失败');
        }


    }


    /**
     * 还原文章
     * @param int $id
     */
    public function recycleHandle($id = 0)
    {
        $this->changePostStatue($id, "publish", "恢复");
    }

    /**
     * 修改为已审核
     * @param $id
     * @param string $post_status
     */
    public function unverifiedHandle($id, $post_status = 'publish')
    {
        $this->changePostStatue($id, $post_status, "审核状态修改");
    }

    /**
     * 修改为未审核
     * @param $id
     */
    public function reverifyHandle($id)
    {
        $this->changePostStatue($id, "unverified", "审核状态修改");
    }











    /**
     * ==============================================================
     * ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
     *              回收站
     * ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
     * ==============================================================
     */

    /**
     * 删除到回收站
     * @param int $id
     */
    public function preDel($id = 0)
    {
        if (D('Posts', 'Logic')->preDel($id)) {
            $this->success('删除到回收站成功');
        } else {
            $this->error('删除到回收站失败');
        }
    }

    /**
     * 彻底删除
     * @param int $id
     */
    public function del($id = 0)
    {

        if (D("Posts", 'Logic')->del($id)) {
            $this->success('永久删除成功');
        } else {
            $this->error('永久删除失败');
        }
    }


    /**
     * 清空草稿站
     */
    public function emptyDraftHandle()
    {
        $where['post_status'] = 'draft';

        $PostsLogic = new PostsLogic();

        if ($PostsLogic->where($where)->relation(true)->delete()) {
            $this->success('清空草稿箱成功');
        } else {
            $this->error('清空草稿箱失败');
        }

    }


    /**
     * 清空回收站
     */
    public function emptyRecycleHandle()
    {
        $where['post_status'] = 'preDel';

        $PostsLogic = new PostsLogic();

        if ($PostsLogic->where($where)->relation(true)->delete()) {
            $this->success('清空回收站成功');
        } else {
            $this->error('清空回收站失败');
        }

    }


    private function initEditor($post_id)
    {

        /**
         *    var URL_upload = "{:U('Admin/Ueditor/imageUp')}";
         * var URL_fileUp = "{:U('Admin/Ueditor/fileUp')}";
         * var URL_scrawlUp = "{:U('Admin/Ueditor/scrawlUp')}";
         * var URL_getRemoteImage = "{:U('Admin/Ueditor/getRemoteImage')}";
         * var URL_imageManager = "{:U('Admin/Ueditor/imageManager')}";
         * var URL_imageUp = "{:U('Admin/Ueditor/imageUp')}";
         * var URL_getMovie = "{:U('Admin/Ueditor/getMovie')}";
         * var URL_home = "";
         */
        $this->assign("URL_upload", U('Admin/Ueditor/imageUp', array("post_id" => $post_id)));
        $this->assign("URL_fileUp", U('Admin/Ueditor/fileUp', array("post_id" => $post_id)));
        $this->assign("URL_scrawlUp", U('Admin/Ueditor/scrawlUp', array("post_id" => $post_id)));
        $this->assign("URL_getRemoteImage", U('Admin/Ueditor/getRemoteImage', array("post_id" => $post_id)));
        $this->assign("URL_imageManager", U('Admin/Ueditor/imageManager', array("post_id" => $post_id)));
        $this->assign("URL_imageUp", U('Admin/Ueditor/imageUp', array("post_id" => $post_id)));
        $this->assign("URL_getMovie", U('Admin/Ueditor/getMovie', array("post_id" => $post_id)));
        $this->assign("URL_home", "");


    }


    /**
     * @param $id
     */
    public function posts($id = -1)
    {
        $PostEvent = new PostsEvent();


        $this->post_id = $post_id = $id ? (int)$id : false;
        $Posts = new PostsLogic();

        if (IS_POST) {
            $post_data = $_POST;

            $post_data['post_modified'] = date("Y-m-d H:m:s", time());
            $post_data['post_type'] = $_POST['post_type'] ? $_POST['post_type'] : 'single';
            D("post_cat")->where(array("post_id" => $post_data['post_id']))->delete();
            D("post_tag")->where(array("post_id" => $post_data['post_id']))->delete();

            if (!empty($_POST['cats'])) {
                foreach ($_POST['cats'] as $cat_id) {
                    D("Post_cat")->add(array("cat_id" => $cat_id, "post_id" => $post_data['post_id']));
                };
            }

            if (!empty($_POST['tags'])) {
                foreach ($_POST['tags'] as $tag_id) {
                    D("Post_tag")->add(array("tag_id" => $tag_id, "post_id" => $post_data['post_id']));
                }
            }

            if ($post_data['post_type'] == 'single') {
                $url = U('Admin/Posts/index');
            } elseif ($post_data['post_type'] == 'page') {
                $url = U('Admin/Posts/page');
            } else {
                $url = U('Admin/Posts/index');

            }


            if ($Posts->where(array("post_id" => $post_data["post_id"]))->save($post_data)) {
                $this->jsonReturn(1, "已经更新", $url);
            } else {
                //处理失败
                cookie('post_add' . $post_data["post_id"], gzcompress(json_encode($post_data)), 3600000);
                //支持大约2.8万个字符 Ueditor计算方法，所有中文和英文数字都算一个字符计算
                $this->jsonReturn(0, "更新失败", $url);
            }
        } else {

            $this->initEditor($id);
            //投稿员只能看到自己的
            if (!$this->noVerify()) {
                $where['user_id'] = get_current_user_id();
            }


            $where["post_id"] = $post_id;

            $post = D('Posts')->relation(true)->where($where)->find();
            if (empty($post)) {
                $this->error("不存在该记录");
            }

            $tpl_type_list = $PostEvent->getTplList();

            //投稿员只能看到权限内部的分类
            if (!$this->noVerify()) {
                $user = D('User', 'Logic')->detail(( int )$_SESSION [C('USER_AUTH_KEY')]);
                $role = D('Role')->where(array('id' => $user["user_role"] ["role_id"]))->find();
                $cats = D('Cats', 'Logic')->where(array('in', json_decode($role ["cataccess"])))->select();
                foreach ($cats as $key => $value) {
                    $cats[$key]['cat_slug'] = $cats[$key]['cat_name'];
                }
                $tags = array();
            } else {
                $cats = D('Cats', 'Logic')->category();
                $tags = D('Tags', 'Logic')->select();
            }


            $this->assign("cats", $cats);
            $this->assign("tags", $tags);

            $this->assign('tpl_type', gen_opinion_list($tpl_type_list, $post['post_template']));
            $this->assign('post_status', gen_opinion_list(get_opinion("post_status"), $post['post_status']));
            $this->assign('post_type', gen_opinion_list(get_opinion("post_type"), $post['post_type']));


            $this->assign("info", $post);
            $this->assign("handle", U('Admin/Posts/posts', array('id' => $id), true, false));

            $this->assign("action", '编辑文章');
            $this->assign("action_name", 'posts');

            $this->display('post_v3');

        }

    }








    /**
     * ==============================================================
     * ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
     *              分类 与 标签
     * ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
     * ==============================================================
     */

    /**
     * 分类
     */
    public function category()
    {
        $cat_list = D("Cats", "Logic")->relation(true)->selectWithPostsCount();
        foreach ($cat_list as $key => $value) {
            $cat_list[$key]["cat_father"] = D('Cats', 'Logic')->detail($value["cat_father"]);
        }


        $this->assign('category', $cat_list);
        $this->display();
    }

    /**
     * 添加分类
     */
    public function addCategory()
    {
        $action = '添加';
        $this->assign('action', $action);
        $cat_list = D('Cats', 'Logic')->category();

        $this->assign('cats', $cat_list);
        $this->display('addcategory');
    }

    /**
     * 处理添加分类
     */
    public function addCategoryHandle()
    {


        $data['cat_name'] = I('post.cat_name');
        $data['cat_slug'] = urlencode(I('post.cat_slug'));
        $data['cat_father'] = I('post.cat_father');

        if ($data['cat_slug'] == '') {
            $data['cat_slug'] = $data['cat_name'];
        }

        if (D('Cats')->data($data)->add()) {
            $this->success('分类添加成功', U('Admin/Posts/category'));
        } else {
            $this->error('分类添加失败', U('Admin/Posts/category'));
        }
    }

    /**
     * 编辑分类
     * @param $id
     */
    public function editCategory($id)
    {

        $action = '编辑';
        $cat = D('Cats')->find($id);
        $cats = D('Cats', 'Logic')->category();

        $this->assign('action', $action);
        $this->assign('cat', $cat);
        $this->assign('cats', $cats);

        $this->display('editcategory');
    }

    /**
     * 处理编辑分类
     * @param $id
     */
    public function editCategoryHandle($id)
    {

        $Cats = D('Cats');
        $cat_data['cat_name'] = I('post.cat_name');
        $cat_data['cat_slug'] = urlencode(I('post.cat_slug'));
        $cat_data['cat_father'] = I('post.cat_father');

        if ($Cats->where(array('cat_id' => $id))->save($cat_data)) {
            $this->success('分类编辑成功', U('Admin/Posts/category'));
        } else {
            $this->error('分类编辑失败', U('Admin/Posts/category'));
        }
    }

    /**
     * 删除分类
     * @param $id
     */
    public function delCategory($id = -1)
    {

        if ($id == 1) {
            $this->error("默认分类不可删除");
        } else {
            if (D('Cats')->relation(true)->delete($id)) {

                $data['cat_id'] = '1';
                if (D('Post_cat')->where(array("cat_id" => $id))->find()) {
                    $post = M('Post_cat')->where(array("cat_id" => $id))->select();
                    foreach ($post as $v) {
                        M('Post_cat')->where(array("pc_id" => $v['pc_id']))->data($data)->save();
                    }
                }

                $this->success('分类删除成功', U('Admin/Posts/category'));
            } else {
                $this->success('分类删除失败:没有找到指定分类,可能它已经被删除', U('Admin/Posts/category'));
            }
        }
    }

    /**
     * 标签
     */
    public function tag()
    {
        $page = I('get.page', C('PAGER'));

        $TagsLogic = new TagsLogic();


        $count = $TagsLogic->count(); // 查询满足要求的总记录数

        if ($count != 0) {
            $Page = new GreenPage($count, $page); // 实例化分页类 传入总记录数
            $pager_bar = $Page->show();
            $limit = $Page->firstRow . ',' . $Page->listRows;
            $tags = $TagsLogic->selectWithPostsCount($limit);
        }


        $this->assign('tags', $tags);
        $this->assign('pager', $pager_bar);

        $this->display();
    }

    /**
     * 添加标签
     */
    public function addTag()
    {

        $this->display();
    }

    /**
     * 处理添加标签
     */
    public function addTagHandle()
    {
        $tag_data['tag_name'] = I('post.tag_name');
        $tag_data['tag_slug'] = urlencode(I('post.tag_slug'));

        if ($tag_data['tag_slug'] == '') {
            $tag_data['tag_slug'] = urlencode($tag_data['tag_name']);
        }

        if (D('Tags')->data($tag_data)->add()) {
            $this->success('标签添加成功', U('Admin/Posts/tag'));
        } else {

            $this->error('标签添加失败，有可能是tag_slug相同');

        }
    }

    /**
     * 编辑标签
     * @param $id
     */
    public function editTag($id)
    {
        $action = '编辑';
        $this->assign('action', $action);

        $tag = D('Tags')->find($id);

        $this->assign('tag', $tag);
        $this->display();
    }

    /**
     * 处理编辑标签
     * @param $id
     */
    public function editTagHandle($id)
    {

        $Tags = D('Tags');
        $tag_data['tag_name'] = I('post.tag_name');
        $tag_data['tag_slug'] = urlencode(I('post.tag_slug'));

        if ($tag_data['tag_slug'] == '') {
            $tag_data['tag_slug'] = urlencode($tag_data['tag_name']);
        }

        if ($Tags->where(array('tag_id' => $id))->save($tag_data)) {

            $this->success('分类编辑成功', U('Admin/Posts/tag'));
        } else {
            $this->error('分类编辑失败~~可能没有更新', U('Admin/Posts/tag'));
        }
    }

    /**
     * 删除标签
     * @param $id
     */
    public function delTag($id = -1)
    {
        if (D('Tags')->relation(true)->delete($id)) {
            $this->success('标签删除成功', U('Admin/Posts/tag'));
        } else {
            $this->success('标签删除失败:没有找到指定标签,可能它已经被删除', U('Admin/Posts/tag'));
        }
    }






    /**
     * ==============================================================
     * ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
     *              其他
     * ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
     * ==============================================================
     */


    /**
     * 通用列表页面
     * @function 未知类型单页
     */
    public function _empty($method, $args)
    {
        $this->index($method, I('get.post_status', 'publish'), I('get.order', 'post_date desc'), I('get.keyword', ''));
    }


    /**
     * 无需审核直接发布
     * @return bool 如果不用审核返回true，需要返回false
     */
    private function noVerify()
    {
        $user_id = get_current_user_id();
        $access_list = RBAC::getAccessList($user_id);
        if ($access_list['ADMIN']['POSTS']['NOVERIFY'] != '' || ($user_id == 1)) {
            return true;
        } else {
            return false;
        }

    }

}