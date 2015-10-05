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

?>