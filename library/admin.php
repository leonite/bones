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
	//remove_filter( 'the_content', 'wpautop' );
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
			
			wp_logout();
			
			if ( isset ( $_GET['redirect'] ) ) {
			
				wp_redirect( $_GET['redirect'] );
			
			}
			
			exit;
		
		} else {
	
			return;
	
		}

	}
	
	
	
	function ll_breadcrumb() {
global $post;
//schema link
$schema_link = 'http://data-vocabulary.org/Breadcrumb';
$home = 'Р“Р»Р°РІРЅР°СЏ';
$delimiter = ' &raquo; ';
$homeLink = get_bloginfo('url');
if (is_home() || is_front_page()) {
// no need for breadcrumbs in homepage
}
else {
echo '<div id="breadcrumbs">';
// main breadcrumbs lead to homepage
if (!is_single()) {
echo 'You are here: ';
}
echo '<span itemscope itemtype="' . $schema_link . '"><a itemprop="url" href="' . $homeLink . '">' . '<span itemprop="title">' . $home . '</span>' . '</a></span>' . $delimiter . ' ';
// if blog page exists
if (get_page_by_path('blog')) {
if (!is_page('blog')) {
echo '<span itemscope itemtype="' . $schema_link . '"><a itemprop="url" href="' . get_permalink(get_page_by_path('blog')) . '">' . '<span itemprop="title">Blog</span></a></span>' . $delimiter . ' ';
}
}
if (is_category()) {
$thisCat = get_category(get_query_var('cat'), false);
if ($thisCat->parent != 0) {
$category_link = get_category_link($thisCat->parent);
echo '<span itemscope itemtype="' . $schema_link . '"><a itemprop="url" href="' . $category_link . '">' . '<span itemprop="title">' . get_cat_name($thisCat->parent) . '</span>' . '</a></span>' . $delimiter . ' ';
}
$category_id = get_cat_ID(single_cat_title('', false));
$category_link = get_category_link($category_id);
echo '<span itemscope itemtype="' . $schema_link . '"><a itemprop="url" href="' . $category_link . '">' . '<span itemprop="title">' . single_cat_title('', false) . '</span>' . '</a></span>';
}
elseif (is_single() && !is_attachment()) {
if (get_post_type() != 'post') {
$post_type = get_post_type_object(get_post_type());
$slug = $post_type->rewrite;
echo '<span itemscope itemtype="' . $schema_link . '"><a itemprop="url" href="' . $homeLink . '/' . $slug['slug'] . '">' . '<span itemprop="title">' . $post_type->labels->singular_name . '</span>' . '</a></span>';
echo ' ' . $delimiter . ' ' . get_the_title();
}
else {
$category = get_the_category();
if ($category) {
foreach ($category as $cat) {
echo '<span itemscope itemtype="' . $schema_link . '"><a itemprop="url" href="' . get_category_link($cat->term_id) . '">' . '<span itemprop="title">' . $cat->name . '</span>' . '</a></span>' . $delimiter . ' ';
}
}
echo get_the_title();
}
}
elseif (!is_single() && !is_page() && get_post_type() != 'post' && !is_404()) {
$post_type = get_post_type_object(get_post_type());
echo $post_type->labels->singular_name;
}
elseif (is_attachment()) {
$parent = get_post($post->post_parent);
$cat = get_the_category($parent->ID);
$cat = $cat[0];
echo get_category_parents($cat, TRUE, ' ' . $delimiter . ' ');
echo '<span itemscope itemtype="' . $schema_link . '"><a itemprop="url" href="' . get_permalink($parent) . '">' . '<span itemprop="title">' . $parent->post_title . '</span>' . '</a></span>';
echo ' ' . $delimiter . ' ' . get_the_title();
}
elseif (is_page() && !$post->post_parent) {
$get_post_slug = $post->post_name;
$post_slug = str_replace('-', ' ', $get_post_slug);
echo '<span itemscope itemtype="' . $schema_link . '"><a itemprop="url" href="' . get_permalink() . '">' . '<span itemprop="title">' . ucfirst($post_slug) . '</span>' . '</a></span>';
}
elseif (is_page() && $post->post_parent) {
$parent_id = $post->post_parent;
$breadcrumbs = array();
while ($parent_id) {
$page = get_page($parent_id);
$breadcrumbs[] = '<span itemscope itemtype="' . $schema_link . '"><a itemprop="url" href="' . get_permalink($page->ID) . '">' . '<span itemprop="title">' . get_the_title($page->ID) . '</span>' . '</a></span>';
$parent_id = $page->post_parent;
}
$breadcrumbs = array_reverse($breadcrumbs);
for ($i = 0; $i < count($breadcrumbs); $i++) {
echo $breadcrumbs[$i];
if ($i != count($breadcrumbs) - 1)
echo ' ' . $delimiter . ' ';
}
echo $delimiter . '<span itemscope itemtype="' . $schema_link . '"><a itemprop="url" href="' . get_permalink() . '">' . '<span itemprop="title">' . the_title_attribute('echo=0') . '</span>' . '</a></span>';
}
elseif (is_tag()) {
$tag_id = get_term_by('name', single_cat_title('', false), 'post_tag');
if ($tag_id) {
$tag_link = get_tag_link($tag_id->term_id);
}
echo '<span itemscope itemtype="' . $schema_link . '"><a itemprop="url" href="' . $tag_link . '">' . '<span itemprop="title">' . single_cat_title('', false) . '</span>' . '</a></span>';
}
elseif (is_author()) {
global $author;
$userdata = get_userdata($author);
echo '<span itemscope itemtype="' . $schema_link . '"><a itemprop="url" href="' . get_author_posts_url($userdata->ID) . '">' . '<span itemprop="title">' . $userdata->display_name . '</span>' . '</a></span>';
}
elseif (is_404()) {
echo 'Error 404';
}
elseif (is_search()) {
echo 'Search results for "' . get_search_query() . '"';
}
elseif (is_day()) {
echo '<span itemscope itemtype="' . $schema_link . '"><a itemprop="url" href="' . get_year_link(get_the_time('Y')) . '">' . '<span itemprop="title">' . get_the_time('Y') . '</span>' . '</a></span>' . $delimiter . ' ';
echo '<span itemscope itemtype="' . $schema_link . '"><a itemprop="url" href="' . get_month_link(get_the_time('Y'), get_the_time('m')) . '">' . '<span itemprop="title">' . get_the_time('F') . '</span>' . '</a></span>' . $delimiter . ' ';
echo '<span itemscope itemtype="' . $schema_link . '"><a itemprop="url" href="' . get_day_link(get_the_time('Y'), get_the_time('m'), get_the_time('d')) . '">' . '<span itemprop="title">' . get_the_time('d') . '</span>' . '</a></span>';
}
elseif (is_month()) {
echo '<span itemscope itemtype="' . $schema_link . '"><a itemprop="url" href="' . get_year_link(get_the_time('Y')) . '">' . '<span itemprop="title">' . get_the_time('Y') . '</span>' . '</a></span>' . $delimiter . ' ';
echo '<span itemscope itemtype="' . $schema_link . '"><a itemprop="url" href="' . get_month_link(get_the_time('Y'), get_the_time('m')) . '">' . '<span itemprop="title">' . get_the_time('F') . '</span>' . '</a></span>';
}
elseif (is_year()) {
echo '<span itemscope itemtype="' . $schema_link . '"><a itemprop="url" href="' . get_year_link(get_the_time('Y')) . '">' . '<span itemprop="title">' . get_the_time('Y') . '</span>' . '</a></span>';
}
if (get_query_var('paged')) {
if (is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author())
echo ' (';
echo __('Page') . ' ' . get_query_var('paged');
if (is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author())
echo ')';
}
echo '</div>';
}
}

/*
function l_breadcrumbs() {

  $text['home'] = __('Главная'); // текст ссылки "Главная"
  $text['category'] = 'Архив рубрики "%s"'; // текст для страницы рубрики
  $text['search'] = 'Результаты поиска по запросу "%s"'; // текст для страницы с результатами поиска
  $text['tag'] = 'Записи с тегом "%s"'; // текст для страницы тега
  $text['author'] = 'Статьи автора %s'; // текст для страницы автора
  $text['404'] = 'Ошибка 404'; // текст для страницы 404
  $text['page'] = 'Страница %s'; // текст 'Страница N'
  $text['cpage'] = 'Страница комментариев %s'; // текст 'Страница комментариев N'

  $wrap_before = '<div class="breadcrumbs">'; // открывающий тег обертки
  $wrap_after = '</div><!-- .breadcrumbs -->'; // закрывающий тег обертки
  $sep = '›'; // разделитель между "крошками"
  $sep_before = '<span class="sep">'; // тег перед разделителем
  $sep_after = '</span>'; // тег после разделителя
  $show_home_link = 1; // 1 - показывать ссылку "Главная", 0 - не показывать
  $show_on_home = 0; // 1 - показывать "хлебные крошки" на главной странице, 0 - не показывать
  $show_current = 1; // 1 - показывать название текущей страницы, 0 - не показывать
  $before = '<span class="current">'; // тег перед текущей "крошкой"
  $after = '</span>'; // тег после текущей "крошки"


  global $post;
  $home_link = home_url('/');
  $link_before = '<span itemscope itemtype="http://data-vocabulary.org/Breadcrumb">';
  $link_after = '</span>';
  $link_attr = ' itemprop="url"';
  $link_in_before = '<span itemprop="title">';
  $link_in_after = '</span>';
  $link = $link_before . '<a href="%1$s"' . $link_attr . '>' . $link_in_before . '%2$s' . $link_in_after . '</a>' . $link_after;
  $frontpage_id = get_option('page_on_front');
  $parent_id = $post->post_parent;
  $sep = ' ' . $sep_before . $sep . $sep_after . ' ';

  if (is_home() || is_front_page()) {

    if ($show_on_home) echo $wrap_before . '<a href="' . $home_link . '">' . $text['home'] . '</a>' . $wrap_after;

  } else {

    echo $wrap_before;
    if ($show_home_link) echo sprintf($link, $home_link, $text['home']);

    if ( is_category() ) {
      $cat = get_category(get_query_var('cat'), false);
      if ($cat->parent != 0) {
        $cats = get_category_parents($cat->parent, TRUE, $sep);
        $cats = preg_replace("#^(.+)$sep$#", "$1", $cats);
        $cats = preg_replace('#<a([^>]+)>([^<]+)<\/a>#', $link_before . '<a$1' . $link_attr .'>' . $link_in_before . '$2' . $link_in_after .'</a>' . $link_after, $cats);
        if ($show_home_link) echo $sep;
        echo $cats;
      }
      if ( get_query_var('paged') ) {
        $cat = $cat->cat_ID;
        echo $sep . sprintf($link, get_category_link($cat), get_cat_name($cat)) . $sep . $before . sprintf($text['page'], get_query_var('paged')) . $after;
      } else {
        if ($show_current) echo $sep . $before . sprintf($text['category'], single_cat_title('', false)) . $after;
      }

    } elseif ( is_search() ) {
      if (have_posts()) {
        if ($show_home_link && $show_current) echo $sep;
        if ($show_current) echo $before . sprintf($text['search'], get_search_query()) . $after;
      } else {
        if ($show_home_link) echo $sep;
        echo $before . sprintf($text['search'], get_search_query()) . $after;
      }

    } elseif ( is_day() ) {
      if ($show_home_link) echo $sep;
      echo sprintf($link, get_year_link(get_the_time('Y')), get_the_time('Y')) . $sep;
      echo sprintf($link, get_month_link(get_the_time('Y'), get_the_time('m')), get_the_time('F'));
      if ($show_current) echo $sep . $before . get_the_time('d') . $after;

    } elseif ( is_month() ) {
      if ($show_home_link) echo $sep;
      echo sprintf($link, get_year_link(get_the_time('Y')), get_the_time('Y'));
      if ($show_current) echo $sep . $before . get_the_time('F') . $after;

    } elseif ( is_year() ) {
      if ($show_home_link && $show_current) echo $sep;
      if ($show_current) echo $before . get_the_time('Y') . $after;

    } elseif ( is_single() && !is_attachment() ) {
      if ($show_home_link) echo $sep;
      if ( get_post_type() != 'post' ) {
        $post_type = get_post_type_object(get_post_type());
        $slug = $post_type->rewrite;
        printf($link, $home_link . '/' . $slug['slug'] . '/', $post_type->labels->singular_name);
        if ($show_current) echo $sep . $before . get_the_title() . $after;
      } else {
        $cat = get_the_category(); $cat = $cat[0];
        $cats = get_category_parents($cat, TRUE, $sep);
        if (!$show_current || get_query_var('cpage')) $cats = preg_replace("#^(.+)$sep$#", "$1", $cats);
        $cats = preg_replace('#<a([^>]+)>([^<]+)<\/a>#', $link_before . '<a$1' . $link_attr .'>' . $link_in_before . '$2' . $link_in_after .'</a>' . $link_after, $cats);
        echo $cats;
        if ( get_query_var('cpage') ) {
          echo $sep . sprintf($link, get_permalink(), get_the_title()) . $sep . $before . sprintf($text['cpage'], get_query_var('cpage')) . $after;
        } else {
          if ($show_current) echo $before . get_the_title() . $after;
        }
      }

    // custom post type
    } elseif ( !is_single() && !is_page() && get_post_type() != 'post' && !is_404() ) {
      $post_type = get_post_type_object(get_post_type());
      if ( get_query_var('paged') ) {
        echo $sep . sprintf($link, get_post_type_archive_link($post_type->name), $post_type->label) . $sep . $before . sprintf($text['page'], get_query_var('paged')) . $after;
      } else {
        if ($show_current) echo $sep . $before . $post_type->label . $after;
      }

    } elseif ( is_attachment() ) {
      if ($show_home_link) echo $sep;
      $parent = get_post($parent_id);
      $cat = get_the_category($parent->ID); $cat = $cat[0];
      if ($cat) {
        $cats = get_category_parents($cat, TRUE, $sep);
        $cats = preg_replace('#<a([^>]+)>([^<]+)<\/a>#', $link_before . '<a$1' . $link_attr .'>' . $link_in_before . '$2' . $link_in_after .'</a>' . $link_after, $cats);
        echo $cats;
      }
      printf($link, get_permalink($parent), $parent->post_title);
      if ($show_current) echo $sep . $before . get_the_title() . $after;

    } elseif ( is_page() && !$parent_id ) {
      if ($show_current) echo $sep . $before . get_the_title() . $after;

    } elseif ( is_page() && $parent_id ) {
      if ($show_home_link) echo $sep;
      if ($parent_id != $frontpage_id) {
        $breadcrumbs = array();
        while ($parent_id) {
          $page = get_page($parent_id);
          if ($parent_id != $frontpage_id) {
            $breadcrumbs[] = sprintf($link, get_permalink($page->ID), get_the_title($page->ID));
          }
          $parent_id = $page->post_parent;
        }
        $breadcrumbs = array_reverse($breadcrumbs);
        for ($i = 0; $i < count($breadcrumbs); $i++) {
          echo $breadcrumbs[$i];
          if ($i != count($breadcrumbs)-1) echo $sep;
        }
      }
      if ($show_current) echo $sep . $before . get_the_title() . $after;

    } elseif ( is_tag() ) {
      if ( get_query_var('paged') ) {
        $tag_id = get_queried_object_id();
        $tag = get_tag($tag_id);
        echo $sep . sprintf($link, get_tag_link($tag_id), $tag->name) . $sep . $before . sprintf($text['page'], get_query_var('paged')) . $after;
      } else {
        if ($show_current) echo $sep . $before . sprintf($text['tag'], single_tag_title('', false)) . $after;
      }

    } elseif ( is_author() ) {
      global $author;
      $author = get_userdata($author);
      if ( get_query_var('paged') ) {
        if ($show_home_link) echo $sep;
        echo sprintf($link, get_author_posts_url($author->ID), $author->display_name) . $sep . $before . sprintf($text['page'], get_query_var('paged')) . $after;
      } else {
        if ($show_home_link && $show_current) echo $sep;
        if ($show_current) echo $before . sprintf($text['author'], $author->display_name) . $after;
      }

    } elseif ( is_404() ) {
      if ($show_home_link && $show_current) echo $sep;
      if ($show_current) echo $before . $text['404'] . $after;

    } elseif ( has_post_format() && !is_singular() ) {
      if ($show_home_link) echo $sep;
      echo get_post_format_string( get_post_format() );
    }

    echo $wrap_after;

  }
} // end of breadcrumbs
*/
	
	
?>
