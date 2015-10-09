<?php
/*
Digging into WP - http://digwp.com/2010/10/customize-wordpress-dashboard/


	- removing some default WordPress dashboard widgets
	- an example custom dashboard widget
	- adding custom login css
	- changing text in footer of admin


*/


	/**********/
	/*Filters */
	/**********/
	
	/* add section */
	
	//add shortcodes to excerpt
	add_filter('the_excerpt', 'do_shortcode');
	
	//images jpeg quality
	add_filter( 'jpeg_quality', create_function( '', 'return 80;' ) );
	
	/* remove section */
	
	//Remove paragraph tags around content and excerpt
	remove_filter( 'the_content', 'wpautop' );
	remove_filter( 'the_excerpt', 'wpautop' );


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
		
	}

	/*
	For more information on creating Dashboard Widgets, view:
	http://digwp.com/2010/10/customize-wordpress-dashboard/
	*/

	// RSS Dashboard Widget
	function leonite_rss_dashboard_widget() {
		
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
			
			<a href="<?php echo $item->get_permalink(); ?>" title="<?php echo mysql2date( __( 'j F Y @ g:i a', 'leonitetheme' ), $item->get_date( 'Y-m-d H:i:s' ) ); ?>" target="_blank">
			<?php echo $item->get_title(); ?>
			</a>
		
		</h4>
		
		<p style="margin-top: 0.5em;">
		<?php echo substr($item->get_description(), 0, 200); ?>
		</p>
		
								<?php }
	
	}

	// calling all custom dashboard widgets
	function leonite_custom_dashboard_widgets() {
		
		wp_add_dashboard_widget( 'leonite_rss_dashboard_widget', __( 'Recently on Themble (Customize on admin.php)', 'leonitetheme' ), 'leonite_rss_dashboard_widget' );
		
		/*
		this function load all custom dashbord widgets
		*/
	
	}

	// removing the dashboard widgets
	add_action( 'wp_dashboard_setup', 'disable_default_dashboard_widgets' );
	// adding any custom widgets
	add_action( 'wp_dashboard_setup', 'leonite_custom_dashboard_widgets' );


	/************* CUSTOM LOGIN PAGE *****************/

	// calling your own login css so you can style it

	//Updated to proper 'enqueue' method
	//http://codex.wordpress.org/Plugin_API/Action_Reference/login_enqueue_scripts
	function leonite_login_css() {
	
		wp_enqueue_style( 'leonite_login_css', get_template_directory_uri() . '/library/css/login.css', false );
	
	}

	// changing the logo link from wordpress.org to your site
	function leonite_login_url() {  return home_url(); }

	// changing the alt text on the logo to show your site name
	function leonite_login_title() { return get_option( 'blogname' ); }

	// calling it only on the login page
	add_action( 'login_enqueue_scripts', 'leonite_login_css', 10 );
	add_filter( 'login_headerurl', 'leonite_login_url' );
	add_filter( 'login_headertitle', 'leonite_login_title' );


/************* CUSTOMIZE ADMIN *******************/

/*
I don't really recommend editing the admin too much
as things may get funky if WordPress updates. Here
are a few funtions which you can choose to use if
you like.
*/

	/**
	* L_RemoveAdminBar - remove admin bar if user not admin
	* @return void
	**/

	function L_RemoveAdminBar() {
		
		if (!current_user_can('administrator') && !is_admin()) {
		
			show_admin_bar(false);
		
		}
	
	}
	
	add_action('after_setup_theme', 'L_RemoveAdminBar');

	/**
	* L_CustomAdminFooter - replace admin footer link
	* @return string
	**/
	
	function L_CustomAdminFooter() {
		
		_e( '<span id="footer-thankyou">Developed by <a href="http://leonite.ru" target="_blank">leonite.ru</a></span>.', 'leonite-theme' );
	
	}

	add_filter( 'admin_footer_text', 'L_CustomAdminFooter' );
	

	//Remove the WordPress Logo from the WordPress Admin Bar   
	function L_RemoveWpLogo() {  
		
		global $wp_admin_bar;  
		$wp_admin_bar->remove_menu('wp-logo');  
		
	}
	
	add_action( 'wp_before_admin_bar_render', 'L_RemoveWpLogo' );  
	
	
	
	
	//FIX CATEGORY WP BUG	
	require_once( 'classes/class.categoty-checklist.php' );

		Category_Checklist::init();

	//disable google fonts
	//require_once( 'classes/class.disable-gfonts.php' );
	
	/* Although it would be preferred to do this on hook,
	* load early to make sure Open Sans is removed
	*/
	//	$disable_google_fonts = new Disable_Google_Fonts;
		
	
	//custom logout url
	
	add_filter( 'logout_url', 'leonite_custom_logout_url');
	add_action( 'wp_loaded', 'leonite_custom_logout_action' );
	
	/*
	* Replace default log-out URL.
	*
	* @wp-hook logout_url
	* @return string
	*/

	function leonite_custom_logout_url() {
		
		global $wp;
		
		if ( is_admin() ) {
		
			$redirect = urlencode( home_url ( '/' ) );
		
		} else {
		
			$redirect = urlencode( trailingslashit( home_url( $wp->request ) ) );
		
		}
		
		$url = add_query_arg( array( 'dologout' => '1' ),  home_url( '/' ) );
		$url = add_query_arg( array( 'redirect' => $redirect ), $url );
		
		return $url;
	
	}
	
	/*
	* Log the user out.
	*
	* @wp-hook wp_loaded
	* @return void
	*/

	function leonite_custom_logout_action() {
		
		if ( isset ( $_GET['dologout'] ) ) {
			
			if ( isset ( $_GET['redirect'] ) ) {
			
				wp_redirect( $_GET['redirect'] );
			
			}
			
			wp_logout();
			exit;
		
		} else {
	
			return;
	
		}

	}
	
	
	
?>
