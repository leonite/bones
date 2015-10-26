<?php
/* 
  - head cleanup (remove rsd, uri links, junk css, ect)
  - enqueueing scripts & styles
  - theme support functions
  - custom menu output & fallbacks
  - related post function
  - page-navi function
  - removing <p> from around images
  - customizing the post excerpt

*/

/*********************
WP_HEAD GOODNESS
The default wordpress head is
a mess. Let's clean it up by
removing all the junk we don't
need.
*********************/

	function leonite_head_cleanup() {
		
		// category feeds
		// remove_action( 'wp_head', 'feed_links_extra', 3 );
		// post and comment feeds
		// remove_action( 'wp_head', 'feed_links', 2 );
		// EditURI link
		remove_action( 'wp_head', 'rsd_link' );
		// windows live writer
		remove_action( 'wp_head', 'wlwmanifest_link' );
		// previous link
		remove_action( 'wp_head', 'parent_post_rel_link', 10, 0 );
		// start link
		remove_action( 'wp_head', 'start_post_rel_link', 10, 0 );
		// links for adjacent posts
		remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );
		// WP version
		remove_action( 'wp_head', 'wp_generator' );
		// remove WP version from css
		add_filter( 'style_loader_src', 'leonite_remove_wp_ver_css_js', 9999 );
		// remove Wp version from scripts
		add_filter( 'script_loader_src', 'leonite_remove_wp_ver_css_js', 9999 );

	} /* end leonite head cleanup */

	// A better title
	// http://www.deluxeblogtips.com/2012/03/better-title-meta-tag.html
	function rw_title( $title, $sep, $seplocation ) {
	
		global $page, $paged;

		// Don't affect in feeds.
		if ( is_feed() ) return $title;

		// Add the blog's name
		if ( 'right' == $seplocation ) {
			
			$title .= get_bloginfo( 'name' );
		
		} else {
			
			$title = get_bloginfo( 'name' ) . $title;
		
		}

		// Add the blog description for the home/front page.
		$site_description = get_bloginfo( 'description', 'display' );

		if ( $site_description && ( is_home() || is_front_page() ) ) {
			
			$title .= " {$sep} {$site_description}";
		
		}

		// Add a page number if necessary:
		if ( $paged >= 2 || $page >= 2 ) {
			
			$title .= " {$sep} " . sprintf( __( 'Page %s', 'dbt' ), max( $paged, $page ) );
		
		}

		return $title;

	} // end better title

	// remove WP version from RSS
	function leonite_rss_version() { return ''; }

	// remove WP version from scripts
	function leonite_remove_wp_ver_css_js( $src ) {
		
		if ( strpos( $src, 'ver=' ) )
			$src = remove_query_arg( 'ver', $src );
		
		return $src;
	
	}

	// remove injected CSS for recent comments widget
	function leonite_remove_wp_widget_recent_comments_style() {
		
		if ( has_filter( 'wp_head', 'wp_widget_recent_comments_style' ) ) {
			
			remove_filter( 'wp_head', 'wp_widget_recent_comments_style' );
		
		}
	
	}

	// remove injected CSS from recent comments widget
	function leonite_remove_recent_comments_style() {
		
		global $wp_widget_factory;
		
		if (isset($wp_widget_factory->widgets['WP_Widget_Recent_Comments'])) {
			
			remove_action( 'wp_head', array($wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style') );
		
	}
}

	// remove injected CSS from gallery
	function leonite_gallery_style($css) {
		
	return preg_replace( "!<style type='text/css'>(.*?)</style>!s", '', $css );
	
	}


	/**
	* L_FileVersion - return md5 hash if file exist
	* @param string $filename
	* @param string $flag
	* @return string 
	**/
	
	function L_FileVersion($filename, $flag) {
		
		// get the absolute path to the file
		if ($flag == 'css') {
			
			$pathToFile = THEME_CSS . $filename;
			
		} else if ($flag == 'js') {
	
			$pathToFile = THEME_JS . $filename;
	
		}

		//check if the file exists
		if (file_exists($pathToFile)) {
			
			// return hash of file
			return hash_file('md5', $pathToFile);
		
		}
		
		else
		
		{
			
			// let them know the file wasn't found
			return time();
		
		}
	
	}
	
	// Remove Open Sans that WP adds from frontend

	if (!function_exists('remove_wp_open_sans')) :
		
		function remove_wp_open_sans() {
			
			wp_deregister_style( 'open-sans' );
			wp_register_style( 'open-sans', false );
		
		}
		
		add_action('wp_enqueue_scripts', 'remove_wp_open_sans');
		
		// Uncomment below to remove from admin
		//add_action('admin_enqueue_scripts', 'remove_wp_open_sans');
	
	endif;


	/* Hide WP version strings from scripts and styles and adding hash to links
		
		* @param {string} $src 
		* @return {string} $src
		* @filter script_loader_src
		* @filter style_loader_src
	
	*/
	
	function remove_wp_version_strings_and_hash_it( $src ) {
	
		if ( ( strpos( $src, 'api-maps.yandex.ru' ) == false ) && ( strpos( $src, 'fonts.googleapis.com' ) == false ) ) { //if yandex maps or google fonts, don't parse url
    
			parse_str( parse_url( $src, PHP_URL_QUERY), $query );
	
			if ( !empty( $query['ver'] ) ) {
	
				$src = remove_query_arg( 'ver', $src );
				
			}
				
			$hash = md5( @file_get_contents( $src ) );
			$src = $src . '?vhid=' . $hash;			
		
		}
     
		return $src;
	
	}
	add_filter( 'script_loader_src', 'remove_wp_version_strings_and_hash_it' );
	add_filter( 'style_loader_src', 'remove_wp_version_strings_and_hash_it' );

	/**
	* L_RemoteFileExists - check file exist via http or https connection
	* @param string $url 
	* @param bool $usehttps / default = true
	* @return bool 
	**/
 
	function L_RemoteFileExists($url, $usehttps = true) {

		$curl = curl_init($url);

		//don't fetch the actual page, you only want to check the connection is ok
		if ($usehttps)
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		
		curl_setopt($curl, CURLOPT_NOBODY, true);
		
		//do request
		$result = curl_exec($curl);
		$ret = false;

		//if request did not fail
		if ($result !== false) {
			
			//if request was ok, check response code
			$statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);  

			if ($statusCode == 200) {
				$ret = true;   
			}
		}

		curl_close($curl);

		return $ret;
	
	}


/*********************
SCRIPTS & ENQUEUEING
*********************/

	// loading modernizr and jquery, and reply script
	function leonite_scripts_and_styles() {

		global $wp_styles; // call global $wp_styles variable to add conditional wrapper around ie stylesheet the WordPress way

		if (!is_admin()) {
			
			/*JS
			adding scripts file in the footer
			including jquery firstly
			we check if google cdn is available
			*/
			
			$exists = L_RemoteFileExists('https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js');
		
			if ($exists) {
			
				wp_deregister_script( 'jquery' );
				wp_register_script( 'jquery', ( 'https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js' ), false, null, true );
				wp_enqueue_script( 'jquery' );
		
			} else {
			
				wp_enqueue_script( 'jquery' );
			
			}
						
			// modernizr (without media query polyfill)
			wp_register_script( 'leonite-modernizr', get_stylesheet_directory_uri() . '/library/js/libs/modernizr.custom.min.js', array(), '', true );

			//bootstrap
			wp_register_script( 'bootstrap-js', get_stylesheet_directory_uri()  . '/library/js/bootstrap.min.js', array('jquery'), '', true );
			
			//thirdparty scripts
			wp_register_script( 'thirdparty-js', get_stylesheet_directory_uri()  . '/library/js/thirdparty.js', array('jquery'), '', true );
			
			//adding scripts file in the footer
			wp_register_script( 'leonite-js', get_stylesheet_directory_uri() . '/library/js/scripts.js', array('jquery'), '', true );
			
			
			// comment reply script for threaded comments
			if ( is_singular() AND comments_open() AND (get_option('thread_comments') == 1)) {
			
				wp_enqueue_script( 'comment-reply' );
			
			}
	
			
			//enqueue scripts	
			wp_enqueue_script( 'leonite-modernizr' );
			wp_enqueue_script( 'bootstrap-js' );
			wp_enqueue_script( 'thirdparty-js' );
			wp_enqueue_script( 'leonite-js' );
		
			
			/*
			
			CSS
			
			*/
			
			//bootstrap
			//wp_register_style( 'bootstrap-css', get_stylesheet_directory_uri()  . '/library/css/bootstrap.min.css', array(), '', 'all' );
			
			// register main stylesheet
			wp_register_style( 'leonite-css', get_stylesheet_directory_uri() . '/library/css/theme.css', array(), '', 'all' );

			// ie-only style sheet
			wp_register_style( 'leonite-ie-only', get_stylesheet_directory_uri() . '/library/css/ie.css', array(), '' );

			
			// enqueue styles and scripts
			// @todo: allow subsets via i18n.
			wp_enqueue_style( 'leonite-pt-serif', '//fonts.googleapis.com/css?family=PT+Serif&subset=latin,cyrillic', false );
			wp_enqueue_style( 'leonite-open-sans', '//fonts.googleapis.com/css?family=Open+Sans:400,700&subset=latin,cyrillic', false );
			
			//wp_enqueue_style( 'bootstrap-css' );
			wp_enqueue_style( 'leonite-css' );
			wp_enqueue_style( 'leonite-ie-only' );
			
			$wp_styles->add_data( 'leonite-ie-only', 'conditional', 'lt IE 9' ); // add conditional wrapper around ie stylesheet

			
			/*
			
			adding local vars
			
			*/
			$jsvars = array( 'template_url' => get_bloginfo('template_url'), 'homeurl' => get_option('home'), 'currenturl' => $_SERVER['REQUEST_URI'], 'pageid' => (int)get_the_ID(), 'ajaxurl' => admin_url('admin-ajax.php') );
		
			wp_localize_script( 'core-js', 'jsvars', $jsvars );

		}
	
	}

/*********************
THEME SUPPORT
*********************/

// Adding WP 3+ Functions & Theme Support
function leonite_theme_support() {

	// wp thumbnails (sizes handled in functions.php)
	add_theme_support( 'post-thumbnails' );

	// default thumb size
	set_post_thumbnail_size(125, 125, true);

	// wp custom background (thx to @bransonwerner for update)
	add_theme_support( 'custom-background',
	    array(
	    'default-image' => '',    // background image default
	    'default-color' => '',    // background color default (dont add the #)
	    'wp-head-callback' => '_custom_background_cb',
	    'admin-head-callback' => '',
	    'admin-preview-callback' => ''
	    )
	);

	// rss thingy
	add_theme_support('automatic-feed-links');

	// to add header image support go here: http://themble.com/support/adding-header-background-image-support/

	// adding post format support
	add_theme_support( 'post-formats',
		array(
			'aside',             // title less blurb
			'gallery',           // gallery of images
			'link',              // quick link to other site
			'image',             // an image
			'quote',             // a quick quote
			'status',            // a Facebook like status update
			'video',             // video
			'audio',             // audio
			'chat'               // chat transcript
		)
	);

	// wp menus
	add_theme_support( 'menus' );

	// registering wp3+ menus
	register_nav_menus(
		array(
			'main-nav' => __( 'The Main Menu', 'leonite' ),   // main nav in header
			'footer-links' => __( 'Footer Links', 'leonite' ) // secondary nav in footer
		)
	);

	// Enable support for HTML5 markup.
	add_theme_support( 'html5', array(
		'comment-list',
		'search-form',
		'comment-form'
	) );

} /* end leonite theme support */


	/*********************
	RELATED POSTS FUNCTION
	*********************/

	// Related Posts Function (call using leonite_related_posts(); )
	function leonite_related_posts() {
		
		echo '<ul id="leonite-related-posts">';
		global $post;
		$tags = wp_get_post_tags( $post->ID );
			
		if ($tags) {
			
			foreach( $tags as $tag ) {
			
				$tag_arr .= $tag->slug . ',';
			
			}
		
		$args = array(
			
			'tag' => $tag_arr,
			'numberposts' => 5, /* you can change this to show more */
			'post__not_in' => array($post->ID)
		
		);
		
		$related_posts = get_posts( $args );
		
		if($related_posts) {
			
			foreach ( $related_posts as $post ) : setup_postdata( $post ); ?>
				
				<li class="related_post"><a class="entry-unrelated" href="<?php the_permalink() ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></li>
			
			<?php endforeach; } else { ?>
			
			<?php echo '<li class="no_related_post">' . __( 'No Related Posts Yet!', 'leonitetheme' ) . '</li>'; ?>
			
			<?php }
		
		}
		
		wp_reset_postdata();
		echo '</ul>';
	
	} /* end leonite related posts function */

	/*********************
	PAGE NAVI
	*********************/

	// Numeric Page Navi (built into the theme by default)
	function leonite_page_navi() {
		
		global $wp_query;
		$bignum = 999999999;
		
		if ( $wp_query->max_num_pages <= 1 )
			return;
  
		echo '<nav class="pagination">';
		echo paginate_links( array(
			
			'base'         => str_replace( $bignum, '%#%', esc_url( get_pagenum_link($bignum) ) ),
			'format'       => '',
			'current'      => max( 1, get_query_var('paged') ),
			'total'        => $wp_query->max_num_pages,
			'prev_text'    => '&larr;',
			'next_text'    => '&rarr;',
			'type'         => 'list',
			'end_size'     => 3,
			'mid_size'     => 3
		
		) );
		
		echo '</nav>';
	
	} /* end page navi */

	/*********************
	RANDOM CLEANUP ITEMS
	*********************/

	// remove the p from around imgs (http://css-tricks.com/snippets/wordpress/remove-paragraph-tags-from-around-images/)
	function leonite_filter_ptags_on_images($content) {
		
		return preg_replace('/<p>\s*(<a .*>)?\s*(<img .* \/>)\s*(<\/a>)?\s*<\/p>/iU', '\1\2\3', $content);
		
	}

	// This removes the annoying […] to a Read More link
	function leonite_excerpt_more($more) {
		
		global $post;
		
		// edit here if you like
		return '...  <a class="excerpt-read-more" href="'. get_permalink( $post->ID ) . '" title="'. __( 'Read ', 'leonitetheme' ) . esc_attr( get_the_title( $post->ID ) ).'">'. __( 'Read more &raquo;', 'leonitetheme' ) .'</a>';
	
	}



?>
