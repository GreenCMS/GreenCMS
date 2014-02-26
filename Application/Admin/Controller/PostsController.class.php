<?php
/**
 * Created by Green Studio.
 * File: PostsController.class.php
 * User: TianShuo
 * Date: 14-1-26
 * Time: 上午10:43
 */

namespace Admin\Controller;

use Org\Util\Rbac;

class PostsController extends AdminBaseController
{
    public function index($post_type = 'single', $post_status = 'publish')
    {
        $cat = I('get.cat');
        $tag = I('get.tag');
        $where = array('post_status' => $post_status);

        if ($cat != '') {
            $post_ids = D('Cats', 'Logic')->getPostsId($cat);
            $post_ids = null ? array('post_id' => 0) : $post_ids;
        } else if ($tag != '') {
            $post_ids = D('Tags', 'Logic')->getPostsId($tag);
            $post_ids = null ? array('post_id' => 0) : $post_ids;
        }


        $posts = D('Posts', 'Logic')->getList(0, $post_type, 'post_id desc', true, $where, $post_ids);

        $this->assign('posts', $posts);

        $this->display('index');
    }

    public function indexHandle()
    {
        if (I('post.delAll') == 1) {
            $post_ids = I('post.posts');
            is_string($post_ids) == true ? $num = 0 : $num = count($post_ids);

            foreach ($post_ids as $post_id) {
                $res = D('Posts', 'Logic')->preDel($post_id);
                if ($res == false) $this->success('文章ID：' . $post_id . '删除到回收站失败');
            }
            $this->success($num . '篇文章批量删除到回收站成功');
        }
        if (I('post.verifyAll') == 1) {
            $post_ids = I('post.posts');
            is_string($post_ids) == true ? $num = 0 : $num = count($post_ids);
            foreach ($post_ids as $post_id) {
                $res = D('Posts', 'Logic')->verify($post_id);
                if ($res == false) $this->success('文章ID：' . $post_id . '移至待审核列表失败');
            }
            $this->success($num . '篇文章批量移至待审核列表');
        }
        if (I('post.postAdd') == 1) {
            $this->redirect('Admin/Posts/add');
        }

    }

    public function page()
    {
        $this->index(page);
    }

    public function add()
    {

        $cats = D('Cats', 'Logic')->category();
        $tags = D('Tags', 'Logic')->select();

        $this->assign("tags", $tags);
        $this->assign("cats", $cats);

        $this->assign("handle", U('Admin/Posts/addHandle'));
        $this->assign("publish", "发布");

        $this->display();
    }

    public function noVerify()
    {
        $accessList = RBAC::getAccessList($_SESSION[C('USER_AUTH_KEY')]);
        if ($accessList['ADMIN']['POSTS']['NOVERIFY'] != '' || (( int )$_SESSION [C('USER_AUTH_KEY')] == 1)) {
            return true;
        } else {
            return false;
        }

    }

    public function addHandle()
    {
         $post_id = $_POST['post_id'] ? (int)$_POST['post_id'] : false;
        $data['post_type'] = $_POST['post_type'] ? $_POST['post_type'] : 'single';
        $data['post_title'] = $_POST['post_title'];
        $data['post_content'] = $_POST['post_content'];

        $data['post_name'] = $_POST['post_title'];
        $data['post_modified'] = $data['post_date'] = date("Y-m-d H:m:s", time());
        $data['user_id'] = $_SESSION [C('USER_AUTH_KEY')];


        if (($this->noverify() == false) || ($_POST['post_status'] == 'unverified')) {
            $data['post_status'] = 'unverified';
        } else {
            $data['post_status'] = 'publish';
        }


        if ($post_id) {
            if (D('Posts', 'Logic')->where(array('id' => $post_id))->save($data)) {
                if ($data['post_type'] == 'single') {
                    $this->success('修改成功', U('Admin/Posts/index'));
                } else {
                    $this->success('修改成功', U('Admin/Posts/page'));
                }

            } else {
                $this->error('修改失败');
            }
        } else {
            if ($post_id = D('Posts')->relation(true)->add($data)) { //, 'Logic'
                foreach ($_POST['cats'] as $cat_id) {
                    D("Post_cat", 'Logic')->add(array("cat_id" => $cat_id, "post_id" => $post_id));
                }
                foreach ($_POST['tags'] as $tag_id) {
                    D("Post_tag", 'Logic')->add(array("tag_id" => $tag_id, "post_id" => $post_id));
                }
                // $this->success('发布成功', U('Admin/Posts/index'));

                if ($data['post_type'] == 'single') {
                    $this->json_return(1, "发布成功", U('Admin/Posts/index'));
                } else {
                    $this->json_return(1, "发布成功", U('Admin/Posts/page'));
                }

            }
        }
    }

    public function preDel($id = 0)
    {
        if (D('Posts', 'Logic')->preDel($id)) {
            $this->success('删除到回收站成功');
        } else {
            $this->error('删除到回收站失败');
        }
    }

    public function del($id = 0)
    {

        if (D("Posts", 'Logic')->del($id)) {
            $this->success('永久删除成功');
        } else {
            $this->error('永久删除失败');
        }
    }

    public function unverified($post_type = "all")
    {
        $where['post_status'] = 'unverified';

        $posts = D('Posts', 'Logic')->getList(0, $post_type, 'post_date desc', true, $where);

        $this->assign('posts', $posts);
        $this->display();
    }

    public function unverifiedHandle($id, $post_status = 'publish')
    {

        $info = D('Posts', 'Logic')->relation(true)->where(array("post_id" => $id))->find();
        if (empty($info)) {
            $this->error("不存在该记录");
        }

        $data['post_status'] = $post_status;

        if (D('Posts', 'Logic')->where(array('post_id' => $id))->setField($data)) {
            $this->success('审核状态修改成功');
        } else {
            $this->error('审核状态修改失败');
        }

    }

    public function recycle($post_type = "all")
    {
        $where['post_status'] = 'preDel';

        $posts = D('Posts', 'Logic')->getList(0, $post_type, 'post_id desc', true, $where);

        $this->assign('posts', $posts);

        $this->display();
    }

    public function recycleHandle($id = 0)
    {

        $data['post_status'] = 'publish';
        if (M('Posts')->where(array('post_id' => $id))->setField($data)) {
            $this->success('恢复成功');
        } else {
            $this->error('恢复失败');
        }
    }

    public function posts($id = -1)
    {

        $this->action = '编辑文章';
        $this->action_name = 'posts';
        $this->post_id = $post_id = $_GET['id'] ? (int)$_GET['id'] : false;
        $M = M("Posts");
        if (IS_POST) {
            $data = $_POST;

            $data['post_modified'] = date("Y-m-d H:m:s", time());
            $data['post_type'] = $_POST['post_type'] ? $_POST['post_type'] : 'single';
            M("post_cat")->where(array("post_id" => $data['post_id']))->delete();
            M("post_tag")->where(array("post_id" => $data['post_id']))->delete();

            if (!empty($_POST['cats'])) {
                foreach ($_POST['cats'] as $cat_id) {
                    M("post_cat")->add(array("cat_id" => $cat_id, "post_id" => $data['post_id']));
                };
            }

            if (!empty($_POST['tags'])) {
                foreach ($_POST['tags'] as $tag_id) {
                    M("post_tag")->add(array("tag_id" => $tag_id, "post_id" => $data['post_id']));
                }
            }

            if ($data['post_type'] == 'single') {
                $url = U('Admin/Posts/index');
            } elseif ($data['post_type'] == 'page') {
                $url = U('Admin/Posts/page');
            } else {
                $url = U('Admin/Posts/index');

            }


            if ($M->save($data)) {
                $this->json_return(1, "已经更新", $url);
            } else {
                $this->json_return(0, "更新失败", $url);
            }
        } else {

            $info = D('Posts')->relation(true)->where(array("post_id" => $post_id))->find();
            if (empty($info)) {
                $this->error("不存在该记录");
            }
            $this->cats = D('Cats', 'Logic')->category();;
            $this->tags = M('Tags')->select();
            $this->assign("info", $info);
            $this->assign("handle", U('Admin/Posts/posts'));

            $this->assign("publish", "更新");
            $this->display('add');

        }

    }

    public function category()
    {

        $category = D("Cats", "Logic")->relation(true)->category();


        foreach ($category as $key => $value) {
            $category[$key]["cat_father"] = D('Cats', 'Logic')->detail($value["cat_father"]);
        }


        $this->assign('category', $category);

        $this->display();
    }

    public function addCategory()
    {
        $action = '添加';
        $this->assign('action', $action);
        $cats = D('Cats', 'Logic')->category();


        $this->assign('cats', $cats);
        $this->display();
    }

    public function addCategoryHandle()
    {


        $data['cat_name'] = I('post.cat_name');
        $data['cat_slug'] = I('post.cat_slug');
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

    public function editCategory($id)
    {

        $action = '编辑';
        $cat = D('Cats')->find($id);
        $cats = D('Cats', 'Logic')->category();

        $this->assign('action', $action);
        $this->assign('cat', $cat);
        $this->assign('cats', $cats);


        $this->display();
    }

    public function editCategoryHandle($id)
    {

        $Cats = D('Cats');
        $data['cat_name'] = I('post.cat_name');
        $data['cat_slug'] = I('post.cat_slug');
        $data['cat_father'] = I('post.cat_father');

        if ($Cats->where(array('cat_id' => $id))->save($data)) {

            $this->success('分类编辑成功', U('Admin/Posts/category'));
        } else {
            Log::write($Cats->getLastSql());
            $this->error('分类编辑失败', U('Admin/Posts/category'));
        }
    }

    public function delCategory($id = -1)
    {

        if ($id == 1) {
            $this->error("默认分类不可删除");
        } else {
            if (D('Cats')->relation(true)->del($id)) {

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

    public function tag()
    {

        $tags = D('Tags')->select();
        $this->assign('tags', $tags);

        $this->display();
    }

    public function addTag()
    {

        $this->display();
    }

    public function addTagHandle()
    {
        $data['tag_name'] = I('post.tag_name');
        $data['tag_slug'] = I('post.tag_slug');

        if ($data['tag_slug'] == '') {
            $data['tag_slug'] = $data['tag_name'];
        }

        if (D('Tags')->data($data)->add()) {
            $this->success('标签添加成功', U('Admin/Posts/tag'));
        }
    }

    public function editTag($id)
    {
        $action = '编辑';
        $this->assign('action', $action);

        $tag = D('Tags')->find($id);

        $this->assign('tag', $tag);
        $this->display();
    }

    public function editTagHandle($id)
    {

        $Tags = D('Tags');
        $data['tag_name'] = I('post.tag_name');
        $data['tag_slug'] = I('post.tag_slug');

        if ($Tags->where(array('tag_id' => $id))->save($data)) {

            $this->success('分类编辑成功', U('Admin/Posts/tag'));
        } else {
            $this->error('分类编辑失败~~可能没有更新', U('Admin/Posts/tag'));
        }
    }

    public function delTag($id = -1)
    {
        if (D('Tags')->relation(true)->delete($id)) {
            $this->success('标签删除成功', U('Admin/Posts/tag'));
        } else {
            $this->success('标签删除失败:没有找到指定标签,可能它已经被删除', U('Admin/Posts/tag'));
        }
    }

}