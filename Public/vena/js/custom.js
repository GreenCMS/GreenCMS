/*-----------------------------------------------------------------------------------
/* Custom Scripts
-----------------------------------------------------------------------------------*/

/* ----------------- Start Document ----------------- */
(function($){
	$(document).ready(function(){

/*----------------------------------------------------*/
/*	Revolution Slider
/*----------------------------------------------------*/

	if ($.fn.cssOriginal != undefined) {
		$.fn.css = $.fn.cssOriginal;
	}

	$('.fullwidthbanner').revolution({
		delay: 8000,
		startwidth: 1200,
		startheight: 540,
		onHoverStop: "on", // Stop Banner Timet at Hover on Slide on/off
		navigationType: "none", //bullet, none
		navigationArrows: "verticalcentered", //nexttobullets, verticalcentered, none
		navigationStyle: "none", //round, square, navbar, none
		touchenabled: "on", // Enable Swipe Function : on/off
		navOffsetHorizontal: 0,
		navOffsetVertical: 20,
		stopAtSlide: -1, // Stop Timer if Slide "x" has been Reached. If stopAfterLoops set to 0, then it stops already in the first Loop at slide X which defined. -1 means do not stop at any slide. stopAfterLoops has no sinn in this case.
		stopAfterLoops: -1, // Stop Timer if All slides has been played "x" times. IT will stop at THe slide which is defined via stopAtSlide:x, if set to -1 slide never stop automatic
		fullWidth: "on",
	});


/*----------------------------------------------------*/
/*	Carousel
/*----------------------------------------------------*/
// Add classes for other carousels
var $carousel = $('.recent-work-jc');

var scrollCount;

function adjustScrollCount() {
	if( $(window).width() < 768 ) {
		scrollCount = 1;
	} else {
		scrollCount = 3;
	}

}

function adjustCarouselHeight() {

	$carousel.each(function() {
		var $this    = $(this);
		var maxHeight = -1;
		$this.find('li').each(function() {
			maxHeight = maxHeight > $(this).height() ? maxHeight : $(this).height();
		});
		$this.height(maxHeight);
	});
}
function initCarousel() {
	adjustCarouselHeight();
	adjustScrollCount();
	var i = 0;
	var g = {};
	$carousel.each(function() {
		i++;

		var $this = $(this);
		g[i] = $this.jcarousel({
			animation           : 600,
			scroll              : scrollCount
		});
		$this.jcarousel('scroll', 0);
		 $this.prev().find('.jcarousel-prev').bind('active.jcarouselcontrol', function() {
			$(this).addClass('active');
		}).bind('inactive.jcarouselcontrol', function() {
			$(this).removeClass('active');
		}).jcarouselControl({
			target: '-='+scrollCount,
			carousel: g[i]
		});

		$this.prev().find('.jcarousel-next').bind('active.jcarouselcontrol', function() {
			$(this).addClass('active');
		}).bind('inactive.jcarouselcontrol', function() {
			$(this).removeClass('active');
		}).jcarouselControl({
			target: '+='+scrollCount,
			carousel: g[i]
		});

		$this.touchwipe({
		wipeLeft: function() {
			$this.jcarousel('scroll','+='+scrollCount);
		},
		wipeRight: function() {
			$this.jcarousel('scroll','-='+scrollCount);
		}
	});

	});
}
$(window).load(function(){
	initCarousel();
});

$(window).resize(function () {
	$carousel.each(function() {
		var $this = $(this);
		$this.jcarousel('destroy');
	});
	initCarousel();
});


/*----------------------------------------------------*/
/*	Skill Bars
/*----------------------------------------------------*/

if($('#skillzz').length != 0){
    var skillbar_active = false;

    if($(window).scrollTop() == 0 && isScrolledIntoView($('#skillzz')) == true){
        skillbarActive();
        skillbar_active = true;
    }
    $(window).bind('scroll', function(){
        if(skillbar_active === false && isScrolledIntoView($('#skillzz')) == true ){
            skillbarActive();
            skillbar_active = true;
        }
    });
}


/*----------------------------------------------------*/
/*	Toggle (FAQ)
/*----------------------------------------------------*/
jQuery(document).ready(function($) { 
 
    // Find the toggles and hide their content
    $('.toggle').each(function(){
        $(this).find('.toggle-content').hide();
    });
 
    // When a toggle is clicked (activated) show their content
    $('.toggle a.toggle-trigger').click(function(){
        var el = $(this), parent = el.closest('.toggle');
 
        if( el.hasClass('active') )
        {
            parent.find('.toggle-content').slideToggle();
            el.removeClass('active');
        }
        else
        {
            parent.find('.toggle-content').slideToggle();
            el.addClass('active');
        }
        return false;
    });
 
});  //End

/*----------------------------------------------------*/
/*	Isotope Portfolio Filter
/*----------------------------------------------------*/
	$(window).load(function() {

		var $container = $('#portfolio-list');

		$container.isotope({
			itemSelector: '.item',
			layoutMode: 'masonry'
		});

		var $optionSets = $('.option-set'),
				$optionLinks = $optionSets.find('li');

		$optionLinks.click(function() {
			var $this = $(this);
			// don't proceed if already selected
			if ($this.hasClass('selected')) {
				return false;
			}
			var $optionSet = $this.parents('.option-set');
			$optionSet.find('.selected').removeClass('selected');
			$this.addClass('selected');
		});

		$('#filters').on('click', 'a', function() {
			var selector = $(this).data('filter');
			$container.isotope({filter: selector});

		});

	});
	
/*----------------------------------------------------*/
/*	Swipebox Lightbox Plugin
/*----------------------------------------------------*/	
	jQuery(function($) {
		$(".swipebox").swipebox({
			hideBarsDelay : 0 // 0 to always show caption and action bar
		});
	});
	
/*----------------------------------------------------*/
/*	Testimonials Rotator
/*----------------------------------------------------*/
	$(document).ready(function() {
		
		$('.quote').quovolver();
		
	});
	
/*----------------------------------------------------*/
/*	Notification Boxes
/*----------------------------------------------------*/
	$(document).ready(function(){
		$('.close').click(function(){
			$(this).parent().fadeOut('slow');
		});
	});
	
/*----------------------------------------------------*/
/*	Tabs
/*----------------------------------------------------*/
$(document).ready(function(){    
	$("#tabs li").click(function() { 
	
		var this_tmp = $(this);  
		
		$("#tabs li").removeClass('active');   
		$(this).addClass("active");        
		$(".tab_content:visible").fadeOut(300, function(){            
			
			var selected_tab = this_tmp.find("a").attr("href");            
			$(selected_tab).fadeIn(300);        
		});  
		
		return false;    
	});
});

/*----------------------------------------------------*/
/*	Vertical Multi Menu
/*----------------------------------------------------*/

$(document).ready(function(){
	$(function() {
	    var itemsList = $('.vertical-menu > li > ul'),
	        itemLink = $('.vertical-menu > li > a');  
	        expandedItem = $('.vertical-menu > li > ul.expand');  
	    
	    itemsList.hide();
		expandedItem.show();
	    itemLink.click(function(e) {
	        e.preventDefault();
	        if(!$(this).hasClass('active')) {
	            itemLink.removeClass('active');
	            itemsList.filter(':visible').slideUp('normal');
	            $(this).addClass('active').next().stop(true,true).slideDown('normal');
	        } else {
	            $(this).removeClass('active');
	            $(this).next().stop(true,true).slideUp('normal');
	        }
	    });
	});
});

/*----------------------------------------------------*/
/*	Accordions with contents
/*----------------------------------------------------*/
$(document).ready(function(){
	$(function() {
	    var accList = $('.accordion li p'),
	        accLink = $('.accordion li a'); 
			accExpanded = $('.accordion li p.expand');			
	    
	    accList.hide();
		accExpanded.show();
	    accLink.click(function(e) {
	        e.preventDefault();
	        if(!$(this).hasClass('active')) {
	            accLink.removeClass('active');
	            accList.filter(':visible').slideUp('normal');
	            $(this).addClass('active').next().stop(true,true).slideDown('normal');
	        } else {
	            $(this).removeClass('active');
	            $(this).next().stop(true,true).slideUp('normal');
	        }
	    });
	});
});

/*----------------------------------------------------*/
/*	Swipe Slider 
/*----------------------------------------------------*/
window.mySwipe = new Swipe(document.getElementById('slider'), {
  startSlide: 2,
  speed: 400,
  auto: 3000,
  continuous: true,
  disableScroll: false,
  stopPropagation: false,
  callback: function(index, elem) {},
  transitionEnd: function(index, elem) {}
});


/*----------------------------------------------------*/
/*	Tooltips
/*----------------------------------------------------*/


$('.tooltip').tooltipster({
   animation: 'fade',
   arrow: true,
   arrowColor: '',
   content: '',
   delay: 200,
   fixedWidth: 0,
   maxWidth: 0,
   functionBefore: function(origin, continueTooltip) {
      continueTooltip();
   },
   functionReady: function(origin, tooltip) {},
   functionAfter: function(origin) {},
   icon: '(?)',
   iconDesktop: false,
   iconTouch: false,
   iconTheme: '.tooltipster-icon',
   interactive: false,
   interactiveTolerance: 350,
   offsetX: 0,
   offsetY: 0,
   onlyOne: true,
   position: 'top',
   speed: 350,
   timer: 0,
   theme: '.tooltipster-default',
   touchDevices: true,
   trigger: 'hover',
   updateAnimation: true
});



/*----------------------------------------------------*/
/*	Responsive Menu
/*----------------------------------------------------*/

var jPanelMenu = {};
$(function() {
	$('pre').each(function(i, e) {hljs.highlightBlock(e)});
	
	jPanelMenu = $.jPanelMenu({
		menu: '.navigation',
		animated: false
	});
	jPanelMenu.on();

	$(document).on('click',jPanelMenu.menu + ' li a',function(e){
		if ( jPanelMenu.isOpen() && $(e.target).attr('href').substring(0,1) == '#' ) { jPanelMenu.close(); }
	});

	$(document).on('click','#trigger-off',function(e){
		jPanelMenu.off();
		$('html').css('padding-top','40px');
		$('#trigger-on').remove();
		$('body').append('<a href="" title="Re-Enable jPanelMenu" id="trigger-on">Re-Enable jPanelMenu</a>');
		e.preventDefault();
	});

	$(document).on('click','#trigger-on',function(e){
		jPanelMenu.on();
		$('html').css('padding-top',0);
		$('#trigger-on').remove();
		e.preventDefault();
	});
});
/* ------------------ End Document ------------------ */
});

})(this.jQuery);

/**
 * jQuery Plugin to obtain touch gestures from iPhone, iPod Touch, iPad, and Android mobile phones
 * Common usage: wipe images (left and right to show the previous or next image)
 *
 * @author Andreas Waltl, netCU Internetagentur (http://www.netcu.de)
 */
(function($){$.fn.touchwipe=function(settings){var config={min_move_x:20,min_move_y:20,wipeLeft:function(){},wipeRight:function(){},wipeUp:function(){},wipeDown:function(){},preventDefaultEvents:true};if(settings)$.extend(config,settings);this.each(function(){var startX;var startY;var isMoving=false;function cancelTouch(){this.removeEventListener('touchmove',onTouchMove);startX=null;isMoving=false}function onTouchMove(e){if(config.preventDefaultEvents){e.preventDefault()}if(isMoving){var x=e.touches[0].pageX;var y=e.touches[0].pageY;var dx=startX-x;var dy=startY-y;if(Math.abs(dx)>=config.min_move_x){cancelTouch();if(dx>0){config.wipeLeft()}else{config.wipeRight()}}else if(Math.abs(dy)>=config.min_move_y){cancelTouch();if(dy>0){config.wipeDown()}else{config.wipeUp()}}}}function onTouchStart(e){if(e.touches.length==1){startX=e.touches[0].pageX;startY=e.touches[0].pageY;isMoving=true;this.addEventListener('touchmove',onTouchMove,false)}}if('ontouchstart'in document.documentElement){this.addEventListener('touchstart',onTouchStart,false)}});return this}})(jQuery);


function isScrolledIntoView(elem)
{
    var docViewTop = $(window).scrollTop();
    var docViewBottom = docViewTop + $(window).height();

    var elemTop = $(elem).offset().top;
    var elemBottom = elemTop + $(elem).height();

    return ((elemBottom <= (docViewBottom + $(elem).height())) && (elemTop >= (docViewTop - $(elem).height())));
}

function skillbarActive(){
    setTimeout(function(){
	
		$('.skill-bar-value').each(function() {
			$(this)
				.data("origWidth", $(this).width())
				.width(0)
				.animate({
					width: $(this).data("origWidth")
				}, 1200);
		});
		
		$('.skill-bar-value .dot').each(function() {
			var me = $(this);
			var perc = me.attr("data-percentage");

			var current_perc = 0;

			var progress = setInterval(function() {
				if (current_perc>=perc) {
					clearInterval(progress);
				} else {
					current_perc +=1;
					me.text((current_perc)+'%');
				}
				
			}, 10);
			
		});

	},10);
}