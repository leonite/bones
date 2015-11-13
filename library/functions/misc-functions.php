<?php
	
	/*
	This file contains custom functions for theme working purposes

	Developed by: Leonid Belov (Leonite)
	URL: https://leonite.ru
	*/


	/**
	* L_GetImage - get image from post content by number
	* @param string $number 
	* @return string / image link with tag
	**/
	function L_getImage($number) {
		
		global $post;
		$img = '';
		$output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
		$img = $matches [1] [$number];

		return $img;
	
	}
	
	
	
	/**
	* L_GetUserIp - get the user ip
	* @return string
	**/
	function L_getUserIp() {
		
		if ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
		
			//check ip from share internet
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		
		} elseif ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
		
			//to check ip is pass from proxy
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		
		} else {
		
			$ip = $_SERVER['REMOTE_ADDR'];
		
		}
		
		return apply_filters( 'l_user_ip', $ip );
	
	}
	
	
	
	/**
	* L_GetFormattingContent - get content with formatting
	* @return string
	**/
	function L_getFormattingContent() {  
		
		$content = get_the_content($more_link_text);  
		$content = apply_filters('the_content', $content);  
		echo $content;
		
	}
	
	
	
	/**
	* L_CatHasParent - return true if category has parent
	* @param string $catid
	* @return bool
	**/
	function L_catHasParent($catid) {
		
		$category = get_category($catid);
		if ($category->category_parent > 0) {
			
			return true;
			
		}
		
		return false;
	
	}
	
	
	
	/**
	* L_ShowChildCats - return child cats of parent id
	* @param $parent - id of parent
	* @param $usename - boolean, if true return name of cat
	* @return array of child cats
	**/
	function L_showChildCats ($parent, $usename) {
		
		$idObj = get_category_by_slug($parent); 
		$parent_id = $idObj->term_id;
		$all_cats_ids = get_all_category_ids();
		
		foreach ( $all_cats_ids as $cat_id ) {
			
			if (cat_is_ancestor_of($parent_id, $cat_id)) {
			
				if ($usename) {
					
					$child_cats[] = get_cat_name($cat_id); 
				
				} else {
					
					$child_cats[] = (int)$cat_id;
				
				}
			
			}
		
		}
		
		if ($child_cats <> NULL) {
			
			sort( $child_cats ); 
		
		}
		
		return $child_cats; 
		
	}
	
	
	
	/**
	* L_GetCatId - get cat id by name
	* @param $cat_name - name of cat
	* @return int - if of cat
	**/
	function L_getCatId($cat_name) {
		
		$term = get_term_by('name', $cat_name, 'category');
		return $term->term_id;
	
	}
	
	
	
	/**
	* L_GetCatSlug - get cat slug by id
	* @param $cat_id - if of cat
	* @return string - slug of cat
	**/
	function L_getCatSlug($cat_id) {
	
		$cat_id = (int) $cat_id;
		$category = &get_category($cat_id);
		return $category->slug;
	
	}
	
	
	
	/**
	* L_GetIdBySlug - get id by slug
	* @param $page_slug - slug
	* @return string - id
	**/
	function L_getIdBySlug($page_slug) {
		
		$page = get_page_by_path($page_slug);
		
		if ($page) {
			
			return $page->ID;
		
		} else {
		
			return null;
		
		}
	
	}
	
	
	/**
	* L_SendSms - send sms via sms.ru service
	* @param string $msg - message to send
	* @param string $id - api id from sms.ru
	* @param string $numberto - number to send a message
	* @return bool
	**/
	function L_sendSms ($msg, $id, $numberto) {
		
		$result = false;
		
		$ch = curl_init("http://sms.ru/sms/send");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		curl_setopt($ch, CURLOPT_POSTFIELDS, array(

			"api_id"		=>	$id,
			"to"			=>	$numberto,
			"text"		=>	iconv("windows-1251","utf-8",$msg)

		));
		
		$body = curl_exec($ch);
		curl_close($ch);
		
		($body != false) ? $result = true : $result = false;
		
		return $result;
		
	}
	
	
	
	/**
	* detectBots - detect bots
	* @return bool
	**/
	function detectBots() {
		
		if (isset($_SERVER['HTTP_USER_AGENT']) && preg_match('/bot|crawl|slurp|spider/i', $_SERVER['HTTP_USER_AGENT'])) {
		
			return TRUE;
		
		} else {
			
			return FALSE;
		
		}
		
	}
	
	
	
	/**
	* checktags - check tags from current post
	* @return bool
	**/
	function checktags() {
		
		global $post;
		$tag_ids = wp_get_post_tags( $post->ID, array( 'fields' => 'ids' ) );
		
		if ( !empty($tag_ids) ) {
			
			return true;
		
		} else {
			
			return false;
		
		}
		
	}

	/**
	* is_blank - when you need to accept these as valid, non-empty values
	* @return value
	**/
	function is_blank($value) {
		
		return empty($value) && !is_numeric($value);
		
	}
	
	/**
	* L_GetPostCats - get the all categories of current post, but not uncategorized
	* @return categories in HTML markup or false
	**/
	
	function L_GetPostCats() {
		
		//global $post;
		
		$cats = get_the_category();
		$result = null;
				
				if ( sizeOf ( $cats ) > 0 ) {
				
					foreach( $cats as $category ) {
					
						if ( $category->cat_ID != 1 ) {
												
							$result .= '<a href="' . get_category_link( $category->term_id ) . '" title="' . sprintf( __( "View all posts in %s" ), $category->name ) . '" ' . '>' . $category->name.'</a>';
						
						}
					
					}
					
					return $result;
				
				} else {
					
					return false;
					
				}
				
	}
	
	/**
	* L_GetPostTags - get the all tags of current post
	* @return tags in HTML markup or false
	**/
	
	function L_GetPostTags() {
		
		//global $post;
		
		$tags = get_the_tags();
		$result = null;
				
				if ($tags != false) {
				
					foreach( $tags as $tag ) {
					
						//if ( $category->cat_ID != 1 ) {
												
							$result .= '<a href="' . get_tag_link( $tag->term_id ) . '" title="' . sprintf( __( "View posts by tag: %s" ), $tag->name ) . '" ' . '>' . $tag->name.'</a>';
						
						//}
					
					}
					
					return $result;
				
				} else {
					
					return false;
					
				}
				
	}

?>
