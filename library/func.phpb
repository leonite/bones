<?php

/*

Leonite theme class

*/


class Leonite {
	
	public static $defaults = array();
	public static $colors_css_version = 20151011;

	private function __construct() {}

	/**
	 * Runs immediately at the end of this file, not to be confused
	 * with after_setup_theme, which runs a little bit later.
	 */
	public static function themesetup() {
		add_action( 'after_setup_theme', array( __CLASS__, 'after_setup_theme' ) );
		do_action( 'leonite_setup' );
	}

	/**
	 * Runs during core's after_setup_theme
	 */
	public static function after_setup_theme() {
		
		global $content_width;

		self::$defaults = array(
			
			'colors' => array(
			
				'accent' => '#117bb8',
				'text' => '#3a3a3a',
			
			),
			
			'color_labels' => array(
			
				'accent' => __( 'Accent Color', 'leonite' ),
				'text' => __( 'Text Color', 'leonite' ),
			
			),
			
			'paths' => array(
			
				'HOME_URI' => home_url(),
				'THEME_URI' => get_stylesheet_directory_uri(),
				'THEME_ROOT' => get_theme_root(),
				'THEME_IMAGES' => THEME_URI . '/library/images/',
				'THEME_CSS' => THEME_URI . '/library/css/',
				'THEME_JS' => THEME_URI . '/library/js/',
				'THEME_CLASS' => THEME_URI . '/library/classes/'
			
			),
		
		);

		if ( ! isset( $content_width ) ) {
			$content_width = 780;
		}
		
		// launching operation cleanup
		add_action( 'init', array( __CLASS__, 'leonite_head_cleanup' ) );

		add_action( 'init', array( __CLASS__, 'inline_controls_handler' ) );

		add_action( 'pre_get_posts', array( __CLASS__, 'pre_get_posts' ) );
		add_filter( 'posts_results', array( __CLASS__, 'posts_results' ), 10, 2 );
		add_filter( 'found_posts', array( __CLASS__, 'found_posts' ), 10, 2 );
		add_filter( 'body_class', array( __CLASS__, 'body_class' ) );
		add_filter( 'post_class', array( __CLASS__, 'post_class' ), 10, 3 );

		add_filter( 'shortcode_atts_gallery', array( __CLASS__, 'shortcode_atts_gallery' ), 10, 3 );
		add_filter( 'use_default_gallery_style', '__return_false' );

		add_action( 'widgets_init', array( __CLASS__, 'widgets_init' ) );
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'enqueue_scripts' ) );
		add_action( 'wp', array( __CLASS__, 'setup_author' ) );

		add_filter( 'wp_page_menu_args', array( __CLASS__, 'page_menu_args' ) );
		add_filter( 'wp_title', array( __CLASS__, 'wp_title' ), 10, 2 );

		// Enhanced customizer support
		add_action( 'customize_register', array( __CLASS__, 'customize_register' ) );
		add_action( 'customize_preview_init', array( __CLASS__, 'customize_preview_js' ) );

		
		
		
		
		load_theme_textdomain( 'leonite', get_template_directory() . '/library/translation' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		// Editor style
		add_editor_style( self::$defaults['paths']['THEME_URI'] . '/library/css/editor-style.css' );

		// Post thumbnail support and additional image sizes.
		add_theme_support( 'post-thumbnails' );
		
		set_post_thumbnail_size( 360, 210, true );
		add_image_size( 'leonite-mini', 60, 60, true );
		add_image_size( 'leonite-gallery', 220, 220, true );

		// This theme uses a primary navigation menu and an additional
		// menu for social profile links.
		add_theme_support( 'menus' );
		
		register_nav_menus( array(
			'primary' => __( 'Primary Menu', 'leonite' ),
			'social'  => __( 'Social Menu', 'leonite' ),
		) );
		
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
		
		// HTML5 support for core elements.
		add_theme_support( 'html5', array(
			'comment-list',
			'comment-form',
			'search-form',
			'gallery',
		) );
		
		// wp custom background (thx to @bransonwerner for update)
		add_theme_support( 'custom-background',
			
			array(
				
				'default-image' => '',	// background image default
				'default-color' => 'ffffff',	// background color default (dont add the #)
				'wp-head-callback' => '_custom_background_cb',
				'admin-head-callback' => '',
				'admin-preview-callback' => ''
			
			)
		
		);

		// Add support for Jetpack's Featured Content
		//add_theme_support( 'featured-content', array(
		//	'filter' => 'semicolon_get_featured_posts',
		//	'max_posts' => 2,
		//) );

		do_action( 'semicolon_after_setup_theme' );
	
	}
	
}


	// remove the p from around imgs (http://css-tricks.com/snippets/wordpress/remove-paragraph-tags-from-around-images/)
	public static function leonite_filter_ptags_on_images($content) {
		
		return preg_replace('/<p>\s*(<a .*>)?\s*(<img .* \/>)\s*(<\/a>)?\s*<\/p>/iU', '\1\2\3', $content);
		
	}

	// This removes the annoying […] to a Read More link
	public static function leonite_excerpt_more($more) {
		
		global $post;
		
		// edit here if you like
		return '...  <a class="excerpt-read-more" href="'. get_permalink( $post->ID ) . '" title="'. __( 'Read ', 'leonitetheme' ) . esc_attr( get_the_title( $post->ID ) ).'">'. __( 'Read more &raquo;', 'leonitetheme' ) .'</a>';
	
	}


Leonite::themesetup();

?>