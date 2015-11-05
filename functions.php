<?php
	
	/*
	*
	* @Author: Leonite
	* @Since 1.0
	*
	*/

	//DEFINE GLOBALS

	//define path's\
	define( 'HOME_URI', home_url() ); //http://test1.ru/wp1 
	define( 'THEME_URI', get_stylesheet_directory_uri() ); //http://test1.ru/wp1/wp-content/themes/themename
	define( 'THEME_ROOT', get_theme_root() ); //Z:\home\test1.ru\www\wp1/wp-content/themename
	define( 'THEME_IMAGES', THEME_URI . '/library/images/' ); //http://test1.ru/wp1/wp-content/themes/themename/library/images 
	define( 'THEME_CSS', THEME_URI . '/library/css/' ); //http://test1.ru/wp1/wp-content/themes/themename/library/css 
	define( 'THEME_JS', THEME_URI . '/library/js/' ); //http://test1.ru/wp1/wp-content/themes/themename/library/js 
	define( 'THEME_CLASS', THEME_URI . '/library/classes/' ); //http://test1.ru/wp1/wp-content/themes/themename/library/classes
	
	//compiled
	define( 'THEME_CSS_C', THEME_URI . '/library/css/compiled' ); //http://test1.ru/wp1/wp-content/themes/themename/library/css/compiled 
	define( 'THEME_JS_C', THEME_URI . '/library/js/compiled' ); //http://test1.ru/wp1/wp-content/themes/themename/library/js/compiled 


	// load core functions
	require_once( 'library/core.php' );
	
	// load backend functions
	require_once( 'library/backend/backend-functions.php' );
	
	// wp admin customization
	require_once( 'library/admin.php' );
	
	//wp menu walker
	require_once('library/classes/class.wp-bootstrap-navwalker.php');
	
	//lazy images class
	require_once( 'library/classes/class.lazy-images.php' );
	
	// load theme settings page
	if ( L_RemoteFileExists( THEME_URI . '/library/themesettings/classes/class.my-theme-options.php' , false ) ) {
	
		require_once( 'library/themesettings/classes/class.my-theme-options.php' );
	
	}

/*********************
LAUNCH leonite
Let's get everything up and running.
*********************/

function leonite_ahoy() {

  //Allow editor style.
  add_editor_style( get_stylesheet_directory_uri() . '/library/css/editor-style.css' );

  // let's get language support going, if you need it
  load_theme_textdomain( 'leonitetheme', get_template_directory() . '/library/translation' );

  // USE THIS TEMPLATE TO CREATE CUSTOM POST TYPES EASILY
  //require_once( 'library/custom-post-type.php' );

  // launching operation cleanup
  add_action( 'init', 'leonite_head_cleanup' );
  // A better title
  add_filter( 'wp_title', 'rw_title', 10, 3 );
  // remove WP version from RSS
  add_filter( 'the_generator', 'leonite_rss_version' );
  // remove pesky injected css for recent comments widget
  add_filter( 'wp_head', 'leonite_remove_wp_widget_recent_comments_style', 1 );
  // clean up comment styles in the head
  add_action( 'wp_head', 'leonite_remove_recent_comments_style', 1 );
  // clean up gallery output in wp
  add_filter( 'gallery_style', 'leonite_gallery_style' );

  // enqueue base scripts and styles
  add_action( 'wp_enqueue_scripts', 'leonite_scripts_and_styles', 999 );
  // ie conditional wrapper

  // launching this stuff after theme setup
  leonite_theme_support();

  // adding sidebars to Wordpress (these are created in functions.php)
  add_action( 'widgets_init', 'leonite_register_sidebars' );

  // cleaning up random code around images
  add_filter( 'the_content', 'leonite_filter_ptags_on_images' );
  // cleaning up excerpt
  add_filter( 'excerpt_more', 'leonite_excerpt_more' );

} /* end leonite ahoy */

// let's get this party started
add_action( 'after_setup_theme', 'leonite_ahoy' );


/************* OEMBED SIZE OPTIONS *************/

if ( ! isset( $content_width ) ) {
	$content_width = 680;
}

/************* THUMBNAIL SIZE OPTIONS *************/

// Thumbnail sizes
/*add_image_size( 'leonite-thumb-600', 600, 150, true );
add_image_size( 'leonite-thumb-300', 300, 100, true );
add_image_size( 'leonite-thumb-150', 150, 150, true );
add_image_size( 'leonite-thumb-100', 100, 100, true );


to add more sizes, simply copy a line from above
and change the dimensions & name. As long as you
upload a "featured image" as large as the biggest
set width or height, all the other sizes will be
auto-cropped.

To call a different size, simply change the text
inside the thumbnail function.

For example, to call the 300 x 100 sized image,
we would use the function:
<?php the_post_thumbnail( 'leonite-thumb-300' ); ?>
for the 600 x 150 image:
<?php the_post_thumbnail( 'leonite-thumb-600' ); ?>

You can change the names and dimensions to whatever
you like. Enjoy!


the_post_thumbnail( 'leonite-thumb-600' );
the_post_thumbnail( 'leonite-thumb-300' );
the_post_thumbnail( 'leonite-thumb-150' );
the_post_thumbnail( 'leonite-thumb-100' );*/

//add_image_size( 'homepage-thumb', 220, 180 ); // Soft Crop Mode
//add_image_size( 'singlepost-thumb', 590, 9999 ); // Unlimited Height Mode


add_filter( 'image_size_names_choose', 'leonite_custom_image_sizes' );

function leonite_custom_image_sizes( $sizes ) {
   
	return array_merge( $sizes, array(
		
		'thumb-600' => __('600px by 150px'),
		'thumb-300' => __('300px by 100px'),
		'thumb-150' => __('150px by 150px'),
		'thumb-100' => __('100px by 100px')
    
	) );

}

//custom image sizes

add_action( 'after_setup_theme', 'setup_images' );

function setup_images() {
 
    add_image_size( 'thumb-600', 600, 150, true );
	add_image_size( 'thumb-300', 300, 100, true );
	add_image_size( 'thumb-150', 150, 150, true );
	add_image_size( 'thumb-100', 100, 100, true );
	
}

/*
The function above adds the ability to use the dropdown menu to select
the new images sizes you have just created from within the media manager
when you add media to your content blocks. If you add more image sizes,
duplicate one of the lines in the array and name it according to your
new image size.
*/

/************* THEME CUSTOMIZE *********************/

/* 
  A good tutorial for creating your own Sections, Controls and Settings:
  http://code.tutsplus.com/series/a-guide-to-the-wordpress-theme-customizer--wp-33722
  
  Good articles on modifying the default options:
  http://natko.com/changing-default-wordpress-theme-customization-api-sections/
  http://code.tutsplus.com/tutorials/digging-into-the-theme-customizer-components--wp-27162
  
  To do:
  - Create a js for the postmessage transport method
  - Create some sanitize functions to sanitize inputs
  - Create some boilerplate Sections, Controls and Settings
*/

function leonite_theme_customizer($wp_customize) {
  // $wp_customize calls go here.
  //
  // Uncomment the below lines to remove the default customize sections 

  // $wp_customize->remove_section('title_tagline');
  // $wp_customize->remove_section('colors');
  // $wp_customize->remove_section('background_image');
  // $wp_customize->remove_section('static_front_page');
  // $wp_customize->remove_section('nav');

  // Uncomment the below lines to remove the default controls
  // $wp_customize->remove_control('blogdescription');
  
  // Uncomment the following to change the default section titles
  // $wp_customize->get_section('colors')->title = __( 'Theme Colors' );
  // $wp_customize->get_section('background_image')->title = __( 'Images' );
}

add_action( 'customize_register', 'leonite_theme_customizer' );

/************* ACTIVE SIDEBARS ********************/

// Sidebars & Widgetizes Areas
function leonite_register_sidebars() {
	register_sidebar(array(
		'id' => 'sidebar1',
		'name' => __( 'Sidebar 1', 'leonite' ),
		'description' => __( 'The first (primary) sidebar.', 'leonite' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h4 class="widgettitle">',
		'after_title' => '</h4>',
	));
	
	//footer sidebar menu links #1
	register_sidebar(array(
		'id' => 'footer-links-1',
		'name' => __( 'Footer Links Sidebar', 'leonite' ),
		'description' => __( 'Footer Links Sidebar', 'leonite' ),
		'before_widget' => '<div id="%1$s" class="footer-links">',
		'after_widget' => '</div>',
		'before_title' => '<h4 class="footer-header">',
		'after_title' => '</h4>',
	));
	
	//footer sidebar menu links #2
	register_sidebar(array(
		'id' => 'footer-links-2',
		'name' => __( 'Footer Links Sidebar 2', 'leonite' ),
		'description' => __( 'Footer Links Sidebar 2', 'leonite' ),
		'before_widget' => '<div id="%1$s" class="footer-links">',
		'after_widget' => '</div>',
		'before_title' => '<h4 class="footer-header">',
		'after_title' => '</h4>',
	));

	/*
	to add more sidebars or widgetized areas, just copy
	and edit the above sidebar code. In order to call
	your new sidebar just use the following code:

	Just change the name to whatever your new
	sidebar's id is, for example:

	register_sidebar(array(
		'id' => 'sidebar2',
		'name' => __( 'Sidebar 2', 'leonitetheme' ),
		'description' => __( 'The second (secondary) sidebar.', 'leonitetheme' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h4 class="widgettitle">',
		'after_title' => '</h4>',
	));

	To call the sidebar in your template, you can just copy
	the sidebar.php file and rename it to your sidebar's name.
	So using the above example, it would be:
	sidebar-sidebar2.php

	*/
} // don't remove this bracket!


/************* COMMENT LAYOUT *********************/

// Comment Layout
function leonite_comments( $comment, $args, $depth ) {
   $GLOBALS['comment'] = $comment; ?>
  <div id="comment-<?php comment_ID(); ?>" <?php comment_class('cf'); ?>>
    <article  class="cf">
      <header class="comment-author vcard">
        <?php
        /*
          this is the new responsive optimized comment image. It used the new HTML5 data-attribute to display comment gravatars on larger screens only. What this means is that on larger posts, mobile sites don't have a ton of requests for comment images. This makes load time incredibly fast! If you'd like to change it back, just replace it with the regular wordpress gravatar call:
          echo get_avatar($comment,$size='32',$default='<path_to_url>' );
        */
        ?>
        <?php // custom gravatar call ?>
        <?php
          // create variable
          $bgauthemail = get_comment_author_email();
        ?>
        <img data-gravatar="http://www.gravatar.com/avatar/<?php echo md5( $bgauthemail ); ?>?s=40" class="load-gravatar avatar avatar-48 photo" height="40" width="40" src="<?php echo get_template_directory_uri(); ?>/library/images/nothing.gif" />
        <?php // end custom gravatar call ?>
        <?php printf(__( '<cite class="fn">%1$s</cite> %2$s', 'leonitetheme' ), get_comment_author_link(), edit_comment_link(__( '(Edit)', 'leonitetheme' ),'  ','') ) ?>
        <time datetime="<?php echo comment_time('Y-m-j'); ?>"><a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ) ?>"><?php comment_time(__( 'F jS, Y', 'leonitetheme' )); ?> </a></time>

      </header>
      <?php if ($comment->comment_approved == '0') : ?>
        <div class="alert alert-info">
          <p><?php _e( 'Your comment is awaiting moderation.', 'leonitetheme' ) ?></p>
        </div>
      <?php endif; ?>
      <section class="comment_content cf">
        <?php comment_text() ?>
      </section>
      <?php comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
    </article>
  <?php // </li> is added by WordPress automatically ?>
<?php
} // don't remove this bracket!


//tinyMCE adding style button
// Add the Style Dropdown Menu to the second row of visual editor buttons
function my_mce_buttons_2($buttons)
{
    array_unshift($buttons, 'styleselect');
    return $buttons;
}
add_filter('mce_buttons_2', 'my_mce_buttons_2');

/*
function set_tinymce_config( $init ) {
   // Don't remove line breaks
   $init['remove_linebreaks'] = true; 
   // Convert newline characters to BR tags
   $init['convert_newlines_to_brs'] = false; 
   // Do not remove redundant BR tags
   $init['remove_redundant_brs'] = true;

   // Pass $init back to WordPress
   return $init;
}
add_filter('tiny_mce_before_init', 'set_tinymce_config');
*/

/*
This is a modification of a function found in the
twentythirteen theme where we can declare some
external fonts. If you're using Google Fonts, you
can replace these fonts, change it in your scss files
and be up and running in seconds.

function leonite_fonts() {
  wp_enqueue_style('googleFonts', 'http://fonts.googleapis.com/css?family=Lato:400,700,400italic,700italic');
}

add_action('wp_enqueue_scripts', 'leonite_fonts');*/

	//Small security tweak

	if (strpos($_SERVER['REQUEST_URI'], "eval(") ||	strpos($_SERVER['REQUEST_URI'], "CONCAT") || strpos($_SERVER['REQUEST_URI'], "UNION+SELECT") ||	strpos($_SERVER['REQUEST_URI'], "base64")) {
		
		@header("HTTP/1.1 400 Bad Request");
		@header("Status: 400 Bad Request");
		@header("Connection: Close");
		@exit;
	
	}
	
	

/* DON'T DELETE THIS CLOSING TAG */ ?>
