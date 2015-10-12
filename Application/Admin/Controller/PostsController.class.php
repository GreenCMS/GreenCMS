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
use Common\Logic\CatsLogic;
use Common\Logic\cc;
use Common\Logic\PostsLogic;
use Common\Logic\TagsLogic;
use Common\Logic\UserLogic;
use Common\Util\GreenPage;
use Common\Util\CacheManager;
use Org\Util\Rbac;

/**
 * Admin模块,文章控制器
 * Class PostsController
 * @package Admin\Controller
 */
class PostsController extends AdminBaseController
{

    public function __construct()
    {

        parent::__construct();

        CacheManager::clearCat();
        CacheManager::clearPost();
        CacheManager::clearTag();


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
        if (I('post.emptyAll') == 1) {
            $post_ids = I('post.posts');
            is_string($post_ids) == true ? $num = 0 : $num = count($post_ids);

            $res_info = '';
            foreach ($post_ids as $post_id) {
                $res = $PostsLogic->del($post_id);
                if ($res == false) $res_info = $res_info . ' 文章ID：' . $post_id . '批量彻底删除失败';
            }
            $this->success($num . '篇文章批量彻底删除成功' . $res_info);
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
        if (I('post.restoreAll') == 1) {
            $post_ids = I('post.posts');
            is_string($post_ids) == true ? $num = 0 : $num = count($post_ids);
            $res_info = '';
            foreach ($post_ids as $post_id) {
                $res = $PostsLogic->restore($post_id);
                if ($res == false) $res_info = $res_info . '文章ID：' . $post_id . '移至所有文章列表失败';
            }
            $this->success($num . '篇文章批量移至所有文章列表' . $res_info);
        }
        if (I('post.postAdd') == 1) {
            $this->redirect('Admin/Posts/add');
        }


    }


    /**
     * 页面列表群
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
     * @param string $tpl 模板名称
     * @param string $name
     */
    public function index($post_type = 'single', $post_status = 'publish', $order = 'post_date desc',
                          $keyword = '', $tpl = 'index_no_js', $name = '', $uid = 0)
    {
        $CatsLogic = new CatsLogic();
        $TagsLogic = new TagsLogic();

        $key = '';

        //获取get参数
        $cat = I('get.cat');
        $tag = I('get.tag');
        $page = I('get.page', get_opinion('PAGER'));
        $where = array('post_status' => $post_status);
        $where['post_content|post_title'] = array('like', "%$keyword%");
        $post_ids = array();

        if ($uid != 0 && $this->noVerify()) {
            $where['user_id'] = $uid;

            $UserLogic = new UserLogic();
            $user_info = $UserLogic->detail($uid);

            $key .= $user_info["user_nicename"] . ' 的';

        }

        //投稿员只能看到自己的
        if (!$this->noVerify()) {
            $where['user_id'] = get_current_user_id();
        }


        //处理详细信息 搜索，指定TAG CAT文章
        if ($cat != '') {
            $post_ids = $CatsLogic->getPostsIdWithChildren($cat);
            $post_ids = empty($post_ids) ? array('post_id' => 0) : $post_ids;

            $cat_detail = $CatsLogic->detail($cat);

            $cat = '关于分类 ' . $cat_detail['cat_name'] . ' 的';
        } else if ($tag != '') {
            $post_ids = $TagsLogic->getPostsId($tag);
            $post_ids = empty($post_ids) ? array('post_id' => 0) : $post_ids;

            $tag_detail = $TagsLogic->detail($tag);

            $tag = '关于标签' . $tag_detail['tag_name'] . ' 的';
        } else if ($keyword != '') {
            $key .= '关于' . $keyword . ' 的';

        }


        $PostsLogic = new PostsLogic();
        $count = $PostsLogic->countAll($post_type, $where, $post_ids); // 查询满足要求的总记录数

        if ($count != 0) {
            $Page = new GreenPage($count, $page); // 实例化分页类 传入总记录数
            $pager_bar = $Page->show();
            $limit = $Page->firstRow . ',' . $Page->listRows;
            $posts_list = $PostsLogic->getList($limit, $post_type, $order, true, $where, $post_ids);
        }


        $cats = $CatsLogic->category();
        $tags = $TagsLogic->select();


        $this->assign("cats", $cats);
        $this->assign("tags", $tags);


        $this->assign('post_type', $post_type);
        $this->assign('action', $name . $key . $cat . $tag . get_real_string($post_type) . '列表');
        $this->assign('posts', $posts_list);
        $this->assign('pager', $pager_bar);
        $this->display($tpl);
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

    /**
     * 页面列表
     * @param string $post_type
     * @param string $post_status
     * @param string $order
     * @param string $keyword
     */
    public function page($post_type = 'page', $post_status = 'publish', $order = 'post_date desc', $keyword = '')
    {
        $this->index($post_type, $post_status, $order, $keyword);
    }

    /**
     * 回收站列表
     * @param string $post_type
     * @param string $post_status
     * @param string $order
     * @param string $keyword
     */
    public function recycle($post_type = "all", $post_status = 'preDel', $order = 'post_date desc', $keyword = '')
    {
        $this->index($post_type, $post_status, $order, $keyword, 'recycle', "回收站");
        die();
    }

    /**
     * 未通过文章列表
     * @param string $post_type
     * @param string $post_status
     * @param string $order
     * @param string $keyword
     */
    public function reverify($post_type = "all", $post_status = 'reverify', $order = 'post_date desc', $keyword = '')
    {
        $this->index($post_type, $post_status, $order, $keyword, 'reverify', "未通过");
        die();
    }

    /**
     * 草稿箱列表
     * @param string $post_type
     * @param string $post_status
     * @param string $order
     * @param string $keyword
     */
    public function draft($post_type = "all", $post_status = 'draft', $order = 'post_date desc', $keyword = '')
    {
        $PostsLogic = new PostsLogic();
        $PostsLogic->emptyPostDraft($post_status);
        $this->index($post_type, $post_status, $order, $keyword, 'draft', "草稿箱");
        die();

    }

    /**
     * 待审核列表
     * @param string $post_type
     * @param string $post_status
     * @param string $order
     * @param string $keyword
     */
    public function unverified($post_type = "all", $post_status = 'unverified', $order = 'post_date desc', $keyword = '')
    {
        $this->index($post_type, $post_status, $order, $keyword, 'unverified', "待审核");
        die();
    }




    /**
     * 文章操作
     */


    /**
     * 添加文章的操作
     */
    public function add()
    {
        $PostsEvent = new PostsEvent();
        $post_id = $PostsEvent->insertEmpty();

//      $this->redirect(U("Admin/Posts/posts",array("id"=>$post_id)));
//      $post_restored = $PostEvent->restoreFromCookie();

        $this->posts($post_id, true);
        die();
    }


    /**
     * @param $id
     */
    public function posts($id = -1, $new_post = false)
    {
        $PostEvent = new PostsEvent();
        $CatsLogic = new CatsLogic();
        $TagsLogic = new TagsLogic();

//        dump($_POST);
//        die();

        $this->post_id = $post_id = $id ? (int)$id : false;
        $Posts = new PostsLogic();

        if (IS_POST) {
            $post_data = I('post.', '', '');
            if ($post_data['post_id'] != $id) {
                $this->jsonReturn(0, "更新失败，非法请求");
            }

            if (($this->noverify() == false) || (I('post.post_status') == 'unverified')) {
                $post_data['post_status'] = 'unverified';
            }

            $post_data['post_modified'] = date("Y-m-d H:m:s", time());
            $post_data['post_type'] = $_POST['post_type'] ? $_POST['post_type'] : 'single';
            D("post_cat")->where(array("post_id" => $post_data['post_id']))->delete();
            D("post_tag")->where(array("post_id" => $post_data['post_id']))->delete();

            if (!empty($_POST['tags'])) {
                foreach ($_POST['tags'] as $tag_name) {
                    $tags_temp = $TagsLogic->where(array("tag_name" => $tag_name))->find();
                    if (empty($tags_temp)) {
                        $TagsLogic->add(array("tag_name" => $tag_name, "tag_slug" => $tag_name));
                    }
                    $tags_temp = $TagsLogic->where(array("tag_name" => $tag_name))->find();


                    D("Post_tag")->add(array("tag_id" => $tags_temp["tag_id"], "post_id" => $post_data['post_id']));

//                D("Post_tag")->add(array("tag_id" => $tag_name, "post_id" => $post_data['post_id']));
                }
            }


            if (!empty($_POST['cats'])) {
                foreach ($_POST['cats'] as $cat_id) {
                    D("Post_cat")->add(array("cat_id" => $cat_id, "post_id" => $post_data['post_id']));
                };
            }
//
//            if (!empty($_POST['tags'])) {
//                foreach ($_POST['tags'] as $tag_id) {
//                    D("Post_tag")->add(array("tag_id" => $tag_id, "post_id" => $post_data['post_id']));
//                }
//            }

            if ($post_data['post_type'] == 'single') {
                $url = U('Admin/Posts/index');
            } elseif ($post_data['post_type'] == 'page') {
                $url = U('Admin/Posts/page');
            } else {
                $url = U('Admin/Posts/index');

            }

            if (!$this->noVerify()) {
                $url = U('Admin/Posts/unverified');
            }

            CacheManager::clearPostCacheById($id);


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


            if ($new_post) {
                $post['post_status'] = 'publish';
            }

            if (!$this->noVerify()) {
                $post['post_status'] = 'unverified';
            }

            $tpl_type_list = $PostEvent->getTplList();

            //投稿员只能看到权限内部的分类
            if (!$this->noVerify()) {
                $user = D('User', 'Logic')->detail(( int )$_SESSION [get_opinion('USER_AUTH_KEY')]);
                $role = D('Role')->where(array('id' => $user["user_role"] ["role_id"]))->find();

                $role_cataccess = json_decode($role ["cataccess"]);
                if ($role_cataccess == "") {
                    $role_cataccess = array();
                }

                $user_cataccess = json_decode($user ["cataccess"]);
                if ($user_cataccess == "") {
                    $user_cataccess = array();
                }

                $cataccess = array_merge($user_cataccess, $user_cataccess);

                $cat_limit = array('cat_id' => array('in', $cataccess));
                $cats = D('Cats', 'Logic')->where($cat_limit)->select();
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
            $this->assign("handle", U('Admin/Posts/posts', array('id' => $id, 'new_post' => $new_post), true, false));

            $this->assign("action", '编辑文章');
            $this->assign("action_name", 'posts');

            $this->display('post_v3');

        }

    }

    /**
     * 初始化编辑器链接
     * @param $post_id post_id
     */
    private function initEditor($post_id)
    {
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
        $data['post_modified'] = date("Y-m-d H:m:s", time());

        $data['user_id'] = I('post.post_user') ? I('post.post_user') : $_SESSION [get_opinion('USER_AUTH_KEY')];

        $data['post_tag'] = I('post.tags', array());
        $data['post_cat'] = I('post.cats', array());

        //TODO hook here to modifty the post data
        return $data;
    }

    /**
     * 文章添加处理
     * to be removed
     */
    public function addHandle()
    {
        $PostsLogic = new PostsLogic();


        $post_data = $this->dataHandle();

        if (($this->noverify() == false) || (I('post.post_status') == 'unverified')) {
            $post_data['post_status'] = 'unverified';
        } else {
            $post_data['post_status'] = 'publish';
        }


        if ($post_id = $PostsLogic->relation(true)->add($post_data)) { //, 'Logic'

            cookie('post_add', null);

            if ($post_data['post_type'] == 'single') {
                $this->jsonReturn(1, "发布成功", U('Admin/Posts/index'));
            } elseif ($post_data['post_type'] == 'page') {
                $this->jsonReturn(1, "发布成功", U('Admin/Posts/page'));
            } else {
                $this->jsonReturn(1, "发布成功", U('Admin/Posts/' . $post_data['post_type']));
            }

        } else {
            cookie('post_add', gzcompress(json_encode($post_data)), 3600000);
            //支持大约2.8万个字符 Ueditor计算方法，所有中文和英文数字都算一个字符计算
            $this->jsonReturn(0, "发布失败");
        }


    }













    /**
     * ==============================================================
     * ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
     *              回收站
     * ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
     * ==============================================================
     */

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
     * 通用文章状态修改器
     * @param $id
     * @param string $post_status
     * @param string $message
     */
    private function changePostStatue($id, $post_status = "publish", $message = "")
    {
        $PostsLogic = new PostsLogic();

        if (!$PostsLogic->has($id)) {
            $this->error("不存在该记录:" . $id);
        }

        if ($PostsLogic->changePostStatue($id, $post_status)) {
            $this->success($message . '成功');
        } else {
            $this->error($message . '失败');
        }


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
     * 删除到回收站
     * @param int $id
     */
    public function preDel($id = 0)
    {
        $this->changePostStatue($id, "preDel", "删除到回收站");
    }

    /**
     * 彻底删除
     * @param int $id
     */
    public function del($id = 0)
    {
        $PostsLogic = new PostsLogic();


        if ($PostsLogic->del($id)) {
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
        $PostsLogic = new PostsLogic();

        if ($PostsLogic->emptyPostHandleByStatus('draft')) {
            $this->success('清空草稿箱成功');
        } else {
            $this->error('清空草稿箱失败');
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
     * 清空回收站
     */
    public function emptyRecycleHandle()
    {

        $PostsLogic = new PostsLogic();

        if ($PostsLogic->emptyPostHandleByStatus('preDel')) {
            $this->success('清空回收站成功');
        } else {
            $this->error('清空回收站失败');
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
        $CatsLogic = new CatsLogic();

        $cat_list = $CatsLogic->selectWithPostsCount();
        foreach ($cat_list as $key => $value) {
            $cat_list[$key]["cat_father"] = $CatsLogic->detail($value["cat_father"]);
        }


        $this->assign('category', $cat_list);
        $this->display();
    }

    /**
     * 添加分类
     */
    public function addCategory()
    {
        $CatsLogic = new CatsLogic();

        $action = '添加分类';
        $this->assign('action', $action);
        $cat_list = $CatsLogic->category();

        $this->assign('cats', $cat_list);
        $this->display('addcategory');
    }

    /**
     * 处理添加分类
     */
    public function addCategoryHandle()
    {
        $CatsLogic = new CatsLogic();

        $data['cat_name'] = I('post.cat_name');
        $data['cat_slug'] = urlencode(I('post.cat_slug'));
        $data['cat_father'] = I('post.cat_father');
        $cat_data['cat_order'] = I('post.cat_order');

        if ($data['cat_slug'] == '') {
            $data['cat_slug'] = $data['cat_name'];
        }

        if ($CatsLogic->data($data)->add()) {
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
        $CatsLogic = new CatsLogic();

        $action = '编辑分类';
        $cat = $CatsLogic->detail($id);
        $cats = $CatsLogic->category();

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
        $CatsLogic = new CatsLogic();

        $cat_data['cat_name'] = I('post.cat_name');
        $cat_data['cat_slug'] = urlencode(I('post.cat_slug'));
        $cat_data['cat_father'] = I('post.cat_father');
        $cat_data['cat_order'] = I('post.cat_order');

        if ($CatsLogic->where(array('cat_id' => $id))->save($cat_data)) {
            $this->success('分类编辑成功', U('Admin/Posts/category'));
        } else {
            $this->error('分类编辑失败', U('Admin/Posts/category'));
        }
    }


    public function preDelCategory($id = -1)
    {
        $CatsLogic = new CatsLogic();
        $cats_list = $CatsLogic->category();

        $this->assign('form_url', U('Admin/Posts/delCategory', array('id' => $id)));
        $this->assign('cats_list', $cats_list);
        $this->assign('action', '删除分类');
        $this->display('delcat');
    }

    /**
     * 删除分类
     * @param $id
     */
    public function delCategory($id = -1)
    {
        $CatsLogic = new CatsLogic();
        $PostsLogic = new PostsLogic();

        $process_method = I('post.process_method');

        if ($process_method == 'tocat' && I('post.newcat') == $id) {
            $this->error("移动后的分类不能和当前分类相同");
        }

        if ($id == 1) {
            $this->error("默认分类不可删除");
        } else {
            if ($CatsLogic->delete($id)) {
                if (D('Post_cat')->where(array("cat_id" => $id))->find()) {
                    $pc_list = D('Post_cat')->where(array("cat_id" => $id))->select();
                    foreach ($pc_list as $pc) {
                        if ($process_method == 'del') {
                            $PostsLogic->preDel($pc['post_id']);
                        }

                        if ($process_method == 'tocat') {
                            $data['cat_id'] = I('post.newcat');
                            D('Post_cat')->where(array("pc_id" => $pc['pc_id']))->data($data)->save();
                        }
                    }
                }
            } else {
                $this->error('分类删除失败:没有找到指定分类,可能它已经被删除', U('Admin/Posts/category'));
            }
        }

        $this->success('分类删除成功', U('Admin/Posts/category'));
    }

    /**
     * 标签
     */
    public function tag()
    {
        $page = I('get.page', get_opinion('PAGER'));

        $TagsLogic = new TagsLogic();


        $count = $TagsLogic->countAll(); // 查询满足要求的总记录数

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
        $this->assign('action', '新增标签');
        $this->display();
    }

    /**
     * 处理添加标签
     */
    public function addTagHandle()
    {
        $TagsLogic = new TagsLogic();


        $tag_data['tag_name'] = I('post.tag_name');
        $tag_data['tag_slug'] = urlencode(I('post.tag_slug'));

        if ($tag_data['tag_slug'] == '') {
            $tag_data['tag_slug'] = urlencode($tag_data['tag_name']);
        }

        if ($TagsLogic->data($tag_data)->add()) {
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
        $TagsLogic = new TagsLogic();


        $action = '编辑';
        $this->assign('action', $action);

        $tag = $TagsLogic->detail($id);

        $this->assign('tag', $tag);
        $this->display();
    }

    /**
     * 处理编辑标签
     * @param $id
     */
    public function editTagHandle($id)
    {
        $TagsLogic = new TagsLogic();

        $tag_data['tag_name'] = I('post.tag_name');
        $tag_data['tag_slug'] = urlencode(I('post.tag_slug'));

        if ($tag_data['tag_slug'] == '') {
            $tag_data['tag_slug'] = urlencode($tag_data['tag_name']);
        }

        if ($TagsLogic->where(array('tag_id' => $id))->save($tag_data)) {

            $this->success('分类编辑成功', U('Admin/Posts/tag'));
        } else {
            $this->error('分类编辑失败~~可能没有更新', U('Admin/Posts/tag'));
        }
    }

    public function preDelTag($id = -1)
    {
        $TagsLogic = new TagsLogic();
        $tags_list = $TagsLogic->select();

        $this->assign('form_url', U('Admin/Posts/delTag', array('id' => $id)));
        $this->assign('tags_list', $tags_list);
        $this->assign('action', '删除标签');
        $this->display('deltag');
    }

    /**
     * 删除标签
     * @param $id
     */
    public function delTag($id = -1)
    {
        $TagsLogic = new TagsLogic();
        $PostsLogic = new PostsLogic();


        $process_method = I('post.process_method');

        if ($process_method == 'totag' && I('post.newtag') == $id) {
            $this->error("移动后的标签不能和当前分类相同");
        }


        if ($TagsLogic->delete($id)) {
            if (D('Post_tag')->where(array("tag_id" => $id))->find()) {
                $pt_list = D('Post_tag')->where(array("tag_id" => $id))->select();
                foreach ($pt_list as $pt) {
                    if ($process_method == 'del') {
                        $PostsLogic->preDel($pt['post_id']);
                    }

                    if ($process_method == 'totag') {
                        $data['tag_id'] = I('post.newtag');
                        D('Post_tag')->where(array("pt_id" => $pt['pt_id']))->data($data)->save();
                    }
                }
            }

        } else {
            $this->error('标签删除失败:没有找到指定标签,可能它已经被删除', U('Admin/Posts/tag'));
        }

        $this->success('标签删除成功', U('Admin/Posts/tag'));


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

    public function countAll()
    {

        $where = array();
        //投稿员只能看到自己的
        if (!$this->noVerify()) {
            $where['user_id'] = get_current_user_id();
        }

        $PostsLogic = new PostsLogic();

        $res = array();

        $post_status = C('post_status');
        foreach ($post_status as $key => $value) {
            $where ['post_status'] = $key;
            $count = $PostsLogic->countAll('single', $where); // 查询满足要求的总记录数
            $res['single'][$key] = $count;
        }

        $where ['post_status'] = 'publish';
        $count = $PostsLogic->countAll('page', $where); // 查询满足要求的总记录数
        $res['page']['publish'] = $count;


        $this->jsonReturn(1, $res);

    }


}