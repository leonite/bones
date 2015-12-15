/*
 * Main script file
 * Author: leonite <leonitebelov@gmail.com>
 *
 * This file should contain any js scripts you want to add to the site.
 * Instead of calling it in the header or throwing it inside wp_head()
 * this file will be called automatically in the footer so as not to
 * slow the page load.
 *
 * There are a lot of example functions and tools in here. If you don't
 * need any of it, just remove it. They are meant to be helpers and are
 * not required. It's your world baby, you can do whatever you want.
*/

( function( $ ) {
	

//skip link focus fix
( function() {
	var is_webkit = navigator.userAgent.toLowerCase().indexOf( 'webkit' ) > -1,
	    is_opera  = navigator.userAgent.toLowerCase().indexOf( 'opera' )  > -1,
	    is_ie     = navigator.userAgent.toLowerCase().indexOf( 'msie' )   > -1;

	if ( ( is_webkit || is_opera || is_ie ) && 'undefined' !== typeof( document.getElementById ) ) {
		var eventMethod = ( window.addEventListener ) ? 'addEventListener' : 'attachEvent';
		window[ eventMethod ]( 'hashchange', function() {
			var element = document.getElementById( location.hash.substring( 1 ) );

			if ( element ) {
				if ( ! /^(?:a|select|input|button|textarea)$/i.test( element.tagName ) )
					element.tabIndex = -1;

				element.focus();
			}
		}, false );
	}
})();


/*
 * Get Viewport Dimensions
 * returns object with viewport dimensions to match css in width and height properties
 * ( source: http://andylangton.co.uk/blog/development/get-viewport-size-width-and-height-javascript )
*/
function updateViewportDimensions() {
	var w=window,d=document,e=d.documentElement,g=d.getElementsByTagName('body')[0],x=w.innerWidth||e.clientWidth||g.clientWidth,y=w.innerHeight||e.clientHeight||g.clientHeight;
	return { width:x,height:y };
}
// setting the viewport width
var viewport = updateViewportDimensions();


/*
 * Throttle Resize-triggered Events
 * Wrap your actions in this function to throttle the frequency of firing them off, for better performance, esp. on mobile.
 * ( source: http://stackoverflow.com/questions/2854407/javascript-jquery-window-resize-how-to-fire-after-the-resize-is-completed )
*/
var waitForFinalEvent = (function () {
	var timers = {};
	return function (callback, ms, uniqueId) {
		if (!uniqueId) { uniqueId = "Don't call this twice without a uniqueId"; }
		if (timers[uniqueId]) { clearTimeout (timers[uniqueId]); }
		timers[uniqueId] = setTimeout(callback, ms);
	};
})();

// how long to wait before deciding the resize has stopped, in ms. Around 50-100 should work ok.
var timeToWaitForLast = 100;


/*
 * Here's an example so you can see how we're using the above function
 *
 * This is commented out so it won't work, but you can copy it and
 * remove the comments.
 *
 *
 *
 * If we want to only do it on a certain page, we can setup checks so we do it
 * as efficient as possible.
 *
 * if( typeof is_home === "undefined" ) var is_home = $('body').hasClass('home');
 *
 * This once checks to see if you're on the home page based on the body class
 * We can then use that check to perform actions on the home page only
 *
 * When the window is resized, we perform this function
 * $(window).resize(function () {
 *
 *    // if we're on the home page, we wait the set amount (in function above) then fire the function
 *    if( is_home ) { waitForFinalEvent( function() {
 *
 *	// update the viewport, in case the window size has changed
 *	viewport = updateViewportDimensions();
 *
 *      // if we're above or equal to 768 fire this off
 *      if( viewport.width >= 768 ) {
 *        console.log('On home page and window sized to 768 width or more.');
 *      } else {
 *        // otherwise, let's do this instead
 *        console.log('Not on home page, or window sized to less than 768.');
 *      }
 *
 *    }, timeToWaitForLast, "your-function-identifier-string"); }
 * });
 *
 * Pretty cool huh? You can create functions like this to conditionally load
 * content and other stuff dependent on the viewport.
 * Remember that mobile devices and javascript aren't the best of friends.
 * Keep it light and always make sure the larger viewports are doing the heavy lifting.
 *
*/

/*
 * We're going to swap out the gravatars.
 * In the functions.php file, you can see we're not loading the gravatar
 * images on mobile to save bandwidth. Once we hit an acceptable viewport
 * then we can swap out those images since they are located in a data attribute.
*/
function loadGravatars() {
  // set the viewport using the function above
  viewport = updateViewportDimensions();
  // if the viewport is tablet or larger, we load in the gravatars
  if (viewport.width >= 768) {
  $('.comment img[data-gravatar]').each(function(){
    $(this).attr('src',jQuery(this).attr('data-gravatar'));
  });
	}
} // end function


//показать/скрыть элемент
	function toggle_visibility( id ) {
		
		var e = document.getElementById( id );
		
		if( e.style.display == 'block' )
			e.style.display = 'none'; 
			else
			e.style.display = 'block';
			
	}
	
	
	
	


/*
 * Put all your regular jQuery in here.
*/
	$( document ).ready( function() {
		
		// set the viewport using the function above
		viewport = updateViewportDimensions();
		
		if (viewport.width >= 768) {
		
		$("#sidebar1").stick_in_parent({ "offset_top" : $("#header").height() } );
		
		}
	
	/*
	*
	*Equal height function
	*Uses for main page articles columns
	*
	*/
	
	equalheight = function(container) {

		var currentTallest = 0,
		currentRowStart = 0,
		rowDivs = new Array(),
		$el,
		topPosition = 0;
		
		$(container).each(function() {

			$el = $(this);
			$($el).height('auto')
			topPostion = $el.position().top;

			if (currentRowStart != topPostion) {
		
				for (currentDiv = 0 ; currentDiv < rowDivs.length ; currentDiv++) {
			
					rowDivs[currentDiv].height(currentTallest);
			
				}
     
				rowDivs.length = 0; // empty the array
				currentRowStart = topPostion;
				currentTallest = $el.height();
				rowDivs.push($el);
	
			} else {
		
				rowDivs.push($el);
				currentTallest = (currentTallest < $el.height()) ? ($el.height()) : (currentTallest);
		
			}
		
			for (currentDiv = 0 ; currentDiv < rowDivs.length ; currentDiv++) {
			
				rowDivs[currentDiv].height(currentTallest);
		
			}
	
		});
	
	}
	
	
	
	/*
	*
	*back to top button
	*
	*/
	
	//only large screens
	if (viewport.width >= 768) {
	
	// browser window scroll (in pixels) after which the "back to top" link is shown
	var offset = 300,
	//browser window scroll (in pixels) after which the "back to top" link opacity is reduced
	offset_opacity = 1200,
	//duration of the top scrolling animation (in ms)
	scroll_top_duration = 700,
	//grab the "back to top" link
	$back_to_top = $('.cd-top');

	
	//show hide subnav depending on scroll direction
    var position = $(window).scrollTop();
	
	//hide or show the "back to top" link
	$(window).scroll(function() {
	
		( $(this).scrollTop() > offset ) ? $back_to_top.addClass('cd-is-visible') : $back_to_top.removeClass('cd-is-visible cd-fade-out');
		
		if( $(this).scrollTop() > offset_opacity ) { 
			
			$back_to_top.addClass('cd-fade-out');
		
		}
		
		
		/*
	//sticky block
	var block_padding = 0; //отступ после блока
	var footer_height = $("#footer").outerHeight(); // высота подвала
	
	var sidebar = $("#sidebar1"); // блок который перемещается
	var sidebar_height = sidebar.outerHeight(); // высота блока в пикселях
	
	var sticky_position = sidebar.offset().top + sidebar.outerHeight(); //) - $(window).height(); // позиция после которой блок становится плавающим
	var scroll_top = $(window).scrollTop(); // текущее положение скролла в пикселях
	var dh = $(document).height();
	
		var overall_height = (dh - scroll_top) - $(window).height(); //сколько осталось до дна в пикселях
		var overall_height_from_top = (dh - overall_height) - $(window).height(); //сколько прошло в пикселях от верха
	
	//var sb = $(".sticky-block");
	//var sbi = $(".sticky-block .inner");
	//var sb_ot = sb.offset().top;
	//var sbi_ot = sbi.offset().top;
	
	//console.log(scroll_top + $(window).height());
	
	//console.log(overall_height_from_top + $(window).height());	
		
	  var scroll = $(window).scrollTop();
	  
	

			if (scroll > position) { //scroll down
			
			
				//calculate scroll bottom position
				var scroll_bottom = Math.round($( window ).scrollTop() + $( window ).height());
			
				//position when sidebar stop
				var stop_position = Math.round($( document ).height() - footer_height);
				
				
					
					//enable sticky block while scroll top > sidebar 
					if ( scroll_top +	$(window).height() >= sticky_position + block_padding )  {
						
						var ff = Math.abs(sticky_position -  scroll_bottom);
				//var df = Math.abs($(window).height() - ff);
						var tt = Math.abs(ff - sidebar.offset().top);
						
						var dd = ff + Math.abs(sticky_position -  scroll_bottom);
				//console.log( Math.abs(sticky_position -  scroll_bottom));
				console.log ( ff +" " + tt + " " + dd + " " + scroll_bottom + " " + sticky_position ) 
						
						//handle stop position when footer is right near
						if ( scroll_bottom <= stop_position ) { //if ( sidebar_height + $(document).scrollTop() + footer_height < $(document).height() ) {
		
							console.log("WORK");
							console.log("STICKY ENABLED");
							
							//console.log( "test: " + Math.round( sidebar.offset().top + sidebar.outerHeight() ) );
							var h = Math.abs(sticky_position -  scroll_bottom);
							
							sidebar.css( { "padding-top" : ff } );
					
						} else {
			
							console.log("STOP");
							console.log("STICKY DISABLED");
							
						}
					
					}
		

			} else { //scroll up
         
			//sidebar.css( {"paddingTop" : 0} );
			//console.log("scroll to top : " + Math.round( $( document ).height() - $(window).scrollTop() ) );
		
   
			}
	
			position = scroll;
		

	//end sticky block*/
	
	}); //scroll end function

	//smooth scroll to top
	$back_to_top.on('click', function(event) {
		
		event.preventDefault();
		$('body,html').animate({
		
			scrollTop: 0 ,
		 	
			}, scroll_top_duration
		
		);
	
	});
	
	}
	
	/*
	*
	*back to top end
	*
	*/
	
	//load equal heights when page load
	$(window).load(function() {
		
		//change margin top of content
		var w = $(window).width();
		var h = ($('#header').innerHeight() / 16);
		$('#content').css('margin-top',h + 'em');
		
		equalheight('.grid-container .t-c');
	
	});
	
	//update grid on main page
	if( typeof is_home === "undefined" ) var is_home = $('body').hasClass('home');
		
		if( is_home ) {
			
			$(window).scroll(function() {
		
				//console.log('equalheight is working');
				equalheight('.grid-container .t-c');
	
			});
	
		}

	$(window).resize( function() {
	
		// set the viewport using the function above
		viewport = updateViewportDimensions();
		
		if (viewport.width >= 481) {
		
			//set main
			var h = ($('#header').innerHeight() / 16);
			$('#content').css('margin-top',h + 'em');
		
		} else {
			
			//set main
			var h = ($('#header').innerHeight() / 16);
			$('#content').css('margin-top',h + 1+ 'em');
			
		}
		
		if (viewport.width >= 768) {
		
			$("#sidebar1").stick_in_parent({ "offset_top" : $("#header").height() } );

		} else {
			
			$("#sidebar1").trigger("sticky_kit:detach");
			
		}

	//resize when window resize
	equalheight('.grid-container .t-c');
		
	});
	
	//headroom js here
	var headerHeight = $("#header").height();
	
	$("#header").headroom({
	
		"offset": headerHeight,
		"tolerance": 20,
		"classes": {
    
			"initial": "slide",
			"pinned": "slide--reset",
			"unpinned": "slide--up"
		
		}
	
	});
	
	//hamburder primary menu
	$(".navbar-toggle").on("click", function () {
	
		$(this).toggleClass("active");
	
	});
	
	//search primary menu
	$("#search-primary-toggle").click(function() {
		
		$(this).toggleClass("opened");
		$("#search-container-top").slideToggle("fast");
		
		if ($(this).hasClass("opened")) {
			
			$("#sq").focus();
			
		}
		
		return false;
	
	});
	
	
	$("body").click(function() {
	
		$("#search-primary-toggle").removeClass("opened");
		$( "#search-container-top" ).slideUp( "fast", function() {
		// Animation complete.
		});
	
	});

	$('#search-container-top').click(function(event) {
	
		event.stopPropagation();
	
	});
	
	/*
	* Let's fire off the gravatar function
	* You can remove this if you don't need it
	*/
	
	loadGravatars();

	//anchors links
		
	anchors.options = {
			
		placement: 'right'
		//visible: 'always'
		//icon: '§'
	
	};
		
	//anchors.add('h1');
	anchors.add('h2');
	
	//adding css property to syntaxHighlighter
	//var x = document.getElementsByClassName("syntaxhighlighter");
	//$(x).css('border','none !important');
	
	
	//mobile pagination
	$('#paginationpageselectcontrol').change(function() {
	
		window.location = $('#paginationpageselectcontrol').val();
	
	});
	
	} ); // End DOM READY

} )( jQuery );

/* end of as page load scripts*/