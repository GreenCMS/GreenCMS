<?php
/**
 * Created by Green Studio.
 * File: PostsController.class.php
 * User: TianShuo
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
 * Class PostsController
 * @package Admin\Controller
 */
class PostsController extends AdminBaseController
{
    /**
     * 列表显示，包括page和single
     * @param string $post_type 文章类型
     * @param string $post_status 文章状态
     * @param string $order 顺序
     * @param string $keyword 搜索关键词
     */
    public function index($post_type = 'single', $post_status = 'publish', $order = 'post_id desc', $keyword = '')
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
        $this->assign('action', $key . $cat . $tag . get_real_string($post_type) . '列表');
        $this->assign('posts', $posts_list);
        $this->assign('pager', $pager_bar);
        $this->display('index_no_js');
    }

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
     * 页面列表
     */
    public function single($post_type = 'single', $post_status = 'publish', $order = 'post_id desc', $keyword = '')
    {
        $this->index($post_type, $post_status, $order, $keyword);
    }

    /**
     * 页面列表
     */
    public function page($post_type = 'page', $post_status = 'publish', $order = 'post_id desc', $keyword = '')
    {
        $this->index($post_type, $post_status, $order, $keyword);
    }

    /**
     * 添加文章的操作
     */
    public function add()
    {

        $PostEvent = new PostsEvent();


        $tpl_type_list = $PostEvent->get_tpl_type_list();
        $post = $PostEvent->restore_from_cookie();


        //投稿员只能看到自己的
        if (!$this->noVerify()) {
            //TODO 使用原生SQL提高效率
            $user_id = get_current_user_id();
            $user = D('User', 'Logic')->detail($user_id);
            $role_id = $user["user_role"] ["role_id"];
            $role = D('Role')->where(array('id' => $role_id))->find();
            $where['cat_id'] = array('in', json_decode($role ["cataccess"]));
            $cats = D('Cats', 'Logic')->where($where)->select();
            foreach ($cats as $key => $value) {
                $cats[$key]['cat_slug'] = $cats[$key]['cat_name'];
            }

        } else {

            $cats = D('Cats', 'Logic')->category();
            $tags = D('Tags', 'Logic')->select();
        }

        $this->assign('tpl_type', gen_opinion_list($tpl_type_list));

        $this->assign("info", $post);
        $this->assign("tags", $tags);
        $this->assign("cats", $cats);

        $this->assign("handle", U('Admin/Posts/addHandle'));
        $this->assign("publish", "发布");

        $this->display();
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
     * @return bool 如果不用审核返回true，需要返回false
     */
    public function noVerify()
    {
        $user_id = get_current_user_id();
        $access_list = RBAC::getAccessList($user_id);
        if ($access_list['ADMIN']['POSTS']['NOVERIFY'] != '' || ($user_id == 1)) {
            return true;
        } else {
            return false;
        }

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
        $data['post_modified'] = $data['post_date'] = date("Y-m-d H:m:s", time());
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
     * @param string $post_type
     */
    public function unverified($post_type = "all")
    {
        $where['post_status'] = 'unverified';

        //投稿员只能看到自己的
        if (!$this->noVerify()) {
            $where['user_id'] = get_current_user_id();
        }
        $posts = D('Posts', 'Logic')->getList(0, $post_type, 'post_date desc', true, $where);

        $this->assign('posts', $posts);
        $this->display();
    }

    /**
     * @param $id
     * @param string $post_status
     */
    public function unverifiedHandle($id, $post_status = 'publish')
    {

        $post_detail = D('Posts', 'Logic')->relation(true)->where(array("post_id" => $id))->find();
        if (empty($post_detail)) {
            $this->error("不存在该记录");
        }

        $post_data['post_status'] = $post_status;

        if (D('Posts', 'Logic')->where(array('post_id' => $id))->setField($post_data)) {
            $this->success('审核状态修改成功');
        } else {
            $this->error('审核状态修改失败');
        }

    }

    /**
     * @param string $post_type
     */
    public function recycle($post_type = "all")
    {
        $where['post_status'] = 'preDel';

        $posts_list = D('Posts', 'Logic')->getList(0, $post_type, 'post_id desc', true, $where);

        $this->assign('posts', $posts_list);

        $this->display();
    }

    /**
     * @param int $id
     */
    public function recycleHandle($id = 0)
    {

        $data['post_status'] = 'publish';
        if (D('Posts')->where(array('post_id' => $id))->setField($data)) {
            $this->success('恢复成功');
        } else {
            $this->error('恢复失败');
        }
    }

    /**
     *
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


    /**
     * @param string $post_type
     */
    public function reverify($post_type = "all")
    {
        $where['post_status'] = 'reverify';

        //投稿员只能看到自己的
        if (!$this->noVerify()) {
            $where['user_id'] = get_current_user_id();
        }
        $posts = D('Posts', 'Logic')->getList(0, $post_type, 'post_date desc', true, $where);

        $this->assign('posts', $posts);
        $this->display();

    }


    /**
     * @param $id
     */
    public function reverifyHandle($id)
    {
        $post_detail = D('Posts', 'Logic')->relation(true)->where(array("post_id" => $id))->find();
        if (empty($post_detail)) {
            $this->error("不存在该记录");
        }

        $post_data['post_status'] = "unverified";

        if (D('Posts', 'Logic')->where(array('post_id' => $id))->setField($post_data)) {
            $this->success('审核状态修改成功');
        } else {
            $this->error('审核状态修改失败');
        }

    }


    /**
     * @param $id
     */
    public function posts($id = -1)
    {

        $this->action = '编辑文章';
        $this->action_name = 'posts';
        $this->post_id = $post_id = $_GET['id'] ? (int)$_GET['id'] : false;
        $Posts = new PostsLogic();
        if (IS_POST) {
            $post_data = $_POST;

            $post_data['post_modified'] = date("Y-m-d H:m:s", time());
            $post_data['post_type'] = $_POST['post_type'] ? $_POST['post_type'] : 'single';
            M("post_cat")->where(array("post_id" => $post_data['post_id']))->delete();
            M("post_tag")->where(array("post_id" => $post_data['post_id']))->delete();

            if (!empty($_POST['cats'])) {
                foreach ($_POST['cats'] as $cat_id) {
                    M("Post_cat")->add(array("cat_id" => $cat_id, "post_id" => $post_data['post_id']));
                };
            }

            if (!empty($_POST['tags'])) {
                foreach ($_POST['tags'] as $tag_id) {
                    M("Post_tag")->add(array("tag_id" => $tag_id, "post_id" => $post_data['post_id']));
                }
            }

            if ($post_data['post_type'] == 'single') {
                $url = U('Admin/Posts/index');
            } elseif ($post_data['post_type'] == 'page') {
                $url = U('Admin/Posts/page');
            } else {
                $url = U('Admin/Posts/index');

            }


            if ($Posts->save($post_data)) {
                $this->jsonReturn(1, "已经更新", $url);
            } else {
                $this->jsonReturn(0, "更新失败", $url);
            }
        } else {

            //投稿员只能看到自己的
            if (!$this->noVerify()) {
                $where['user_id'] = get_current_user_id();
            }


            $where["post_id"] = $post_id;

            $post = D('Posts')->relation(true)->where($where)->find();
            if (empty($post)) {
                $this->error("不存在该记录");
            }

            $PostEvent = new PostsEvent();
            $tpl_type_list = $PostEvent->get_tpl_type_list();

            $this->assign('tpl_type', gen_opinion_list($tpl_type_list, $post['post_template']));


            //投稿员只能看到自己的
            if (!$this->noVerify()) {
                $user_id = ( int )$_SESSION [C('USER_AUTH_KEY')];
                $user = D('User', 'Logic')->detail($user_id);
                $role_id = $user["user_role"] ["role_id"];
                $role = D('Role')->where(array('id' => $role_id))->find();
                $where['cat_id'] = array('in', json_decode($role ["cataccess"]));
                $cats = D('Cats', 'Logic')->where($where)->select();
                foreach ($cats as $key => $value) {
                    $cats[$key]['cat_slug'] = $cats[$key]['cat_name'];
                }

            } else {

                $cats = D('Cats', 'Logic')->category();
                $tags = D('Tags', 'Logic')->select();
            }

            $this->assign("cats", $cats);
            $this->assign("tags", $tags);

            $this->assign("info", $post);
            $this->assign("handle", U('Admin/Posts/posts', array('id' => $id), true, false));

            $this->assign("publish", "更新");
            $this->display('add');

        }

    }

    /**
     *
     */
    public function category()
    {
        $cat_list = D("Cats", "Logic")->relation(true)->category();
        foreach ($cat_list as $key => $value) {
            $cat_list[$key]["cat_father"] = D('Cats', 'Logic')->detail($value["cat_father"]);
        }


        $this->assign('category', $cat_list);
        $this->display();
    }

    /**
     *
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
     *
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
     *
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
            $tags =$TagsLogic->selectWithPostsCount($limit);
        }


        $this->assign('tags', $tags);
        $this->assign('pager', $pager_bar);

        $this->display();
    }

    /**
     *
     */
    public function addTag()
    {

        $this->display();
    }

    /**
     *
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
     * @function 未知类型单页
     */
    public function _empty($method, $args)
    {
        $this->index($method, I('get.post_status', 'publish'), I('get.order', 'post_id desc'), I('get.keyword', ''));
    }
}