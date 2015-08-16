<?php
/**
 * Created by GreenStudio GCS Dev Team.
 * File: UeditorController.class.php
 * User: Timothy Zhang
 * Date: 14-1-27
 * Time: ??9:24
 */

namespace Admin\Controller;

use Common\Util\File;
use Think\Log;
use Think\Upload;

/**
 * Class UeditorController
 * @package Admin\Controller
 */
class UeditorController extends AdminBaseController
{


    private $post_id = 0;
    private $sub_name = array('date', 'Y/m-d');


    /**
     *
     */
    public function __construct()
    {
        parent::__construct();

        date_default_timezone_set("Asia/Shanghai");


        $this->post_id = (I('param.post_id', 0));

        error_reporting(E_ERROR | E_WARNING);
    }

    /**
     *
     */
    public function index()
    {
        // $this->display();
    }


    /**
     *
     */
//    public function scrawlUp()
//    {
//        header("Content-Type:text/html;charset=utf-8");
//
//        //上传配置
//        $config = array(
//            "savePath" => Upload_PATH . 'scraw/' . date('Y') . '/' . date('m') . '/', //存储文件夹
//            "maxSize" => 10000, //允许的文件最大尺寸，单位KB
//            "allowFiles" => array(".gif", ".png", ".jpg", ".jpeg", ".bmp") //允许的文件格式
//        );
//        //临时文件目录
//        $tmpPath = Upload_PATH . "tmp/";
//
//        //获取当前上传的类型
//        $action = htmlspecialchars($_GET["action"]);
//        if ($action == "tmpImg") { // 背景上传
//            //背景保存在临时目录中
//            $config["savePath"] = $tmpPath;
//            $up = new Uploader("upfile", $config);
//            $info = $up->getFileInfo();
//            /**
//             * 返回数据，调用父页面的ue_callback回调
//             */
//            echo "<script>parent.ue_callback('" . $info["url"] . "','" . $info["state"] . "')</script>";
//        } else {
//            //涂鸦上传，上传方式采用了base64编码模式，所以第三个参数设置为true
//            $up = new Uploader("content", $config, true);
//            //上传成功后删除临时目录
//            if (file_exists($tmpPath)) {
//                File::delDir($tmpPath);
//            }
//            $info = $up->getFileInfo();
//            echo "{'url':'" . $info["url"] . "',state:'" . $info["state"] . "'}";
//        }
//
//    }

    /**
     *
     */
    public function getContent()
    {


        echo '<meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>
            <script src="' . __ROOT__ . '/Extend/Ueditor/ueditor.parse.js" type="text/javascript"></script>
            <script>' . " uParse('.content',{
                  'highlightJsUrl':'" . __ROOT__ . "/Extend/Ueditor/third-party/SyntaxHighlighter/shCore.js',
                  'highlightCssUrl':" . __ROOT__ . "/Extend/Ueditor/third-party/SyntaxHighlighter/shCoreDefault.css'
              })</script>";


        $content = htmlspecialchars(stripslashes($_REQUEST ['myEditor']));

        // 存入数据库或者其他操作

        // 显示
        echo "第1个编辑器的值";
        echo "<div class='content'>" . htmlspecialchars_decode($content) . "</div>";

    }

    /**
     *
     */
    public function fileUp()
    {
        // header("Content-Type: text/html; charset=utf-8");


        $config = array(
            "savePath" => 'File/',
            "maxSize" => get_opinion('attachFileSize', false, 20*1000*1000), // 单位B，20M
            "exts" => explode(",", get_opinion("attachFileSuffix", false, 'zip,rar,doc,docx,zip,pdf,txt,ppt,pptx,xls,xlsx')),
            "subName" => $this->sub_name,
        );

        $upload = new Upload($config);
        $info = $upload->upload();

        if ($info) {
            $state = "SUCCESS";
        } else {
            $state = "ERROR" . $upload->getError();
        }
        /**
         * 得到上传文件所对应的各个参数,数组结构
         * array(1) {
         * ["upfile"] => array(9) {  //表单中字段名称
         * ["name"] => string(8) "head.jpg"  //源文件名称
         * ["type"] => string(10) "image/jpeg" //mine type
         * ["size"] => int(35578)  //文件大小
         * ["key"] => string(3) "img" //
         * ["ext"] => string(3) "jpg" //后缀
         * ["md5"] => string(32) "70f514f29b318f4cd6d8a4089a989f3c"
         * ["sha1"] => string(40) "d9c0a401b64a394ff71085205036b8a5d0e4a74d"
         * ["savename"] => string(17) "539992b8670f3.jpg" //保存名称
         * ["savepath"] => string(17) "Links/2014/06-12/" //保存路径
         * }
         * }
         */


        //save img info here


        /**
         * 向浏览器返回数据json数据
         * {
         *   'url'      :'a.rar',        //保存后的文件路径
         *   'fileType' :'.rar',         //文件描述，对图片来说在前端会添加到title属性上
         *   'original' :'编辑器.jpg',   //原始文件名
         *   'state'    :'SUCCESS'       //上传状态，成功时返回SUCCESS,其他任何值将原样返回至图片上传框中
         * }
         */

        $return_data['url'] = $info['upfile']['urlpath'];
        $return_data['fileType'] = $info['upfile']['ext'];
        $return_data['original'] = $info['upfile']['name'];
        $return_data['state'] = $state;

        $this->ajaxReturn($return_data);

    }

    /**
     * 获取远程图片
     */
    public function getRemoteImage()
    {
        header("Content-Type: text/html; charset=utf-8");

        //远程抓取图片配置
        $config = array(
            "savePath" => Upload_PATH . 'remote/' . date('Y') . '/' . date('m') . '/', //保存路径
            "allowFiles" => array(".gif", ".png", ".jpg", ".jpeg", ".bmp"), //文件允许格式
            "maxSize" => get_opinion('attachImgSize', false, 20000000),
        );
        $uri = htmlspecialchars($_REQUEST['upfile']);
        $uri = str_replace("&amp;", "&", $uri);
        $this->getRemoteImage2($uri, $config);

    }

    /**
     * 远程抓取
     * @param $uri
     * @param $config
     */
    public function getRemoteImage2($uri, $config)
    {
        //忽略抓取时间限制
        set_time_limit(0);
        //ue_separate_ue  ue用于传递数据分割符号
        $imgUrls = explode("ue_separate_ue", $uri);
        $tmpNames = array();
        foreach ($imgUrls as $imgUrl) {
            //http开头验证
            if (strpos($imgUrl, "http") !== 0) {
                array_push($tmpNames, "https error");
                continue;
            }

            //sae环境 不兼容
            if (!defined('SAE_TMP_PATH')) {
                //获取请求头
                $heads = get_headers($imgUrl);
                //死链检测
                if (!(stristr($heads[0], "200") && stristr($heads[0], "OK"))) {
                    array_push($tmpNames, "get_headers error");
                    continue;
                }
            }


            //格式验证(扩展名验证和Content-Type验证)
            $fileType = strtolower(strrchr($imgUrl, '.'));
            if (!in_array($fileType, $config['allowFiles']) || stristr($heads['Content-Type'], "image")) {
                array_push($tmpNames, "Content-Type error");
                continue;
            }

            //打开输出缓冲区并获取远程图片
            ob_start();
            $context = stream_context_create(
                array(
                    'http' => array(
                        'follow_location' => false // don't follow redirects
                    )
                )
            );
            //请确保php.ini中的fopen wrappers已经激活
            readfile($imgUrl, false, $context);
            $img = ob_get_contents();
            ob_end_clean();

            //大小验证
            $uriSize = strlen($img); //得到图片大小
            $allowSize = 1024 * $config['maxSize'];
            if ($uriSize > $allowSize) {
                array_push($tmpNames, "maxSize error");
                continue;
            }

            $savePath = $config['savePath'];


            if (!defined('SAE_TMP_PATH')) {

                //非SAE
                //创建保存位置
                if (!file_exists($savePath)) {
                    mkdir($savePath, 0777, true);
                }
                //写入文件
                $tmpName = $savePath . rand(1, 10000) . time() . strrchr($imgUrl, '.');
                try {
                    File::writeFile($tmpName, $img, "a");

                    array_push($tmpNames, __ROOT__ . '/' . $tmpName);
                } catch (\Exception $e) {
                    array_push($tmpNames, "error");
                }
            } else {
                //SAE

                $Storage = new \SaeStorage();
                $domain = get_opinion('SaeStorage');
                $destFileName = 'remote/' . date('Y') . '/' . date('m') . '/' . rand(1, 10000) . time() . strrchr($imgUrl, '.');
                $result = $Storage->write($domain, $destFileName, $img, -1);
                Log::write('$destFileName:' . $destFileName);
                if ($result) {
                    array_push($tmpNames, $result);
                } else {
                    array_push($tmpNames, "not supported");
                }

            }

        }
        /**
         * 返回数据格式
         * {
         *   'url'   : '新地址一ue_separate_ue新地址二ue_separate_ue新地址三',
         *   'srcUrl': '原始地址一ue_separate_ue原始地址二ue_separate_ue原始地址三'，
         *   'tip'   : '状态提示'
         * }
         */
        //save file info here

        $return_data['url'] = implode("ue_separate_ue", $tmpNames);
        $return_data['tip'] = '远程图片抓取成功！';
        $return_data['srcUrl'] = $uri;

        $this->ajaxReturn($return_data);


        //      echo "{'url':'" . implode("ue_separate_ue", $tmpNames) . "','tip':'远程图片抓取成功！','srcUrl':'" . $uri . "'}";
    }

    /**
     * 无需移植
     * @function getMovie
     */
    public function getMovie()
    {

        $key = get_opinion("tudouSearchKey");
        $type = I('post.videoType');
        $html = file_get_contents('http://api.tudou.com/v3/gw?method=item.search&appKey=myKey&format=json&kw=' .
            $key . '&pageNo=1&pageSize=20&channelId=' . $type . '&inDays=7&media=v&sort=s');
        echo $html;
    }

    /**
     * @function imageManager
     */
    public function imageManager()
    {

        header("Content-Type: text/html; charset=utf-8");


        //需要遍历的目录列表，最好使用缩略图地址，否则当网速慢时可能会造成严重的延时
        $paths = array(Upload_PATH, 'upload1/');

        //  $action = htmlspecialchars($_POST["action"]);
        $action = htmlspecialchars($_REQUEST["action"]);

        if ($action == "get") {
            if (!defined('SAE_TMP_PATH')) {
                $files = array();
                foreach ($paths as $path) {

                    //$dir = new Dir();
                    //$tmp = $dir->getfiles($path);

                    $tmp = File::getFiles($path);
                    if ($tmp) {
                        $files = array_merge($files, $tmp);
                    }
                }
                if (!count($files)) return;
                rsort($files, SORT_STRING);
                $str = "";
                foreach ($files as $file) {
                    $str .= __ROOT__ . '/' . $file . "ue_separate_ue";
                }
                echo $str;
            } else {
                // SAE环境下
                $st = new \SaeStorage(); // 实例化
                /*
                *  getList:获取指定domain下的文件名列表
                *  return: 执行成功时返回文件列表数组，否则返回false
                *  参数：存储域，路径前缀，返回条数，起始条数
                */
                $num = 0;
                while ($ret = $st->getList(get_opinion('SaeStorage'), null, 100, $num)) {
                    foreach ($ret as $file) {
                        if (preg_match("/\.(gif|jpeg|jpg|png|bmp)$/i", $file))

                            echo $st->getUrl('upload', $file) . "ue_separate_ue";
                        $num++;
                    }
                }
            }


        }


    }

    /**
     * @function imageUp
     */
    public function imageUp()
    {
        //   header("Content-Type: text/html; charset=utf-8");

        // 上传图片框中的描述表单名称，
        $title = htmlspecialchars($_POST ['pictitle'], ENT_QUOTES);
        $path = htmlspecialchars($_POST ['dir'], ENT_QUOTES);

        $config = array(
            "savePath" => 'Img/',
            "maxSize" => get_opinion('attachImgSize', false, 20000000), // 单位B
            "exts" => explode(",", get_opinion("attachImgSuffix", false, 'gif,png,jpg,jpeg,bmp')),
            "subName" => $this->sub_name,
        );

        $upload = new Upload($config);
        $info = $upload->upload();

        if ($info) {
            $state = "SUCCESS";
        } else {
            $state = "ERROR" . $upload->getError();
        }


        /**
         * 得到上传文件所对应的各个参数,数组结构
         * array(1) {
         * ["upfile"] => array(9) {  //表单中字段名称
         * ["name"] => string(8) "head.jpg"  //源文件名称
         * ["type"] => string(10) "image/jpeg" //mine type
         * ["size"] => int(35578)  //文件大小
         * ["key"] => string(3) "img" //
         * ["ext"] => string(3) "jpg" //后缀
         * ["md5"] => string(32) "70f514f29b318f4cd6d8a4089a989f3c"
         * ["sha1"] => string(40) "d9c0a401b64a394ff71085205036b8a5d0e4a74d"
         * ["savename"] => string(17) "539992b8670f3.jpg" //保存名称
         * ["savepath"] => string(17) "Links/2014/06-12/" //保存路径
         * }
         * }
         */


        /**
         * 向浏览器返回数据json数据
         * {
         * 'url' :'a.jpg', //保存后的文件路径
         * 'title' :'hello', //文件描述，对图片来说在前端会添加到title属性上
         * 'original' :'b.jpg', //原始文件名
         * 'state' :'SUCCESS' //上传状态，成功时返回SUCCESS,其他任何值将原样返回至图片上传框中
         * }
         */


        //save img attch info

        $return_data['url'] = $info['upfile']['urlpath'];
        $return_data['title'] = $title;
        $return_data['original'] = $info['upfile']['name'];
        $return_data['state'] = $state;

        $this->ajaxReturn($return_data);


    }
}