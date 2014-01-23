(function(K,m,Y){
	var L={
		transition:"elastic",speed:300,width:false,initialWidth:"600",innerWidth:false,maxWidth:false,height:false,initialHeight:"450",innerHeight:false,maxHeight:false,scalePhotos:true,scrolling:true,inline:false,html:false,iframe:false,fastIframe:true,photo:false,href:false,title:false,rel:false,opacity:0.9,preloading:true,current:"image {current} of {total}",previous:"previous",next:"next",close:"close",xhrError:"This content failed to load.",imgError:"This image failed to load.",open:false,returnFocus:true,reposition:true,loop:true,slideshow:false,slideshowAuto:true,slideshowSpeed:2500,slideshowStart:"start slideshow",slideshowStop:"stop slideshow",onOpen:false,onLoad:false,onComplete:false,onCleanup:false,onClosed:false,overlayClose:true,escKey:true,arrowKey:true,top:false,bottom:false,left:false,right:false,fixed:false,data:undefined
	}
	,y="colorbox",U="cbox",s=U+"Element",X=U+"_open",e=U+"_load",W=U+"_complete",v=U+"_cleanup",ae=U+"_closed",i=U+"_purge",w=!K.support.opacity&&!K.support.style,ah=w&&!Y.XMLHttpRequest,ac=U+"_IE6",R,ai,aj,d,I,q,b,Q,c,ab,O,k,h,p,u,Z,t,T,A,C,ag,ak,n,g,a,x,J,o,E,aa,N,B,M,af="div",ad;
	function H(al,ao,an){
		var am=m.createElement(al);
		if(ao){
			am.id=U+ao
		}
		if(an){
			am.style.cssText=an
		}
		return K(am)
	}
	function F(am){
		var al=c.length,an=(J+am)%al;
		return(an<0)?al+an:an
	}
	function P(al,am){
		return Math.round((/%/.test(al)?((am==="x"?l():S())/100):1)*parseInt(al,10))
	}
	function D(al){
		return ag.photo||/\.(gif|png|jp(e|g|eg)|bmp|ico)((#|\?).*)?$/i.test(al)
	}
	function l(){
		return Y.innerWidth||ab.width()
	}
	function S(){
		return Y.innerHeight||ab.height()
	}
	function V(){
		var al,am=K.data(x,y);
		if(am==null){
			ag=K.extend({},L);
			if(console&&console.log){
				console.log("Error: cboxElement missing settings object")
			}
		}
		else{
			ag=K.extend({},am)
		}
		for(al in ag){
			if(K.isFunction(ag[al])&&al.slice(0,2)!=="on"){
				ag[al]=ag[al].call(x)
			}
		}
		ag.rel=ag.rel||x.rel||K(x).data("rel")||"nofollow";
		ag.href=ag.href||K(x).attr("href");
		ag.title=ag.title||x.title;
		if(typeof ag.href==="string"){
			ag.href=K.trim(ag.href)
		}
	}
	function G(al,am){
		K.event.trigger(al);
		if(am){
			am.call(x)
		}
	}
	function z(){
		var am,ao=U+"Slideshow_",ap="click."+U,aq,an,al;
		if(ag.slideshow&&c[1]){
			aq=function(){
				Z.text(ag.slideshowStop).unbind(ap).bind(W,function(){
					if(ag.loop||c[J+1]){
						am=setTimeout(M.next,ag.slideshowSpeed)
					}
				}).bind(e,function(){
					clearTimeout(am)
				}).one(ap+" "+v,an);
				ai.removeClass(ao+"off").addClass(ao+"on");
				am=setTimeout(M.next,ag.slideshowSpeed)
			};
			an=function(){
				clearTimeout(am);
				Z.text(ag.slideshowStart).unbind([W,e,v,ap].join(" ")).one(ap,function(){
					M.next();
					aq()
				});
				ai.removeClass(ao+"on").addClass(ao+"off")
			};
			if(ag.slideshowAuto){
				aq()
			}
			else{
				an()
			}
		}
		else{
			ai.removeClass(ao+"off "+ao+"on")
		}
	}
	function f(al){
		if(!N){
			x=al;
			V();
			c=K(x);
			J=0;
			if(ag.rel!=="nofollow"){
				c=K("."+s).filter(function(){
					var an=K.data(this,y),am;
					if(an){
						am=K(this).data("rel")||an.rel||this.rel
					}
					return(am===ag.rel)
				});
				J=c.index(x);
				if(J===-1){
					c=c.add(x);
					J=c.length-1
				}
			}
			if(!E){
				E=aa=true;
				ai.show();
				if(ag.returnFocus){
					K(x).blur().one(ae,function(){
						K(this).focus()
					})
				}
				R.css({
					opacity:+ag.opacity,cursor:ag.overlayClose?"pointer":"auto"
				}).show();
				ag.w=P(ag.initialWidth,"x");
				ag.h=P(ag.initialHeight,"y");
				M.position();
				if(ah){
					ab.bind("resize."+ac+" scroll."+ac,function(){
						R.css({
							width:l(),height:S(),top:ab.scrollTop(),left:ab.scrollLeft()
						})
					}).trigger("resize."+ac)
				}
				G(X,ag.onOpen);
				C.add(p).hide();
				A.html(ag.close).show()
			}
			M.load(true)
		}
	}
	function r(){
		if(!ai&&m.body){
			ad=false;
			ab=K(Y);
			ai=H(af).attr({
				id:y,"class":w?U+(ah?"IE6":"IE"):""
			}).hide();
			R=H(af,"Overlay",ah?"position:absolute":"").hide();
			h=H(af,"LoadingOverlay").add(H(af,"LoadingGraphic"));
			aj=H(af,"Wrapper");
			d=H(af,"Content").append(O=H(af,"LoadedContent","width:0; height:0; overflow:hidden"),p=H(af,"Title"),u=H(af,"Current"),t=H(af,"Next"),T=H(af,"Previous"),Z=H(af,"Slideshow").bind(X,z),A=H(af,"Close"));
			aj.append(H(af).append(H(af,"TopLeft"),I=H(af,"TopCenter"),H(af,"TopRight")),H(af,false,"clear:left").append(q=H(af,"MiddleLeft"),d,b=H(af,"MiddleRight")),H(af,false,"clear:left").append(H(af,"BottomLeft"),Q=H(af,"BottomCenter"),H(af,"BottomRight"))).find("div div").css({
				"float":"left"
			});
			k=H(af,false,"position:absolute; width:9999px; visibility:hidden; display:none");
			C=t.add(T).add(u).add(Z);
			K(m.body).append(R,ai.append(aj,k))
		}
	}
	function j(){
		if(ai){
			if(!ad){
				ad=true;
				ak=I.height()+Q.height()+d.outerHeight(true)-d.height();
				n=q.width()+b.width()+d.outerWidth(true)-d.width();
				g=O.outerHeight(true);
				a=O.outerWidth(true);
				ai.css({
					"padding-bottom":ak,"padding-right":n
				});
				t.click(function(){
					M.next()
				});
				T.click(function(){
					M.prev()
				});
				A.click(function(){
					M.close()
				});
				R.click(function(){
					if(ag.overlayClose){
						M.close()
					}
				});
				K(m).bind("keydown."+U,function(am){
					var al=am.keyCode;
					if(E&&ag.escKey&&al===27){
						am.preventDefault();
						M.close()
					}
					if(E&&ag.arrowKey&&c[1]){
						if(al===37){
							am.preventDefault();
							T.click()
						}
						else{
							if(al===39){
								am.preventDefault();
								t.click()
							}
						}
					}
				});
				K("."+s,m).live("click",function(al){
					if(!(al.which>1||al.shiftKey||al.altKey||al.metaKey)){
						al.preventDefault();
						f(this)
					}
				})
			}
			return true
		}
		return false
	}
	if(K.colorbox){
		return
	}
	K(r);
	M=K.fn[y]=K[y]=function(al,an){
		var am=this;
		al=al||{};
		r();
		if(j()){
			if(!am[0]){
				if(am.selector){
					return am
				}
				am=K("<a/>");
				al.open=true
			}
			if(an){
				al.onComplete=an
			}
			am.each(function(){
				K.data(this,y,K.extend({},K.data(this,y)||L,al))
			}).addClass(s);
			if((K.isFunction(al.open)&&al.open.call(am))||al.open){
				f(am[0])
			}
		}
		return am
	};
	M.position=function(an,ap){
		var ar,au=0,am=0,aq=ai.offset(),al,ao;
		ab.unbind("resize."+U);
		ai.css({
			top:-90000,left:-90000
		});
		al=ab.scrollTop();
		ao=ab.scrollLeft();
		if(ag.fixed&&!ah){
			aq.top-=al;
			aq.left-=ao;
			ai.css({
				position:"fixed"
			})
		}
		else{
			au=al;
			am=ao;
			ai.css({
				position:"absolute"
			})
		}
		if(ag.right!==false){
			am+=Math.max(l()-ag.w-a-n-P(ag.right,"x"),0)
		}
		else{
			if(ag.left!==false){
				am+=P(ag.left,"x")
			}
			else{
				am+=Math.round(Math.max(l()-ag.w-a-n,0)/2)
			}
		}
		if(ag.bottom!==false){
			au+=Math.max(S()-ag.h-g-ak-P(ag.bottom,"y"),0)
		}
		else{
			if(ag.top!==false){
				au+=P(ag.top,"y")
			}
			else{
				au+=Math.round(Math.max(S()-ag.h-g-ak,0)/2)
			}
		}
		ai.css({
			top:aq.top,left:aq.left
		});
		an=(ai.width()===ag.w+a&&ai.height()===ag.h+g)?0:an||0;
		aj[0].style.width=aj[0].style.height="9999px";
		function at(av){
			I[0].style.width=Q[0].style.width=d[0].style.width=av.style.width;
			d[0].style.height=q[0].style.height=b[0].style.height=av.style.height
		}
		ar={
			width:ag.w+a,height:ag.h+g,top:au,left:am
		};
		if(an===0){
			ai.css(ar)
		}
		ai.dequeue().animate(ar,{
			duration:an,complete:function(){
				at(this);
				aa=false;
				aj[0].style.width=(ag.w+a+n)+"px";
				aj[0].style.height=(ag.h+g+ak)+"px";
				if(ag.reposition){
					setTimeout(function(){
						ab.bind("resize."+U,M.position)
					}
					,1)
				}
				if(ap){
					ap()
				}
			}
			,step:function(){
				at(this)
			}
		})
	};
	M.resize=function(al){
		if(E){
			al=al||{};
			if(al.width){
				ag.w=P(al.width,"x")-a-n
			}
			if(al.innerWidth){
				ag.w=P(al.innerWidth,"x")
			}
			O.css({
				width:ag.w
			});
			if(al.height){
				ag.h=P(al.height,"y")-g-ak
			}
			if(al.innerHeight){
				ag.h=P(al.innerHeight,"y")
			}
			if(!al.innerHeight&&!al.height){
				O.css({
					height:"auto"
				});
				ag.h=O.height()
			}
			O.css({
				height:ag.h
			});
			M.position(ag.transition==="none"?0:ag.speed)
		}
	};
	M.prep=function(am){
		if(!E){
			return
		}
		var ap,an=ag.transition==="none"?0:ag.speed;
		O.remove();
		O=H(af,"LoadedContent").append(am);
		function al(){
			ag.w=ag.w||O.width();
			ag.w=ag.mw&&ag.mw<ag.w?ag.mw:ag.w;
			return ag.w
		}
		function ao(){
			ag.h=ag.h||O.height();
			ag.h=ag.mh&&ag.mh<ag.h?ag.mh:ag.h;
			return ag.h
		}
		O.hide().appendTo(k.show()).css({
			width:al(),overflow:ag.scrolling?"auto":"hidden"
		}).css({
			height:ao()
		}).prependTo(d);
		k.hide();
		K(o).css({
			"float":"none"
		});
		if(ah){
			K("select").not(ai.find("select")).filter(function(){
				return this.style.visibility!=="hidden"
			}).css({
				visibility:"hidden"
			}).one(v,function(){
				this.style.visibility="inherit"
			})
		}
		ap=function(){
			var aB,ay,az=c.length,av,aA="frameBorder",au="allowTransparency",ar,aq,ax,aw;
			if(!E){
				return
			}
			function at(){
				if(w){
					ai[0].style.removeAttribute("filter")
				}
			}
			ar=function(){
				clearTimeout(B);
				h.detach().hide();
				G(W,ag.onComplete)
			};
			if(w){
				if(o){
					O.fadeIn(100)
				}
			}
			p.html(ag.title).add(O).show();
			if(az>1){
				if(typeof ag.current==="string"){
					u.html(ag.current.replace("{current}",J+1).replace("{total}",az)).show()
				}
				t[(ag.loop||J<az-1)?"show":"hide"]().html(ag.next);
				T[(ag.loop||J)?"show":"hide"]().html(ag.previous);
				if(ag.slideshow){
					Z.show()
				}
				if(ag.preloading){
					aB=[F(-1),F(1)];
					while(ay=c[aB.pop()]){
						aw=K.data(ay,y);
						if(aw&&aw.href){
							aq=aw.href;
							if(K.isFunction(aq)){
								aq=aq.call(ay)
							}
						}
						else{
							aq=ay.href
						}
						if(D(aq)){
							ax=new Image();
							ax.src=aq
						}
					}
				}
			}
			else{
				C.hide()
			}
			if(ag.iframe){
				av=H("iframe")[0];
				if(aA in av){
					av[aA]=0
				}
				if(au in av){
					av[au]="true"
				}
				av.name=U+(+new Date());
				if(ag.fastIframe){
					ar()
				}
				else{
					K(av).one("load",ar)
				}
				av.src=ag.href;
				if(!ag.scrolling){
					av.scrolling="no"
				}
				K(av).addClass(U+"Iframe").appendTo(O).one(i,function(){
					av.src="//about:blank"
				})
			}
			else{
				ar()
			}
			if(ag.transition==="fade"){
				ai.fadeTo(an,1,at)
			}
			else{
				at()
			}
		};
		if(ag.transition==="fade"){
			ai.fadeTo(an,0,function(){
				M.position(0,ap)
			})
		}
		else{
			M.position(an,ap)
		}
	};
	M.load=function(an){
		var am,ao,al=M.prep;
		aa=true;
		o=false;
		x=c[J];
		if(!an){
			V()
		}
		G(i);
		G(e,ag.onLoad);
		ag.h=ag.height?P(ag.height,"y")-g-ak:ag.innerHeight&&P(ag.innerHeight,"y");
		ag.w=ag.width?P(ag.width,"x")-a-n:ag.innerWidth&&P(ag.innerWidth,"x");
		ag.mw=ag.w;
		ag.mh=ag.h;
		if(ag.maxWidth){
			ag.mw=P(ag.maxWidth,"x")-a-n;
			ag.mw=ag.w&&ag.w<ag.mw?ag.w:ag.mw
		}
		if(ag.maxHeight){
			ag.mh=P(ag.maxHeight,"y")-g-ak;
			ag.mh=ag.h&&ag.h<ag.mh?ag.h:ag.mh
		}
		am=ag.href;
		B=setTimeout(function(){
			h.show().appendTo(d)
		}
		,100);
		if(ag.inline){
			H(af).hide().insertBefore(K(am)[0]).one(i,function(){
				K(this).replaceWith(O.children())
			});
			al(K(am))
		}
		else{
			if(ag.iframe){
				al(" ")
			}
			else{
				if(ag.html){
					al(ag.html)
				}
				else{
					if(D(am)){
						K(o=new Image()).addClass(U+"Photo").error(function(){
							ag.title=false;
							al(H(af,"Error").html(ag.imgError))
						}).load(function(){
							var ap;
							o.onload=null;
							if(ag.scalePhotos){
								ao=function(){
									o.height-=o.height*ap;
									o.width-=o.width*ap
								};
								if(ag.mw&&o.width>ag.mw){
									ap=(o.width-ag.mw)/o.width;
									ao()
								}
								if(ag.mh&&o.height>ag.mh){
									ap=(o.height-ag.mh)/o.height;
									ao()
								}
							}
							if(ag.h){
								o.style.marginTop=Math.max(ag.h-o.height,0)/2+"px"
							}
							if(c[1]&&(ag.loop||c[J+1])){
								o.style.cursor="pointer";
								o.onclick=function(){
									M.next()
								}
							}
							if(w){
								o.style.msInterpolationMode="bicubic"
							}
							setTimeout(function(){
								al(o)
							}
							,1)
						});
						setTimeout(function(){
							o.src=am
						}
						,1)
					}
					else{
						if(am){
							k.load(am,ag.data,function(aq,ap,ar){
								al(ap==="error"?H(af,"Error").html(ag.xhrError):K(this).contents())
							})
						}
					}
				}
			}
		}
	};
	M.next=function(){
		if(!aa&&c[1]&&(ag.loop||c[J+1])){
			J=F(1);
			M.load()
		}
	};
	M.prev=function(){
		if(!aa&&c[1]&&(ag.loop||J)){
			J=F(-1);
			M.load()
		}
	};
	M.close=function(){
		if(E&&!N){
			N=true;
			E=false;
			G(v,ag.onCleanup);
			ab.unbind("."+U+" ."+ac);
			R.fadeTo(200,0);
			ai.stop().fadeTo(300,0,function(){
				ai.add(R).css({
					opacity:1,cursor:"auto"
				}).hide();
				G(i);
				O.remove();
				setTimeout(function(){
					N=false;
					G(ae,ag.onClosed)
				}
				,1)
			})
		}
	};
	M.remove=function(){
		K([]).add(ai).add(R).remove();
		ai=null;
		K("."+s).removeData(y).removeClass(s).die()
	};
	M.element=function(){
		return K(x)
	};
	M.settings=L
}(jQuery,document,this));
(function(a){
	a.fn.twitterfeed=function(e,b,c){
		var d={
			limit:10,header:true,tweeticon:true,tweetname:true,tweettime:true,retweets:true,replies:true,showerror:true,ssl:false
		};
		var b=a.extend(d,b);
		return this.each(function(j,m){
			var h=a(m);
			var l="";
			if(!h.hasClass("twitterFeed")){
				h.addClass("twitterFeed")
			}
			if(e==null){
				return false
			}
			if(b.limit>200){
				b.limit=200
			}
			if(b.ssl){
				l="s"
			}
			if(b.replies==true){
				b.replies=false
			}
			else{
				b.replies=true
			}
			var g="http"+l+"://api.twitter.com/1/statuses/user_timeline.json?include_rts="+b.retweets+"&exclude_replies="+b.replies+"&screen_name="+e+"&count="+b.limit;
			var n={};
			n.count=b.limit;
			a.ajax({
				url:g,data:n,dataType:"jsonp",success:function(i){
					k(m,i,b);
					if(a.isFunction(c)){
						c.call(this,h)
					}
				}
				,error:function(i){
					if(b.showerror){
						h.html("<p>Twitter request failed</p>")
					}
				}
			});
			var k=function(s,z,B){
				if(!z){
					return false
				}
				var q="";
				var A="odd";
				if(B.header){
					var o=z[0].user.name;
					var w=z[0].user.screen_name;
					var v=z[0].user.profile_image_url;
					var u='<a href="http://twitter.com/'+w+'/" title="Visit '+o+' on Twitter">';
					q+='<div class="twitterHeader">'+u+'<img src="'+v+'" alt="'+o+'" /></a><span>'+u+o+"</a></span></div>"
				}
				q+='<div class="twitterBody"><ul>';
				for(var p=0;p<z.length;p++){
					if(z[p].retweeted_status){
						var t=z[p].retweeted_status
					}
					else{
						var t=z[p]
					}
					var u='<a href="http://twitter.com/'+t.user.screen_name+'/" title="Visit '+t.user.name+' on Twitter" target="_blank">';
					q+='<li class="twitterRow '+A+'">';
					if(B.tweeticon){
						var v=t.user.profile_image_url;
						var y='<a href="http://twitter.com/'+t.user.screen_name+'/" target="_blank" title="Visit '+t.user.name+' on Twitter" style="background-image:url(\''+v+'\');" class="twitter-icon">'+t.user.name+"</a>";
						q+=y
					}
					if(B.tweetname){
						var o=t.user.name;
						q+='<div class="tweetName">'+u+o+"</a></div>"
					}
					if(B.tweettime){
						var r=f(t.created_at);
						q+='<div class="tweetTime">'+r+"</div>"
					}
					var x=t.text.replace(/(https?:\/\/[-_.!~*\'()a-zA-Z0-9;\/?:\@&=+\$,%#]+)/,function(C){
						var i=(C.length>30)?C.substr(0,30)+"...":C;
						return'<a href="'+C+'" title="Click to view this link" target="_blank">'+i+"</a>"
					}).replace(/@([a-zA-Z0-9_]+)/g,'@<a href="http://twitter.com/$1" title="Click to view $1 on Twitter" target="_blank">$1</a>').replace(/(?:^|\s)#([^\s\.\+:!]+)/g,function(i,C){
						return' <a href="http://twitter.com/search?q='+encodeURIComponent(C)+'" title="Click to view this on Twitter" target="_blank">#'+C+"</a>"
					});
					q+="<p>"+x+"</p>";
					q+="</li>";
					if(A=="odd"){
						A="even"
					}
					else{
						A="odd"
					}
				}
				q+="</ul></div>";
				a(s).html(q)
			};
			var f=function(p){
				p=Date.parse(p.replace(/^([a-z]{3})( [a-z]{3} \d\d?)(.*)( \d{4})$/i,"$1,$2$4$3"));
				var o=new Date();
				var i=new Date(p);
				var q=Math.round((o.getTime()-i.getTime())/1000);
				if(q<60){
					return"< 1m"
				}
				else{
					if(q<(60*60)){
						return(Math.round(q/60)-1)+"m"
					}
					else{
						if(q<(24*60*60)){
							return(Math.round(q/3600)-1)+"h"
						}
						else{
							if(q<(7*24*60*60)){
								return(Math.round(q/86400)-1)+"d"
							}
							else{
								return(Math.round(q/604800)-1)+"w"
							}
						}
					}
				}
			}
		})
	}
})(jQuery);
(function(a){
	a.fn.vTicker=function(b){
		var c={
			speed:700,pause:5000,showItems:3,animation:"",mousePause:true,isPaused:false
		};
		var b=a.extend(c,b);
		moveUp=function(g,d,e){
			if(e){
				return
			}
			var f=g.children("ul");
			first=f.children("li:first").clone(true);
			f.animate({
				top:"-="+d+"px"
			}
			,b.speed,function(){
				a(this).children("li:first").remove();
				a(this).css("top","0px")
			});
			if(b.animation=="fade"){
				f.children("li:first").fadeOut(b.speed);
				f.children("li:last").hide().fadeIn(b.speed)
			}
			first.appendTo(f)
		};
		return this.each(function(){
			var g=a(this);
			var f=0;
			var e=b.isPaused;
			g.css({
				overflow:"hidden",position:"relative"
			}).children("ul").css({
				position:"absolute",margin:0,padding:0
			}).children("li").css({
				margin:0,padding:0
			});
			g.children("ul").children("li").each(function(){
				if(a(this).height()>f){
					f=a(this).height()
				}
			});
			g.children("ul").children("li").each(function(){
				a(this).height(f)
			});
			g.height(f*b.showItems);
			var d=setInterval(function(){
				moveUp(g,f,e)
			}
			,b.pause);
			if(b.mousePause){
				g.bind("mouseenter",function(){
					e=true
				}).bind("mouseleave",function(){
					e=false
				})
			}
		})
	}
})(jQuery);
(function(a){
	a.fn.autofill=function(b){
		var c={
			value:"First Name",prePopulate:"",defaultTextColor:"#666",activeTextColor:"#333"
		};
		var b=a.extend(c,b);
		return this.each(function(){
			var e=a(this);
			var d=(e.attr("type")=="password");
			var f=false;
			if(d){
				e.hide();
				e.after('<input type="text" id="'+this.id+'_autofill" class="'+a(this).attr("class")+'" />');
				f=e;
				e=e.next()
			}
			if(document.activeElement!=e[0]){
				e.css({
					color:b.defaultTextColor
				}).val(b.value)
			}
			e.each(function(){
				a(this.form).submit(function(){
					if(e.val()==b.value){
						e.val(b.prePopulate)
					}
				})
			});
			e.focus(function(){
				if(e.val()==b.value){
					if(d){
						e.hide();
						f.show().focus()
					}
					e.val(b.prePopulate).css({
						color:b.activeTextColor
					})
				}
			}).blur(function(){
				if(e.val()==b.prePopulate||e.val()==""){
					e.css({
						color:b.defaultTextColor
					}).val(b.value)
				}
			});
			if(f&&f.length>0){
				f.blur(function(){
					if(f.val()==""){
						f.hide();
						e.show().css({
							color:b.defaultTextColor
						}).val(b.value)
					}
				})
			}
		})
	}
})(jQuery);
(function(f){
	function b(){
		this.regional=[];
		this.regional[""]={
			labels:["Years","Months","Weeks","Days","Hours","Minutes","Seconds"],labels1:["Year","Month","Week","Day","Hour","Minute","Second"],compactLabels:["y","m","w","d"],whichLabels:null,digits:["0","1","2","3","4","5","6","7","8","9"],timeSeparator:":",isRTL:false
		};
		this._defaults={
			until:null,since:null,timezone:null,serverSync:null,format:"dHMS",layout:"",compact:false,significant:0,description:"",expiryUrl:"",expiryText:"",alwaysExpire:false,onExpiry:null,onTick:null,tickInterval:1
		};
		f.extend(this._defaults,this.regional[""]);
		this._serverSyncs=[];
		function n(p){
			var q=(p<1000000000000?(q=performance.now?(performance.now()+performance.timing.navigationStart):Date.now()):p||new Date().getTime());
			if(q-m>=1000){
				g._updateTargets();
				m=q
			}
			o(n)
		}
		var o=window.requestAnimationFrame||window.webkitRequestAnimationFrame||window.mozRequestAnimationFrame||window.oRequestAnimationFrame||window.msRequestAnimationFrame||null;
		var m=0;
		if(!o||f.noRequestAnimationFrame){
			f.noRequestAnimationFrame=null;
			setInterval(function(){
				g._updateTargets()
			}
			,980)
		}
		else{
			m=window.animationStartTime||window.webkitAnimationStartTime||window.mozAnimationStartTime||window.oAnimationStartTime||window.msAnimationStartTime||new Date().getTime();
			o(n)
		}
	}
	var c=0;
	var h=1;
	var d=2;
	var a=3;
	var l=4;
	var i=5;
	var e=6;
	f.extend(b.prototype,{
		markerClassName:"hasCountdown",propertyName:"countdown",_rtlClass:"countdown_rtl",_sectionClass:"countdown_section",_amountClass:"countdown_amount",_rowClass:"countdown_row",_holdingClass:"countdown_holding",_showClass:"countdown_show",_descrClass:"countdown_descr",_timerTargets:[],setDefaults:function(m){
			this._resetExtraLabels(this._defaults,m);
			f.extend(this._defaults,m||{})
		}
		,UTCDate:function(o,s,q,t,u,n,p,m){
			if(typeof s=="object"&&s.constructor==Date){
				m=s.getMilliseconds();
				p=s.getSeconds();
				n=s.getMinutes();
				u=s.getHours();
				t=s.getDate();
				q=s.getMonth();
				s=s.getFullYear()
			}
			var r=new Date();
			r.setUTCFullYear(s);
			r.setUTCDate(1);
			r.setUTCMonth(q||0);
			r.setUTCDate(t||1);
			r.setUTCHours(u||0);
			r.setUTCMinutes((n||0)-(Math.abs(o)<30?o*60:o));
			r.setUTCSeconds(p||0);
			r.setUTCMilliseconds(m||0);
			return r
		}
		,periodsToSeconds:function(m){
			return m[0]*31557600+m[1]*2629800+m[2]*604800+m[3]*86400+m[4]*3600+m[5]*60+m[6]
		}
		,_attachPlugin:function(o,m){
			o=f(o);
			if(o.hasClass(this.markerClassName)){
				return
			}
			var n={
				options:f.extend({},this._defaults),_periods:[0,0,0,0,0,0,0]
			};
			o.addClass(this.markerClassName).data(this.propertyName,n);
			this._optionPlugin(o,m)
		}
		,_addTarget:function(m){
			if(!this._hasTarget(m)){
				this._timerTargets.push(m)
			}
		}
		,_hasTarget:function(m){
			return(f.inArray(m,this._timerTargets)>-1)
		}
		,_removeTarget:function(m){
			this._timerTargets=f.map(this._timerTargets,function(n){
				return(n==m?null:n)
			})
		}
		,_updateTargets:function(){
			for(var m=this._timerTargets.length-1;m>=0;m--){
				this._updateCountdown(this._timerTargets[m])
			}
		}
		,_optionPlugin:function(r,o,q){
			r=f(r);
			var p=r.data(this.propertyName);
			if(!o||(typeof o=="string"&&q==null)){
				var n=o;
				o=(p||{}).options;
				return(o&&n?o[n]:o)
			}
			if(!r.hasClass(this.markerClassName)){
				return
			}
			o=o||{};
			if(typeof o=="string"){
				var n=o;
				o={};
				o[n]=q
			}
			this._resetExtraLabels(p.options,o);
			f.extend(p.options,o);
			this._adjustSettings(r,p);
			var m=new Date();
			if((p._since&&p._since<m)||(p._until&&p._until>m)){
				this._addTarget(r[0])
			}
			this._updateCountdown(r,p)
		}
		,_updateCountdown:function(r,q){
			var m=f(r);
			q=q||m.data(this.propertyName);
			if(!q){
				return
			}
			m.html(this._generateHTML(q)).toggleClass(this._rtlClass,q.options.isRTL);
			if(f.isFunction(q.options.onTick)){
				var p=q._hold!="lap"?q._periods:this._calculatePeriods(q,q._show,q.options.significant,new Date());
				if(q.options.tickInterval==1||this.periodsToSeconds(p)%q.options.tickInterval==0){
					q.options.onTick.apply(r,[p])
				}
			}
			var n=q._hold!="pause"&&(q._since?q._now.getTime()<q._since.getTime():q._now.getTime()>=q._until.getTime());
			if(n&&!q._expiring){
				q._expiring=true;
				if(this._hasTarget(r)||q.options.alwaysExpire){
					this._removeTarget(r);
					if(f.isFunction(q.options.onExpiry)){
						q.options.onExpiry.apply(r,[])
					}
					if(q.options.expiryText){
						var o=q.options.layout;
						q.options.layout=q.options.expiryText;
						this._updateCountdown(r,q);
						q.options.layout=o
					}
					if(q.options.expiryUrl){
						window.location=q.options.expiryUrl
					}
				}
				q._expiring=false
			}
			else{
				if(q._hold=="pause"){
					this._removeTarget(r)
				}
			}
			m.data(this.propertyName,q)
		}
		,_resetExtraLabels:function(p,m){
			var o=false;
			for(var q in m){
				if(q!="whichLabels"&&q.match(/[Ll]abels/)){
					o=true;
					break
				}
			}
			if(o){
				for(var q in p){
					if(q.match(/[Ll]abels[02-9]/)){
						p[q]=null
					}
				}
			}
		}
		,_adjustSettings:function(t,s){
			var o;
			var n=0;
			var m=null;
			for(var q=0;q<this._serverSyncs.length;q++){
				if(this._serverSyncs[q][0]==s.options.serverSync){
					m=this._serverSyncs[q][1];
					break
				}
			}
			if(m!=null){
				n=(s.options.serverSync?m:0);
				o=new Date()
			}
			else{
				var p=(f.isFunction(s.options.serverSync)?s.options.serverSync.apply(t,[]):null);
				o=new Date();
				n=(p?o.getTime()-p.getTime():0);
				this._serverSyncs.push([s.options.serverSync,n])
			}
			var r=s.options.timezone;
			r=(r==null?-o.getTimezoneOffset():r);
			s._since=s.options.since;
			if(s._since!=null){
				s._since=this.UTCDate(r,this._determineTime(s._since,null));
				if(s._since&&n){
					s._since.setMilliseconds(s._since.getMilliseconds()+n)
				}
			}
			s._until=this.UTCDate(r,this._determineTime(s.options.until,o));
			if(n){
				s._until.setMilliseconds(s._until.getMilliseconds()+n)
			}
			s._show=this._determineShow(s)
		}
		,_destroyPlugin:function(m){
			m=f(m);
			if(!m.hasClass(this.markerClassName)){
				return
			}
			this._removeTarget(m[0]);
			m.removeClass(this.markerClassName).empty().removeData(this.propertyName)
		}
		,_pausePlugin:function(m){
			this._hold(m,"pause")
		}
		,_lapPlugin:function(m){
			this._hold(m,"lap")
		}
		,_resumePlugin:function(m){
			this._hold(m,null)
		}
		,_hold:function(p,o){
			var n=f.data(p,this.propertyName);
			if(n){
				if(n._hold=="pause"&&!o){
					n._periods=n._savePeriods;
					var m=(n._since?"-":"+");
					n[n._since?"_since":"_until"]=this._determineTime(m+n._periods[0]+"y"+m+n._periods[1]+"o"+m+n._periods[2]+"w"+m+n._periods[3]+"d"+m+n._periods[4]+"h"+m+n._periods[5]+"m"+m+n._periods[6]+"s");
					this._addTarget(p)
				}
				n._hold=o;
				n._savePeriods=(o=="pause"?n._periods:null);
				f.data(p,this.propertyName,n);
				this._updateCountdown(p,n)
			}
		}
		,_getTimesPlugin:function(n){
			var m=f.data(n,this.propertyName);
			return(!m?null:(!m._hold?m._periods:this._calculatePeriods(m,m._show,m.options.significant,new Date())))
		}
		,_determineTime:function(p,m){
			var o=function(s){
				var r=new Date();
				r.setTime(r.getTime()+s*1000);
				return r
			};
			var n=function(v){
				v=v.toLowerCase();
				var s=new Date();
				var z=s.getFullYear();
				var x=s.getMonth();
				var A=s.getDate();
				var u=s.getHours();
				var t=s.getMinutes();
				var r=s.getSeconds();
				var y=/([+-]?[0-9]+)\s*(s|m|h|d|w|o|y)?/g;
				var w=y.exec(v);
				while(w){
					switch(w[2]||"s"){
						case"s":r+=parseInt(w[1],10);
						break;
						case"m":t+=parseInt(w[1],10);
						break;
						case"h":u+=parseInt(w[1],10);
						break;
						case"d":A+=parseInt(w[1],10);
						break;
						case"w":A+=parseInt(w[1],10)*7;
						break;
						case"o":x+=parseInt(w[1],10);
						A=Math.min(A,g._getDaysInMonth(z,x));
						break;
						case"y":z+=parseInt(w[1],10);
						A=Math.min(A,g._getDaysInMonth(z,x));
						break
					}
					w=y.exec(v)
				}
				return new Date(z,x,A,u,t,r,0)
			};
			var q=(p==null?m:(typeof p=="string"?n(p):(typeof p=="number"?o(p):p)));
			if(q){
				q.setMilliseconds(0)
			}
			return q
		}
		,_getDaysInMonth:function(m,n){
			return 32-new Date(m,n,32).getDate()
		}
		,_normalLabels:function(m){
			return m
		}
		,_generateHTML:function(q){
			var x=this;
			q._periods=(q._hold?q._periods:this._calculatePeriods(q,q._show,q.options.significant,new Date()));
			var u=false;
			var n=0;
			var p=q.options.significant;
			var w=f.extend({},q._show);
			for(var t=c;t<=e;t++){
				u|=(q._show[t]=="?"&&q._periods[t]>0);
				w[t]=(q._show[t]=="?"&&!u?null:q._show[t]);
				n+=(w[t]?1:0);
				p-=(q._periods[t]>0?1:0)
			}
			var v=[false,false,false,false,false,false,false];
			for(var t=e;t>=c;t--){
				if(q._show[t]){
					if(q._periods[t]){
						v[t]=true
					}
					else{
						v[t]=p>0;
						p--
					}
				}
			}
			var r=(q.options.compact?q.options.compactLabels:q.options.labels);
			var m=q.options.whichLabels||this._normalLabels;
			var s=function(z){
				var y=q.options["compactLabels"+m(q._periods[z])];
				return(w[z]?x._translateDigits(q,q._periods[z])+(y?y[z]:r[z])+" ":"")
			};
			var o=function(z){
				var y=q.options["labels"+m(q._periods[z])];
				return((!q.options.significant&&w[z])||(q.options.significant&&v[z])?'<span class="'+g._sectionClass+'"><span class="'+g._amountClass+'">'+x._translateDigits(q,q._periods[z])+"</span><br/>"+(y?y[z]:r[z])+"</span>":"")
			};
			return(q.options.layout?this._buildLayout(q,w,q.options.layout,q.options.compact,q.options.significant,v):((q.options.compact?'<span class="'+this._rowClass+" "+this._amountClass+(q._hold?" "+this._holdingClass:"")+'">'+s(c)+s(h)+s(d)+s(a)+(w[l]?this._minDigits(q,q._periods[l],2):"")+(w[i]?(w[l]?q.options.timeSeparator:"")+this._minDigits(q,q._periods[i],2):"")+(w[e]?(w[l]||w[i]?q.options.timeSeparator:"")+this._minDigits(q,q._periods[e],2):""):'<span class="'+this._rowClass+" "+this._showClass+(q.options.significant||n)+(q._hold?" "+this._holdingClass:"")+'">'+o(c)+o(h)+o(d)+o(a)+o(l)+o(i)+o(e))+"</span>"+(q.options.description?'<span class="'+this._rowClass+" "+this._descrClass+'">'+q.options.description+"</span>":"")))
		}
		,_buildLayout:function(r,y,t,v,z,x){
			var s=r.options[v?"compactLabels":"labels"];
			var n=r.options.whichLabels||this._normalLabels;
			var m=function(B){
				return(r.options[(v?"compactLabels":"labels")+n(r._periods[B])]||s)[B]
			};
			var w=function(C,B){
				return r.options.digits[Math.floor(C/B)%10]
			};
			var o={
				desc:r.options.description,sep:r.options.timeSeparator,yl:m(c),yn:this._minDigits(r,r._periods[c],1),ynn:this._minDigits(r,r._periods[c],2),ynnn:this._minDigits(r,r._periods[c],3),y1:w(r._periods[c],1),y10:w(r._periods[c],10),y100:w(r._periods[c],100),y1000:w(r._periods[c],1000),ol:m(h),on:this._minDigits(r,r._periods[h],1),onn:this._minDigits(r,r._periods[h],2),onnn:this._minDigits(r,r._periods[h],3),o1:w(r._periods[h],1),o10:w(r._periods[h],10),o100:w(r._periods[h],100),o1000:w(r._periods[h],1000),wl:m(d),wn:this._minDigits(r,r._periods[d],1),wnn:this._minDigits(r,r._periods[d],2),wnnn:this._minDigits(r,r._periods[d],3),w1:w(r._periods[d],1),w10:w(r._periods[d],10),w100:w(r._periods[d],100),w1000:w(r._periods[d],1000),dl:m(a),dn:this._minDigits(r,r._periods[a],1),dnn:this._minDigits(r,r._periods[a],2),dnnn:this._minDigits(r,r._periods[a],3),d1:w(r._periods[a],1),d10:w(r._periods[a],10),d100:w(r._periods[a],100),d1000:w(r._periods[a],1000),hl:m(l),hn:this._minDigits(r,r._periods[l],1),hnn:this._minDigits(r,r._periods[l],2),hnnn:this._minDigits(r,r._periods[l],3),h1:w(r._periods[l],1),h10:w(r._periods[l],10),h100:w(r._periods[l],100),h1000:w(r._periods[l],1000),ml:m(i),mn:this._minDigits(r,r._periods[i],1),mnn:this._minDigits(r,r._periods[i],2),mnnn:this._minDigits(r,r._periods[i],3),m1:w(r._periods[i],1),m10:w(r._periods[i],10),m100:w(r._periods[i],100),m1000:w(r._periods[i],1000),sl:m(e),sn:this._minDigits(r,r._periods[e],1),snn:this._minDigits(r,r._periods[e],2),snnn:this._minDigits(r,r._periods[e],3),s1:w(r._periods[e],1),s10:w(r._periods[e],10),s100:w(r._periods[e],100),s1000:w(r._periods[e],1000)
			};
			var q=t;
			for(var p=c;p<=e;p++){
				var u="yowdhms".charAt(p);
				var A=new RegExp("\\{"+u+"<\\}(.*)\\{"+u+">\\}","g");
				q=q.replace(A,((!z&&y[p])||(z&&x[p])?"$1":""))
			}
			f.each(o,function(D,B){
				var C=new RegExp("\\{"+D+"\\}","g");
				q=q.replace(C,B)
			});
			return q
		}
		,_minDigits:function(o,n,m){
			n=""+n;
			if(n.length>=m){
				return this._translateDigits(o,n)
			}
			n="0000000000"+n;
			return this._translateDigits(o,n.substr(n.length-m))
		}
		,_translateDigits:function(n,m){
			return(""+m).replace(/[0-9]/g,function(o){
				return n.options.digits[o]
			})
		}
		,_determineShow:function(n){
			var o=n.options.format;
			var m=[];
			m[c]=(o.match("y")?"?":(o.match("Y")?"!":null));
			m[h]=(o.match("o")?"?":(o.match("O")?"!":null));
			m[d]=(o.match("w")?"?":(o.match("W")?"!":null));
			m[a]=(o.match("d")?"?":(o.match("D")?"!":null));
			m[l]=(o.match("h")?"?":(o.match("H")?"!":null));
			m[i]=(o.match("m")?"?":(o.match("M")?"!":null));
			m[e]=(o.match("s")?"?":(o.match("S")?"!":null));
			return m
		}
		,_calculatePeriods:function(p,D,t,n){
			p._now=n;
			p._now.setMilliseconds(0);
			var r=new Date(p._now.getTime());
			if(p._since){
				if(n.getTime()<p._since.getTime()){
					p._now=n=r
				}
				else{
					n=p._since
				}
			}
			else{
				r.setTime(p._until.getTime());
				if(n.getTime()>p._until.getTime()){
					p._now=n=r
				}
			}
			var m=[0,0,0,0,0,0,0];
			if(D[c]||D[h]){
				var y=g._getDaysInMonth(n.getFullYear(),n.getMonth());
				var z=g._getDaysInMonth(r.getFullYear(),r.getMonth());
				var s=(r.getDate()==n.getDate()||(r.getDate()>=Math.min(y,z)&&n.getDate()>=Math.min(y,z)));
				var C=function(F){
					return(F.getHours()*60+F.getMinutes())*60+F.getSeconds()
				};
				var u=Math.max(0,(r.getFullYear()-n.getFullYear())*12+r.getMonth()-n.getMonth()+((r.getDate()<n.getDate()&&!s)||(s&&C(r)<C(n))?-1:0));
				m[c]=(D[c]?Math.floor(u/12):0);
				m[h]=(D[h]?u-m[c]*12:0);
				n=new Date(n.getTime());
				var E=(n.getDate()==y);
				var q=g._getDaysInMonth(n.getFullYear()+m[c],n.getMonth()+m[h]);
				if(n.getDate()>q){
					n.setDate(q)
				}
				n.setFullYear(n.getFullYear()+m[c]);
				n.setMonth(n.getMonth()+m[h]);
				if(E){
					n.setDate(q)
				}
			}
			var x=Math.floor((r.getTime()-n.getTime())/1000);
			var o=function(G,F){
				m[G]=(D[G]?Math.floor(x/F):0);
				x-=m[G]*F
			};
			o(d,604800);
			o(a,86400);
			o(l,3600);
			o(i,60);
			o(e,1);
			if(x>0&&!p._since){
				var v=[1,12,4.3482,7,24,60,60];
				var w=e;
				var A=1;
				for(var B=e;B>=c;B--){
					if(D[B]){
						if(m[w]>=A){
							m[w]=0;
							x=1
						}
						if(x>0){
							m[B]++;
							x=0;
							w=B;
							A=1
						}
					}
					A*=v[B]
				}
			}
			if(t){
				for(var B=c;B<=e;B++){
					if(t&&m[B]){
						t--
					}
					else{
						if(!t){
							m[B]=0
						}
					}
				}
			}
			return m
		}
	});
	var j=["getTimes"];
	function k(n,m){
		if(n=="option"&&(m.length==0||(m.length==1&&typeof m[0]=="string"))){
			return true
		}
		return f.inArray(n,j)>-1
	}
	f.fn.countdown=function(n){
		var m=Array.prototype.slice.call(arguments,1);
		if(k(n,m)){
			return g["_"+n+"Plugin"].apply(g,[this[0]].concat(m))
		}
		return this.each(function(){
			if(typeof n=="string"){
				if(!g["_"+n+"Plugin"]){
					throw"Unknown command: "+n
				}
				g["_"+n+"Plugin"].apply(g,[this].concat(m))
			}
			else{
				g._attachPlugin(this,n||{})
			}
		})
	};
	var g=f.countdown=new b()
})(jQuery);
$(document).ready(function(){
	var e="September 31, 2013, 00:00:00",d="aurevilly";
	var g="请输入有效邮箱",f="请输入姓名",j="请输入留言",i="姓名*",c="你的邮箱 *",h="留言 *";
	$(function(){
		$("a[rel*=external], #social a").click(function(){
			window.open(this.href);
			return false
		})
	});
	$("#ticker").twitterfeed(d,{
		header:false,tweettime:false
	}
	,function(k){
		$(k).find("div.twitterBody").vTicker({
			showItems:1,speed:700,pause:4200
		})
	});
	$("#countdown").countdown({
		until:new Date(e),layout:'<div class="two columns alpha"><strong>{dn}</strong> {dl}</div> <div class="two columns"><strong>{hn}</strong> {hl}</div> <div class="two columns"><strong>{mn}</strong> {ml}</div> <div class="two columns omega"><strong>{sn}</strong> {sl}</div>'
	});
	$('#newsletter input[type="submit"]').click(function(){
		$(".error").remove();
		if(!b($('#newsletter input[type="text"]'))){
			$("#newsletter form").after('<div id="response"><span class="error">'+g+"</span></div>");
			$("#newsletter .error").hide().fadeIn("slow");
			return false
		}
	});
	$("#social li a, #contact a").hover(function(){
		var k=this;
		$(k).stop().animate({
			marginTop:"-8px",paddingBottom:"8px"
		}
		,250,function(){
			$(k).animate({
				marginTop:"-4px",paddingBottom:"4px"
			}
			,250)
		})
	}
	,function(){
		var k=this;
		$(k).stop().animate({
			marginTop:"0px",paddingBottom:"0px"
		}
		,250,function(){
			$(k).animate({
				marginTop:"0px",paddingBottom:"0px"
			}
			,250)
		})
	});
	$("#contact a").colorbox({
		transition:"fade",inline:true,scrolling:false,initialWidth:340,width:340,close:"&#x2715;",onCleanup:function(){
			$("#contact-form #response").hide().html("");
			$.colorbox.resize()
		}
	});
	$("#contact-form #name").autofill({
		value:i
	});
	$("#contact-form #email, #email-newsletter").autofill({
		value:c
	});
	$("#contact-form #comments").autofill({
		value:h
	});
	$("#submit-contact button").click(function(n){
		$("#contact-form #response").hide();
		$(this).parent().append('<img src="'+PUBLIC+'/maintain/assets/images/loading.gif" class="loading" alt="Loading..." />');
		var l=$("#contact-form #name").val();
		var k=$("#contact-form #email").val();
		var o=$("#contact-form #comments").val();
		var m=$("#contact-form #token").val();
		if(l==i){
			a(f);
			return false
		}
		else{
			if(!b($("#contact-form #email"))){
				a(g);
				return false
			}
			else{
				if(o==h){
					a(j);
					return false
				}
			}
		}
		$.ajax({
			type:"GET",
			url:"Maintain/message",
			data:"name="+l+"&email="+k+"&comments="+o,
			dataType: 'text',//返回的内容的类型，由于PHP文件是直接echo的，那么这里就是text
			timeout: 1000,//超时时间
			success:function(p){
				//alert(p.toSource());
				

				var dataObj=eval("("+p+")");
				
				
				a(dataObj['data'],"success");
				
				$("#cboxOverlay").fadeOut(2500);
				$("#colorbox").fadeOut(3500);
				
				//a("提交成功","success");
			},error:function(){
				a("提交失败","error");
			}
		});
		n.preventDefault()
	});
	function b(l){
		var k=false;
		var n=/^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
		var m=l.val();
		if(!n.test(m)){
			return false
		}
		else{
			return true
		}
	}
	function a(l,k){
		if(!k){
			var k="error"
		}
		$("#contact-form img.loading").fadeOut(1000);
		$("#contact-form #response").hide().html('<span class="'+k+'">'+l+"</span>").fadeIn("slow");
		$("#contact a").colorbox.resize()
	}
	$("#switch .background a").click(function(){
		$("body").attr("class",$(this).attr("id"));
		$(".background .current").removeClass("current");
		$(this).toggleClass("current")
	});
	$("#switch .color a").click(function(){
		$("#theme").attr({
			href:PUBLIC+"/maintain/assets/stylesheets/themes/"+$(this).attr("id")+".css"
		});
		$(".color .current").removeClass("current");
		$(this).toggleClass("current")
	});
	$("#show").css({
		"margin-left":"-200px"
	});
	$("#hide, #show").click(function(){
		if($("#switch").is(":visible")){
			$("#switch").animate({
				"margin-left":"-200px"
			}
			,1000,function(){
				$(this).hide()
			});
			$("#show").animate({
				"margin-left":"0px"
			}
			,1000).show()
		}
		else{
			$("#show").animate({
				"margin-left":"-200px"
			}
			,1000,function(){
				$(this).hide()
			});
			$("#switch").show().animate({
				"margin-left":"0"
			}
			,1000)
		}
	})
});