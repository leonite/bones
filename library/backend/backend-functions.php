<?php

	/*
	Backend functions

	Developed by: Leonid Belov (Leonite)
	URL: https://leonite.ru
	*/

	// function to display number of posts.
	function getPostViews($postID) {
		
		$count_key = 'post_views_count';
		$count = get_post_meta($postID, $count_key, true);
			
		if ($count=='') {
			
			delete_post_meta($postID, $count_key);
			add_post_meta($postID, $count_key, '0');
			return "<div class='dashicons dashicons-visibility'></div> 0";
			
		}
		
		return '<div class="dashicons dashicons-visibility"></div> '.$count;
	
	}

	// function to count views.
	function setPostViews($postID) {
		
		$count_key = 'post_views_count';
		$count = get_post_meta($postID, $count_key, true);
			
		if ($count=='') {
			
			$count = 0;
			delete_post_meta($postID, $count_key);
			add_post_meta($postID, $count_key, '0');
			
		} else {
			
			$count++;
			update_post_meta($postID, $count_key, $count);
		
		}
	
	}

	// Add it to a column in WP-Admin
	add_filter('manage_posts_columns', 'posts_column_views');
	add_action('manage_posts_custom_column', 'posts_custom_column_views',5,2);
	
	function posts_column_views($defaults) {
		
		$defaults['post_views'] = __('Views');
		return $defaults;
	
	}
	
	function posts_custom_column_views($column_name, $id){
		
		if ($column_name === 'post_views') {
			
			echo getPostViews(get_the_ID());
		
		}
	
	}

	//Function: Register the 'Views' column as sortable in the WP dashboard.
	function register_post_column_views_sortable( $newcolumn ) {
		
		$newcolumn['post_views'] = 'post_views';
		return $newcolumn;
	
	}

	add_filter( 'manage_edit-post_sortable_columns', 'register_post_column_views_sortable' );

	//Function: Sort Post Views in WP dashboard based on the Number of Views (ASC or DESC).
	function sort_views_column( $vars ) {
		
		if ( isset( $vars['orderby'] ) && 'post_views' == $vars['orderby'] ) {
			
			$vars = array_merge( $vars, array(
			//'order' => 'ASC',
			'meta_key' => 'post_views_count', //Custom field key
			'orderby' => 'meta_value_num') //Custom field value (number)
			);
		
		}
		
		return $vars;
	
	}
	
	add_filter( 'request', 'sort_views_column' );
	
	

	
	/*NO Category*/
// Refresh rules on activation/deactivation/category changes
register_activation_hook(__FILE__, 'no_category_base_refresh_rules');
add_action('created_category', 'no_category_base_refresh_rules');
add_action('edited_category', 'no_category_base_refresh_rules');
add_action('delete_category', 'no_category_base_refresh_rules');
function no_category_base_refresh_rules() {
	global $wp_rewrite;
	$wp_rewrite -> flush_rules();
}

register_deactivation_hook(__FILE__, 'no_category_base_deactivate');
function no_category_base_deactivate() {
	remove_filter('category_rewrite_rules', 'no_category_base_rewrite_rules');
	// We don't want to insert our custom rules again
	no_category_base_refresh_rules();
}

// Remove category base
add_action('init', 'no_category_base_permastruct');
function no_category_base_permastruct() {
	global $wp_rewrite, $wp_version;
	if (version_compare($wp_version, '3.4', '<')) {
		// For pre-3.4 support
		$wp_rewrite -> extra_permastructs['category'][0] = '%category%';
	} else {
		$wp_rewrite -> extra_permastructs['category']['struct'] = '%category%';
	}
}

// Add our custom category rewrite rules
add_filter('category_rewrite_rules', 'no_category_base_rewrite_rules');
function no_category_base_rewrite_rules($category_rewrite) {
	//var_dump($category_rewrite); // For Debugging

	$category_rewrite = array();
	$categories = get_categories(array('hide_empty' => false));
	foreach ($categories as $category) {
		$category_nicename = $category -> slug;
		if ($category -> parent == $category -> cat_ID)// recursive recursion
			$category -> parent = 0;
		elseif ($category -> parent != 0)
			$category_nicename = get_category_parents($category -> parent, false, '/', true) . $category_nicename;
		$category_rewrite['(' . $category_nicename . ')/(?:feed/)?(feed|rdf|rss|rss2|atom)/?$'] = 'index.php?category_name=$matches[1]&feed=$matches[2]';
		$category_rewrite['(' . $category_nicename . ')/page/?([0-9]{1,})/?$'] = 'index.php?category_name=$matches[1]&paged=$matches[2]';
		$category_rewrite['(' . $category_nicename . ')/?$'] = 'index.php?category_name=$matches[1]';
	}
	// Redirect support from Old Category Base
	global $wp_rewrite;
	$old_category_base = get_option('category_base') ? get_option('category_base') : 'category';
	$old_category_base = trim($old_category_base, '/');
	$category_rewrite[$old_category_base . '/(.*)$'] = 'index.php?category_redirect=$matches[1]';

	//var_dump($category_rewrite); // For Debugging
	return $category_rewrite;
}

// For Debugging
//add_filter('rewrite_rules_array', 'no_category_base_rewrite_rules_array');
//function no_category_base_rewrite_rules_array($category_rewrite) {
//	var_dump($category_rewrite); // For Debugging
//}

// Add 'category_redirect' query variable
add_filter('query_vars', 'no_category_base_query_vars');
function no_category_base_query_vars($public_query_vars) {
	$public_query_vars[] = 'category_redirect';
	return $public_query_vars;
}

// Redirect if 'category_redirect' is set
add_filter('request', 'no_category_base_request');
function no_category_base_request($query_vars) {
	//print_r($query_vars); // For Debugging
	if (isset($query_vars['category_redirect'])) {
		$catlink = trailingslashit(get_option('home')) . user_trailingslashit($query_vars['category_redirect'], 'category');
		status_header(301);
		header("Location: $catlink");
		exit();
	}
	return $query_vars;
}

?>