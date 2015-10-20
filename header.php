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

			<header class="header" role="banner" itemscope itemtype="http://schema.org/WPHeader">

				<div id="inner-header" class="wrap cf">
					
					<nav class="navbar navbar-default" role="navigation" itemscope itemtype="http://schema.org/SiteNavigationElement">
						
						<div class="container-fluid">
						
						<!-- Brand and toggle get grouped for better mobile display -->
						<div class="navbar-header">
							
							<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
								
								<span class="sr-only">Toggle navigation</span>
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
							
							</button>
						
						<a class="navbar-brand" href="<?php echo home_url(); ?>">
							
							<span class="header-logo" itemscope itemtype="http://schema.org/Organization">
							<img src="<? echo THEME_IMAGES ?>leonite_logo.jpg" class="headerlogo" alt="Information Fidelity" title="<?php bloginfo('name'); ?>"/>
							</span>
						
						</a>
						
						</div>

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
				
					</nav>
<div style="float:right;"><input type="text"></div>
					

				</div>

			</header>
