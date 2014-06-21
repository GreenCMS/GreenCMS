// 功能：在网页中浮动显示指定的图像
// 作者：yangwengang@hotmail.com (刚)
// 日期：2008-3-20
// 转载请保留本注释部分
//
// ***************************************************************************
// **承接ASP.NET开发外包**网站优化**网站架构规划**技术支持**3+年实际开发经验**
// **Email:yangwengang@hotmail.com**所在地：青岛**
// ***************************************************************************
//
// 使用方法：
//      1.在网页中添加此JS脚本文件的引用，如：<script type="text/javascript" src="floatimg.js"></script>
//      2.只需在网页加载（或单击按扭后）时调用FloatImg对象的show方法即可，如：
//          <script type="text/javascript"><!--//
//              var f1 = new FloatImg();//创建新的FloatImg对象
//              f1.show("f1","test1.jpg","http://www.caraq.com",137,81,1,0);
//              var f2 = new FloatImg();//创建新的FloatImg对象
//              f2.show("f2","test2.jpg","http://www.caraq.com",132,109,0.5,1);
//          --></script>
//          show 方法的参数依次为：FloatImg对象名,图像地址,点击图像转向URL,图像宽度,图像高度,移动速度(默认1),图像索引(当页面中需要显示多个浮动图像时分别指定不同的数值，如：0,1,2..)
//

var FloatImg = function() {
    this._interval_time= 10;//每次移动图像的间隔时间（ms），越小越平滑速度越慢,可适当修改为：10,20,30...
    //以下内容若不了解请不要修改
    this._x = 0;
    this._y = 0;
    this._interval_flag = 0;
    this._speed_x = 1;
    this._speed_y = 1;
    this._floatID_beforeStr = "floatimg";
    this._width = 0;
    this._height = 0;
    this._index = 0;
    this._objName = "";
    this._doc = document.documentElement;
}
FloatImg.prototype.show =
    function(objname,img,clicktourl,width,height,speed,index){

        this._speed_x = speed;
        this._speed_y = speed;

        this._width = width;
        this._height = height;
        this._index = index;

        var cw = this._doc.clientWidth-10;
        var ch = this._doc.clientHeight-10;
        this._x = cw/(index+1);
        this._y = ch/(index+1);
        this._objName = objname;

        if(!this.G(this._floatID_beforeStr+this._index)){
            document.body.innerHTML += "<div onmouseover=\""+this._objName+".stop();\" onmouseout=\""+this._objName+".start();\" id=\""+this._floatID_beforeStr+index+"\" style=\"position:absolute;z-index:10000;left:0px;top:0px;width:"+width+"px;height:"+height+"px;overflow:hidden;\"><a href=\""+clicktourl+"\" target=_blank><img src=\""+img+"\" border=0/></a></div>";
        }else{
            this.G(this._floatID_beforeStr+index).innerHTML = "<a href=\""+clicktourl+"\" target=_blank><img src=\""+img+"\" border=0/></a>";
        }
        this.start();
    };
FloatImg.prototype.start=
    function(){
        this._interval_flag = window.setInterval("FloatImg.interval("+this._objName+")",this._interval_time);
    };
FloatImg.prototype.G=
    function(v){ return document.getElementById(v);};
FloatImg.interval =
    function(a){
        var cw = a._doc.clientWidth-10;
        var ch = a._doc.clientHeight-10;
        if(a.G(a._floatID_beforeStr+a._index)){

            if(a._x < 0 || a._x+a._width > cw){
                if(a._x + a._width > cw)
                    a._x = cw - a._width;
                else
                    a._x = 0;
                a._speed_x = -a._speed_x;
            }
            if(a._y < 0 || a._y+a._height > ch){
                if(a._y+a._height > ch)
                    a._y = ch - a._height;
                else
                    a._y = 0;
                a._speed_y = -a._speed_y;
            }

            var fobj = a.G(a._floatID_beforeStr+a._index);
            a._x = a._x + a._speed_x;
            a._y = a._y + a._speed_y;
            fobj.style.left = (a._x + a._doc.scrollLeft) + "px";
            fobj.style.top =  (a._y+  + a._doc.scrollTop) + "px";

        }
    };
FloatImg.prototype.stop=
    function (){
        window.clearInterval(this._interval_flag);
    };