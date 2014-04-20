$(function(){ //头部nav部分； $('.indexnav-content div.menu1').hover(function(){$(this).addClass('focuse');},function(){$(this).removeClass('focuse');}) //
	$('#indexbanner .bannerchoose').each(function(){
		var href=$(this).attr('href');
		if(href!=""){(new Image()).src=href;}
	})
	var bannerfadetime=800;
	var onbanner=false;
	if($('#indexbanner .bannerinner a.hrefbanner').length>1){
		window.indexp={};
		(function(){
			indexp.num=$('#indexbanner .lights a').length;
			indexp.index=0;
			indexscroll();
		}())
		$('#indexbanner .right').bind('click',function(){
			if($('#indexbanner').is(":animated")){return false;}
			clearInterval(bannerinterval);
			indexp.index=(++indexp.index>=indexp.num?0:indexp.index);
			//console.log(indexp.index);
			$('.ground').hide().eq(indexp.index).css('display','block').css('opacity',0).animate({'opacity':1},bannerfadetime);
			$('#indexbanner .lights a').eq(indexp.index).addClass('focused').siblings('a').removeClass('focused');
			$('#indexbanner .bannerinner .hrefbanner').eq(indexp.index).show().siblings('.hrefbanner').hide();
			indexscroll();
		})
		$('#indexbanner .left').bind('click',function(){
			if($('#indexbanner').is(":animated")){return false;}
			clearInterval(bannerinterval);
			indexp.index=(indexp.index==0?indexp.num-1:--indexp.index);
			$('.ground').hide().eq(indexp.index).css('display','block').css('opacity',0).animate({'opacity':1},bannerfadetime);
			$('#indexbanner .lights a').eq(indexp.index).addClass('focused').siblings('a').removeClass('focused');
			$('#indexbanner .bannerinner .hrefbanner').eq(indexp.index).show().siblings('.hrefbanner').hide();
			indexscroll();
		})
		$('#indexbanner .lights a').bind('click',function(){
		    indexp.index=$(this).index();
			clearInterval(bannerinterval);
			$('.ground').hide().eq(indexp.index).css('display','block').css('opacity',0).animate({'opacity':1},bannerfadetime);
			$('#indexbanner .lights a').eq(indexp.index).addClass('focused').siblings('a').removeClass('focused');
			$('#indexbanner .bannerinner .hrefbanner').eq(indexp.index).show().siblings('.hrefbanner').hide();
			indexscroll();
		})
		
		$('.index-colum .li-1 .news .tit').hover(
		    function(){
				$(this).addClass('bordernone');
				$(this).parents('.news').eq(0).siblings('.news').find('.con').stop().hide().end().find('.tit').removeClass('bordernone');
			    $(this).next('.con').fadeIn();	
			},
			function(){}
		)
	}
	function indexscroll(){
			bannerinterval=setInterval(function(){
				if(onbanner)return;
				indexp.index=(++indexp.index>=indexp.num?0:indexp.index);
				$('#indexbanner .lights a').eq(indexp.index).addClass('focused').siblings('a').removeClass('focused');
				$('#indexbanner .bannerinner .describ').eq(indexp.index).show().siblings('.describ').hide();
				//console.log($('#indexbanner .bannerchoose').eq(indexp.index).attr('style'));
				$('.ground').hide().eq(indexp.index).css('display','block').css('opacity',0).animate({'opacity':1},bannerfadetime);
				$('#indexbanner .lights a').eq(indexp.index).addClass('focused').siblings('a').removeClass('focused');
				$('#indexbanner .bannerinner .hrefbanner').eq(indexp.index).show().siblings('.hrefbanner').hide();
			},4500)
	}
	
	$("#indexbanner").mouseover(function(){onbanner=true});
	$("#indexbanner").mouseout(function(){onbanner=false})
    
	//搜索效果；
	$('.headerwrap .logoarea .searchinput').bind('focus',function(){
		var text=this.defaultValue;
		if($(this).val()==text){$(this).val("");}
	}).bind('blur',function(){
		if($(this).val()==""){
			$(this).val(this.defaultValue);
		}
	})
	//nav部分
	$('.indexnav-content div.menu1').hover(function(){
		if($(this).find('.box').length>0) {
			var that=this;
			$(this).find('.box').show().css('visibility','hidden');
			var h=$(this).find('.box').height();
			$(this).addClass('focuse').find('.box').css({'display':'block','height':0,'visibility':'visible'}).stop().animate({'height':h},150,function(){}
			);
		}
	 },
		function(){
			if($(this).find('.box').length>0) {
				var that=this;
				$(this).removeClass('focuse').find('.box').css({'display':'block'}).stop().animate({'height':0},150,function(){
					$(that).find('.box').css('height','auto').hide();
				 }
				);
			 }
		}
	);
	
	if($('.traffic .area').length>0){
		$('.traffic .area span').bind('click',function(){
			var index=$(this).index();
			$(this).addClass('focused').siblings('span').removeClass('focused');
			$('.lines-choose').eq(index).show().siblings('.lines-choose').hide();
		})
	}
	if($('.cooperation-wrap .traffic-article .traffic-carline').length>0){
		$('.cooperation-wrap .traffic-article .traffic-carline span').bind('click',function(){
			var index=$(this).index();
			$(this).addClass('focused').siblings('span').removeClass('focused');
			$('.carline').eq(index).show().siblings('.carline').hide();
		})
	}
	//menutimeout=null;
	/*
	$('.cooperation-wrap .menu .inner dd').bind('click',function(){
		if($(this).hasClass('focused') || $(this).find('.box').length==0) return false;
		$(this).toggleClass('focuse');
		$(this).siblings('dd').removeClass('focuse');
	})*/
	if($('.internalstudent .article-1 .input').length>0){
		$('.internalstudent .article-1 .input').bind('focus',function(){
			var text=this.defaultValue;
			if($(this).val()==text){$(this).val("");}
		}).bind('blur',function(){
			if($(this).val()==""){
				$(this).val(this.defaultValue);
			}
		})
	}
	//访客随屏幕滚动
	if($('#guide').length>0){
		window.guide={};
		guide.ttop=$('.visitor-wrap .visitor .menu>#guide').offset().top;
		guide.scrollheight=$(document).height()-$('.footer').height()-$('.visitor-wrap .visitor .menu>#guide').height()-200;
		scrollvisitor();
		$(window).bind('scroll',function(){
			scrollvisitor();
		})
	//点击左边滚动到相应位置；
		$('.visitor-wrap .menu ul li').bind('click',function(){
			var attr=$(this).attr('attr');
			var top=$('.visitor-wrap .visitor .article h3[attr='+attr+']').offset().top-15;
			$('html').animate({'scrollTop':top},300);
		})
	}
	function scrollvisitor(){
		var top=$(document).scrollTop();
		if(top>=guide.scrollheight){
			//alert('o');
			$('.visitor-wrap .visitor .menu>#guide').css('top',guide.scrollheight-guide.ttop);
		}
		else if(top>=guide.ttop){
			$('.visitor-wrap .visitor .menu>#guide').css({'top':top-guide.ttop});
		}
		else{
			$('.visitor-wrap .visitor .menu>#guide').css({'top':'0px'});
		}
	}
	
})

$(function(){
    window.scrollbar=function(){
	      this.y1=0;
		  this.y2=0;
		  this.move=false;
		  this.top=0;
		  this.prop=1;
		  this.maxh=0;
		  this.top_1=0;
		  this.prop_1=1;
		  this.ifwheel=false;
		  this.getTargetEvent=function(obj,eventname,func){
			  if(obj.addEventListener){
			      obj.addEventListener(eventname,func);
			  }
			  else if(obj.attachEvent){
			      obj.attachEvent('on'+eventname,func);
			      return;
			  }
			  else{
			      obj['on'+eventname]=func;
			  }
		  }
		  this.removeTargetEvent=function(obj,eventname,func){
			  if(obj.removeEventListener){
				  if(typeof func=='function'){
					  obj.removeEventListener(eventname,func);
				  }
			  }
			  else if(obj.detachEvent){
				  if(typeof func=='function'){
					  obj.detachEvent('on'+eventname,func);
				  }
			      return;
			  }
			  else{
				  if(typeof func=='function'){
					  obj['on'+eventname]=null;
				  }
			  }
		  }
	   }
	   scrollbar.prototype.scrollsize=function(t,s,a){
		   t=s==0?(t+parseInt(this.bar.css('top'),10)):t;
	       if(t<=0){
			   this.scrollbox.css('top',0);
			   this.bar.css('top',0);
			   return false;
		   }
		   else if(t>=this.maxh){
			   this.bar.css('top',this.maxh+'px');
			   this.scrollbox.css('top',-1*(this.maxh*this.prop)+'px');
			   return false;
		   }
		   else{
			   this.scrollbox.css('top',-1*(t*this.prop)+'px');
			   this.bar.css('top',t+'px');
			   return false;
		   }
	   }
	   scrollbar.prototype.animatescroll=function(t){
	       if(t<=0){
			   this.bar.animate({'top':0},100);this.scrollbox.animate({'top':0},100); return false;
		   }
		   else if(t>=this.maxh){
			  this.bar.animate({'top':-1*(this.maxh*this.prop)+'px'},100);this.scrollbox.animate({'top':-1*(this.maxh*this.prop)+'px'},100);return false;
		   }
		   else{
			   this.bar.animate({'top':t+'px'},100);this.scrollbox.animate({'top':-1*(t*this.prop)+'px'},100);return false;
		   }
	   }
		scrollbar.prototype.detachevent=function(){
			var that=this;
		    if($.browser.mozilla){
			    this.removeTargetEvent(window,'DOMMouseScroll',that.wheelfunc);
		    }
		    else if($.browser.opera && parseFloat($.browser.version)<9.5){
			   this.removeTargetEvent(window,'mousewheel',that.wheelfunc);
		    }
		   else{
			   this.removeTargetEvent(document,'mousewheel',that.wheelfunc);
		   }
		}
	    scrollbar.prototype.bind=function(obj){   
		   this.scrollbox=obj.scrollbox;
		   this.bar=obj.bar;
		   this.hoverscroll=obj.hoverbox;
		   this.scrollbox.css('top',0);
		   this.step=parseInt(typeof obj.step=='undefined'?'20px':obj.step,10);
		   this.bar.css('top',0);
		   var barp_h=this.bar.parent().height();
		   var bar_h=this.bar.height();
		   var scrollboxp_h=this.scrollbox.parent().height();
		   var scrollbox_h=this.scrollbox.height();
		   if(scrollbox_h<=scrollboxp_h){this.bar.css('visibility','hidden');this.bar.parent().css('visibility','hidden'); return false;}else{this.bar.css('visibility','visible');this.bar.parent().css('visibility','visible');}
		   var h1=parseInt(scrollboxp_h/scrollbox_h*barp_h);
		   this.bar.css('height',h1+'px');
		   this.maxh=barp_h-h1;
		   this.prop=scrollbox_h/barp_h;
		   var that=this;
		   this.hoverscroll.hover(function(){that.ifwheel=true;},function(){that.ifwheel=false;})
		   this.wheelfunc=function(event){ if($.browser.mozilla){ if(that.ifwheel==true){ event.preventDefault(); var t=parseInt(event.detail*40*that.step/120); that.scrollsize(t,0); } } else if($.browser.opera && parseFloat($.browser.version)<9.5){ if(that.ifwheel==true){ if(window.event){window.event.returnValue=false;var e=window.event;} else{ event.preventDefault(); var e=event; } var t=parseInt(e.wheelDelta*that.step/120); that.scrollsize(t,0); } } else{ if(that.ifwheel==true){ if(window.event){window.event.returnValue=false;var e=window.event;} else{ event.preventDefault(); var e=event; } var t=parseInt(-1*e.wheelDelta*that.step/120); that.scrollsize(t,0); } } }
		   if($.browser.mozilla){
			    this.getTargetEvent(window,'DOMMouseScroll',that.wheelfunc);
		   }
		   else if($.browser.opera && parseFloat($.browser.version)<9.5){
			   this.getTargetEvent(window,'mousewheel',that.wheelfunc);
		   }
		   else{
		       this.getTargetEvent(document,'mousewheel',that.wheelfunc);
		   }
		   this.bar.parent().bind('click',function(e){
			  var target=e.target;
			  if(target==obj.bar[0]){return false;}
			  var top=e.pageY;
			  var top_1=obj.bar.offset().top+obj.bar.height();
			  var t;
			  if(top<obj.bar.offset().top){t=top-obj.bar.parent().offset().top;}
			  else{t=top-top_1+parseInt(obj.bar.css('top'),10);}
			  that.animatescroll(t);
		  })
		   this.bar.bind('mousedown',function(e){
			   e.preventDefault();
		       that.move=true;
			   that.y1=e.pageY;
			   that.top=parseInt(that.bar.css('top'),10);
			   that.top_1=parseInt(that.scrollbox.css('top'),10);
		   })
		   $(document).bind('mousemove',function(e){
		       if(that.move==true){
			       that.y2=e.pageY;
				   var x=that.y2-that.y1;
				   var top=x+that.top;
				   that.scrollsize(top,1);
			   }
		   }).bind('mouseup',function(e){
			   if(that.move==true){
				that.move=false;
				that.y1=that.y2=that.top=that.top_1=0;}
		  })
		  
	  }
	  
	  //现任领导；
	if($('.laderlayer').length>0){
		$('#leaderlayer').css('height',$(document).height());
		var leaderbox=null;
	    $('.cooperation-wrap .present-table td .name a').bind('click',function(e){
			e.preventDefault();
		    var attr=$(this).parent().attr('th');
			var h=$(document).scrollTop()-264;
			$('.laderlayer[th='+attr+']').show().css('margin-top',h);
			leaderbox=$('.laderlayer[th='+attr+']');
			$('#leaderlayer').show();
			sa=new scrollbar();
			sa.detachevent();
	        sa.bind({'scrollbox':$('.laderlayer[th='+attr+']').find('.leftarea .innerarea'),'bar':$('.laderlayer[th='+attr+']').find('.sidebar .bar'),'hoverbox':$('.laderlayer[th='+attr+']').find('.layerbox'),'step':'15px'});
		})
		$('.laderlayer .close').live('click',function(){
		    $(this).parent('.laderlayer').hide();
			$('#leaderlayer').hide();
		})
		$('.laderlayer .catogary a').live('click',function(){
		    var index=$(this).index();
			if($(this).hasClass('focuse')){return false;}
			$(this).addClass('focuse').siblings('a').removeClass('focuse');
			$(this).parents('.laderlayer').eq(0).find('table.list').eq(index).show().siblings('.list').hide();
			/*
			delete sa;
			sa=new scrollbar();
			*/
			sa.detachevent();
			sa.bind({'scrollbox':leaderbox.find('.leftarea .innerarea'),'bar':leaderbox.find('.sidebar .bar'),'hoverbox':leaderbox.find('.layerbox'),'step':'15px'});
		})
	}
})
