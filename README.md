GreenCMS - 基于ThikPHP的CMS系统  
================================  

    ********************************************************************************************  
    ********************************************************************************************  
    ******      ***********************************************       **   *****   ****       **  
    ***    ****   *******************************************    ***   *    ***    **   ****   *  
    **    **************************************************   *********    ***    *   *********  
    *   ****************************************************  **********     *     *   *********  
    *   ***********  *  ***      ******       ***  *     **   **********     *     *   *********  
    *   ***********    **   ***   ****   ***   **    **   *   **********     *     **    *******  
    *   ***********   **   *****  ***   *****  **  *****  *   **********           ****    *****  
    *   ***       *  **   ******   *    *****   *  *****  *   **********   *   *   ******     **  
    *   ******   **  **            *            *  *****  *   **********   *   *   ********   **  
    *   ******   **  **   **********   **********  *****  *   **********   ** **   *********   *  
    *   ******   **  **   **********   **********  *****  **  **********   ** **   *********   *  
    **   *****   **  ***   *********    *********  *****  **   *********   *****   *********   *  
    ***    ***   **  ****   ****  ***     ***  **  *****  ***   ****   *   *****   *   ****   **  
    *****       ***  ******      ******       ***  *****  *****       **   *****   ***       ***  
    ********************************************************************************************  
    ********************************************************************************************  
  


# 使用必读  
版权申明：
GreenCMS 欢迎学习交流，版权归作者所有，未经作者同意，不得删除代码中作者信息  

## 关于商业用途：
  
**需在Footer注明使用GreenCMS**
**或者加入友情链接指向GreenCMS.net**
  
## 联系方式:  
Email: admin@njut.asia
  
  
## GreenCMS交流群:  
QQ群: 123085170
  
  
## 官方网站:  
[http://www.greencms.net/](http://www.greencms.net/)  
  
## 官方论坛:  
[http://bbs.greencms.net/](http://bbs.greencms.net/)  

  
## GreenStudio微博:  
[http://weibo.com/u/3228716083](http://weibo.com/u/3228716083)

  
  
## Contributors:  
 - xjh1994  
 - zts1993  

 - others who give us help and advice  


  
  
```php
    echo "
    如果有疑问欢迎大家反馈  
    BUG提交可以直接发ISSUE  
    ";
```  
  
##其他信息:  
####安装之后需要的操作:  
    删除Install模块( /Application/Install)  --重要  
    删除Install数据( /Data/Install) --重要  
    关闭HTML_CACHE_ON(系统后台设置) --新版已经不需要了  
    不要忘记开启rewrite 或者 将/Home/config/config_router.php的url_mode改成0  
  
  
####关于与ThinkPHP的不同：  
> 虽然 GreenCMS是基于ThinkPHP 3.2.1 开发的CMS,我们尽可能使用ThinkPHP原生方法去编写，  
> 但是由于ThinkPHP自身不合理，所以部分文件可能需要替换  
> 目前已知的需要Patch的文件有  
  
    /Core/ThinkPHHP/Library/Think/Model/RelationModel.class.php （两处）// since thinkphp 3.2
  
    /Core/ThinkPHHP/Library/Think/Hook.class.php (一处) // since thinkphp 3.2.1
  
    /Core/ThinkPHHP/Library/Think/Template/TagLib/Cx.class.php (一处) // since thinkphp 3.2.1
  
    /Core/ThinkPHHP/Library/Think/Upload.class.php (一处)// since thinkphp 3.2
  
  
__如果你需要升级ThinkPHP框架请注意上面文件区别__
  
  
####NginX IIS用户须知:  
> 使用NginX IIS的用户可能需要配置rewrite或者pathinfo   
> 更多信息参考ThinkPHP3.2.1手册  
  
####index.php 选项:  
> APP_CACHE : 控制系统高速缓存(不建议关闭)  
> APP_DEBUG : 系统调试设置,项目正式部署后请设置为 false   
  
  
  
####安全风险 :  
> 已知安全风险包括:
> - Data/xxxBackup类文件夹外部可以访问,部署应放在www目录之外的位置 
> - 模板可能会被下载,apache通过.htaccess控制 
  
  
####安装建议 :  
> 完成后删除Data/Install文件夹  
> 在Application/Common/Conf/conig.php中删除不需要的模块信息  
  
  
###目录结构  
      
    /
    |--Addons 插件文件夹 (与OneThink高度兼容)
      
        |--  各个插件目录
      
    |--Application 应用目录
      
        |-- Admin    管理员模块
      
        |-- Home     主模块
      
        |-- Api      手机客户端API模块
      
        |-- Common   通用基础模块
      
        |-- Install  安装程序模块
      
        |-- Weixin   微信公共平台API模块
      
    |-- Core ThinkPHP 3.2.1 完整版 (有删减)
      
    |-- Data 运行文件
      
        |-- Backup    全站备份
      
        |-- Cache   升级等工作的缓存
      
        |-- DBbackup  数据库备份
      
        |-- Install   安装文件
      
        |-- Log   hinkPHP框架Log
      
        |-- Upgrade 升级文件
      
        |-- Temp ThinkPHP框架Temp目录
      
    |-- Extend 外部扩展文件夹
      
        |-- GreenFinder Elfinder文件管理器
      
        |-- PHPMailer 邮件发送类
      
        |-- Ueditor 编辑器
      
    |-- Public 公共文件夹
      
        |-- 各个主题文件夹
      
    |-- Upload 上传文件夹
      
        |-- 不同类型的附件
      
      
    //==
      
    README
      
    .htaccess
      
    .gitignore
      
    robots.txt
      
    LICENSE.txt
      
      
      
    //跳转文件
      
    admin.php
      
    weixin.php
      
    install.php
      
      
    //配置文件
      
    const_config.php
      
    version_config.php
      
    db_config.php
      
      
    //入口文件
      
    index.php  
      
  
  
  
###云平台须知  
  
####SAE(Sina App Engine)平台不能使用的功能  
> - 文章自动获取远程图片  
> - 部分文件上传  
    
####ACE(Aliyun Cloud Engine)平台不能使用的功能  
> - rewrite bug 导致系统不能运行  

####搜狐云景 (Souhu Cloudscape)平台不能使用的功能  
> - 没有原生Memcache和Storage服务

####BAE(Baidu App Engine)平台不能使用的功能  
> - 未测试
  
2014/7/31
  
  
  


![GreenStudio](http://green.njut.asia/Public/share/img/logo-png.png "GreenStudio logo")  