<?php
	
	/*
	This file contains custom functions for theme working purposes

	Developed by: Leonid Belov (Leonite)
	URL: http://leonite.ru
	*/


	/**
	* L_GetImage - get image from post content by number
	* @param string $number 
	* @return string / image link with tag
	**/
	
	function L_GetImage($number) {
		
		global $post;
		$img = '';
		$output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
		$img = $matches [1] [$number];

		return $img;
	
	}
	
	

?>
