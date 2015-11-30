<!DOCTYPE html>

<!--[if lt IE 7]><html <?php language_attributes(); ?> class="no-js lt-ie9 lt-ie8 lt-ie7"><![endif]-->
<!--[if (IE 7)&!(IEMobile)]><html <?php language_attributes(); ?> class="no-js lt-ie9 lt-ie8"><![endif]-->
<!--[if (IE 8)&!(IEMobile)]><html <?php language_attributes(); ?> class="no-js lt-ie9"><![endif]-->
<!--[if gt IE 8]><!--> <html <?php language_attributes(); ?> class="no-js"><!--<![endif]-->

	<head>
	
		<meta charset="<?php bloginfo( 'charset' ); ?>">
		<meta name="viewport" content="width=device-width, initial-scale=1"/>
		<?php // force Internet Explorer to use the latest rendering engine available ?>
		<meta http-equiv="X-UA-Compatible" content="IE=edge">

		<title><?php wp_title( ' - ', true, 'right' ); ?></title>

		<link rel="profile" href="http://gmpg.org/xfn/11">
		<?php // mobile meta (hooray!) ?>
		<meta name="HandheldFriendly" content="True">
		<meta name="MobileOptimized" content="320">
		
		<?php // icons & favicons (for more: http://www.jonathantneal.com/blog/understand-the-favicon/) ?>
		<link rel="apple-touch-icon" href="<?php echo THEME_IMAGES ?>apple-touch-icon.png">
		<link rel="icon" href="<?php echo THEME_URI ?>/favicon.png">
		<!--[if IE]>
			<link rel="shortcut icon" href="<?php echo THEME_URI ?>/favicon.ico">
		<![endif]-->
		<?php // or, set /favicon.ico for IE10 win ?>
		<meta name="msapplication-TileColor" content="#f01d4f">
		<meta name="msapplication-TileImage" content="<?php echo THEME_IMAGES ?>win8-tile-icon.png">
        <meta name="theme-color" content="#121212">

		<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">

		<?php // wordpress head functions ?>
		<?php wp_head(); ?>
		<?php // end of wordpress head ?>

		<?php // drop Google Analytics Here ?>
		<?php // end analytics ?>

	</head>

	<body <?php body_class(); ?> itemscope itemtype="http://schema.org/WebPage">

			
			<div id="container">
			<header id="header" class="header site-header" role="banner" itemscope itemtype="http://schema.org/WPHeader">

				<div id="inner-header" class="wrap cf">
					
					<nav class="navbar navbar-default" role="navigation" itemscope itemtype="http://schema.org/SiteNavigationElement">
						

						
						<!-- Brand and toggle get grouped for better mobile display -->
						
						<div class="header-logo"> <!--logo-->
						
						<a href="<?php echo home_url(); ?>">
							
							<span class="headerpic" itemscope itemtype="http://schema.org/Organization">
							<img src="<? echo THEME_IMAGES ?>leonite_logo.png" alt="leonite.ru" title="<?php bloginfo('name'); ?>"/>
							</span>
						
						</a>
						
						</div>
						
						
						
						<div class="mobile-b-container"><!--search & mobile menu-->	
						
						<!--mobile menu here-->
						<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
								
								<span class="sr-only">Toggle navigation</span>
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
							
						</button>
						<!--mobile menu end-->
						
						
						<!--search block-->
						<div id="search-primary-toggle" class="search-button"><span class="glyphicon glyphicon-search" style="margin: 0px 4px;"></span></div>
						<!--end of search block-->
						
						
						<a href="<?php echo home_url(); ?>">
							
							<div class="logom"><img src="<? echo THEME_IMAGES ?>logom.png" alt="leonite.ru" title="<?php bloginfo('name'); ?>"/></div>
						
						</a>
						
						</div>
					
						
						<div class="header-menu"> <!--menu-->

					<?php
						
						wp_nav_menu( array(
                
							'menu' => __( 'The Main Menu', 'leonite' ),  // nav name
							'theme_location'    => 'main-nav',
							'depth'             => 2,
							'container'         => 'div',
							'container_class'   => 'collapse navbar-collapse',
							'container_id'      => 'bs-example-navbar-collapse-1',
							'menu_class'        => 'nav navbar-nav',
							'fallback_cb'       => 'wp_bootstrap_navwalker::fallback',
							'walker'            => new wp_bootstrap_navwalker())
						
						);
					?>
					
					</div>
					
					<!--search block
						<div class="searchb1">
						 <form class="searchbox">
							
							<input type="search" placeholder="Search......" name="search" class="searchbox-input" onkeyup="buttonUp();" required>
							<input type="submit" class="searchbox-submit" value="GO">
							<span class="searchbox-icon"><span class="glyphicon glyphicon-search" style="margin: 0px 4px;"></span></span>
						
						</form>-->
						<!--</div>
						<span class="glyphicon glyphicon-search" style="margin: 0px 4px;"></span>
						end of search block-->
						
						
					
					
						</nav>
						
						<div id="search-container-top">
					
						<form class="searchform-top">
						
						<label class="screen-reader-text" for="sq">Искать:</label>
						<input id="sq" type="search" class="search-top" value="" name="sq">
						<button id="searchsubmit" class="blue-btn" type="submit">Search</button>
						
						</form>
					
					</div>
						
					</div>
				
					



			</header>

			<a href="#" class="cd-top" title="<?php _e('Наверх страницы','leonite'); ?>"><span class="glyphicon glyphicon-arrow-up" style="margin: 0px 4px;"></span></a>
			
			