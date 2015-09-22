<?php
/*
This file handles the admin area and functions.
You can use this file to make changes to the
dashboard. Updates to this page are coming soon.
It's turned off by default, but you can call it
via the functions file.

Developed by: Eddie Machado
URL: http://themble.com/bones/

Special Thanks for code & inspiration to:
@jackmcconnell - http://www.voltronik.co.uk/
Digging into WP - http://digwp.com/2010/10/customize-wordpress-dashboard/


	- removing some default WordPress dashboard widgets
	- an example custom dashboard widget
	- adding custom login css
	- changing text in footer of admin


*/

/************* DASHBOARD WIDGETS *****************/

//disable auto save
function disableAutoSave(){
    wp_deregister_script('autosave');
}
add_action( 'wp_print_scripts', 'disableAutoSave' );

define( 'AUTOSAVE_INTERVAL', 999999 ); // autosave 1x per year
define( 'EMPTY_TRASH_DAYS',  0 ); // zero days

/* disable post-revisioning nonsense */ 
define('WP_POST_REVISIONS', FALSE);

//disable code editor
define('DISALLOW_FILE_EDIT', true);

// disable default dashboard widgets
function disable_default_dashboard_widgets() {
	global $wp_meta_boxes;
	// unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now']);    // Right Now Widget
	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_activity']);        // Activity Widget
	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_comments']); // Comments Widget
	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links']);  // Incoming Links Widget
	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins']);         // Plugins Widget

	// unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']);    // Quick Press Widget
	unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_recent_drafts']);     // Recent Drafts Widget
	unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']);           //
	unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary']);         //

	// remove plugin dashboard boxes
	unset($wp_meta_boxes['dashboard']['normal']['core']['yoast_db_widget']);           // Yoast's SEO Plugin Widget
	unset($wp_meta_boxes['dashboard']['normal']['core']['rg_forms_dashboard']);        // Gravity Forms Plugin Widget
	unset($wp_meta_boxes['dashboard']['normal']['core']['bbp-dashboard-right-now']);   // bbPress Plugin Widget
	unset($wp_meta_boxes['dashboard']['normal']['core']['quick_count_dashboard_widget']);   // quick_count_dashboard_widget
	
	/*
	have more plugin widgets you'd like to remove?
	share them with us so we can get a list of
	the most commonly used. :D
	https://github.com/eddiemachado/bones/issues
	*/
}

/*
Now let's talk about adding your own custom Dashboard widget.
Sometimes you want to show clients feeds relative to their
site's content. For example, the NBA.com feed for a sports
site. Here is an example Dashboard Widget that displays recent
entries from an RSS Feed.

For more information on creating Dashboard Widgets, view:
http://digwp.com/2010/10/customize-wordpress-dashboard/
*/

// RSS Dashboard Widget
function bones_rss_dashboard_widget() {
	if ( function_exists( 'fetch_feed' ) ) {
		// include_once( ABSPATH . WPINC . '/feed.php' );               // include the required file
		$feed = fetch_feed( 'http://feeds.feedburner.com/wpcandy' );        // specify the source feed
		if (is_wp_error($feed)) {
			$limit = 0;
			$items = 0;
		} else {
			$limit = $feed->get_item_quantity(7);                        // specify number of items
			$items = $feed->get_items(0, $limit);                        // create an array of items
		}
	}
	if ($limit == 0) echo '<div>The RSS Feed is either empty or unavailable.</div>';   // fallback message
	else foreach ($items as $item) { ?>

	<h4 style="margin-bottom: 0;">
		<a href="<?php echo $item->get_permalink(); ?>" title="<?php echo mysql2date( __( 'j F Y @ g:i a', 'bonestheme' ), $item->get_date( 'Y-m-d H:i:s' ) ); ?>" target="_blank">
			<?php echo $item->get_title(); ?>
		</a>
	</h4>
	<p style="margin-top: 0.5em;">
		<?php echo substr($item->get_description(), 0, 200); ?>
	</p>
	<?php }
}

// calling all custom dashboard widgets
function bones_custom_dashboard_widgets() {
	wp_add_dashboard_widget( 'bones_rss_dashboard_widget', __( 'Recently on Themble (Customize on admin.php)', 'bonestheme' ), 'bones_rss_dashboard_widget' );
	/*
	Be sure to drop any other created Dashboard Widgets
	in this function and they will all load.
	*/
}


// removing the dashboard widgets
add_action( 'wp_dashboard_setup', 'disable_default_dashboard_widgets' );
// adding any custom widgets
add_action( 'wp_dashboard_setup', 'bones_custom_dashboard_widgets' );


/************* CUSTOM LOGIN PAGE *****************/

// calling your own login css so you can style it

//Updated to proper 'enqueue' method
//http://codex.wordpress.org/Plugin_API/Action_Reference/login_enqueue_scripts
function bones_login_css() {
	wp_enqueue_style( 'bones_login_css', get_template_directory_uri() . '/library/css/login.css', false );
}

// changing the logo link from wordpress.org to your site
function bones_login_url() {  return home_url(); }

// changing the alt text on the logo to show your site name
function bones_login_title() { return get_option( 'blogname' ); }

// calling it only on the login page
add_action( 'login_enqueue_scripts', 'bones_login_css', 10 );
add_filter( 'login_headerurl', 'bones_login_url' );
add_filter( 'login_headertitle', 'bones_login_title' );


/************* CUSTOMIZE ADMIN *******************/

/*
I don't really recommend editing the admin too much
as things may get funky if WordPress updates. Here
are a few funtions which you can choose to use if
you like.
*/

	// Custom Backend Footer
	function l_custom_admin_footer() {
		
		_e( '<span id="footer-thankyou">Developed by <a href="http://leonite.ru" target="_blank">leonite.ru</a></span>.', 'leonite-theme' );
	
	}

	// adding it to the admin area
	add_filter( 'admin_footer_text', 'l_custom_admin_footer' );


	//FIX CATEGORY WP BUG	
	require_once( 'classes/class.categoty-checklist.php' );

		Category_Checklist::init();

	//disable google fonts
	require_once( 'classes/class.disable-gfonts.php' );
	
	/* Although it would be preferred to do this on hook,
	* load early to make sure Open Sans is removed
	*/
		$disable_google_fonts = new Disable_Google_Fonts;
		
	
	//custom logout url
	add_filter( 'logout_url', 'l_custom_logout_url', 10, 2 );
	add_action( 'wp_loaded', 'l_custom_logout_action' );
	//add_action('wp_logout', 'l_custom_logout_action');
 
	/**
	* Replace default log-out URL.
	*
	* @wp-hook logout_url
	* @param string $logout_url
	* @param string $redirect
	* @return string
	*/
	function l_custom_logout_url( $logout_url, $redirect ) {
	
		global $wp;
		
		//get redirect link
		$temp = home_url('/'); 
	
		if ( is_page('profile') or is_admin() ) {
	
			$redirect = home_url('/'); 
	
		} else {
		
			$redirect = trailingslashit(home_url( $wp->request ));
		
		}
	
		$url = add_query_arg( array('dologout' => '1'), $temp );
		$url = add_query_arg( array('redirect' => $redirect), $url );
	
		return $url;
	
	}
 
	/**
	* Log the user out.
	*
	* @wp-hook wp_loaded
	* @return void
	*/

	function l_custom_logout_action() {
	
		if ( ! isset ( $_GET['dologout'] ) )
			return;
	
		wp_logout();
	
		global $wp;
	
		$redirect = trailingslashit(home_url( $wp->request ));
		$loc = isset ( $_GET['redirect'] ) ? $_GET['redirect'] : $redirect;
		wp_redirect( $loc, 302 );
		exit;
	
	}

?>
