			<footer class="footer" role="contentinfo" itemscope itemtype="http://schema.org/WPFooter">

				<div id="inner-footer" class="wrap cf" style="margin:0 auto;">
				
					<nav role="navigation">
					
						<div id="footer-links1">
						
						<?php
							
							if(is_active_sidebar('footer-links-1')){
								
								dynamic_sidebar('footer-links-1');
							
							}
						
						?>
						
						</div>


						<div id="footer-links2">
						
						<?php

							if(is_active_sidebar('footer-links-2')){

								dynamic_sidebar('footer-links-2');
						
							}
						
						?>

						</div>
						
						<?php /*wp_nav_menu(array(
    					'container' => 'div',                           // enter '' to remove nav container (just make sure .footer-links in _base.scss isn't wrapping)
    					'container_class' => 'footer-links cf',         // class of container (should you choose to use it)
    					'menu' => __( 'Footer Links', 'leonite' ),   // nav name
    					'menu_class' => 'double',            // adding custom nav class
    					'theme_location' => 'footer-links',             // where it's located in the theme
    					'before' => '',                                 // before the menu
    					'after' => '',                                  // after the menu
    					'link_before' => '',                            // before each link
    					'link_after' => '',                             // after each link
    					'depth' => 0,                                   // limit the depth of the nav
    					'fallback_cb' => 'leonite_footer_links_fallback'  // fallback function
						)); */?>
						
					</nav>

					<div class="social">
					
						<img src="https://cdn1.iconfinder.com/data/icons/iconza-circle-social/64/697032-vkontakte-64.png">
						<img src="https://cdn1.iconfinder.com/data/icons/iconza-circle-social/64/697067-instagram-64.png">
						<img src="https://cdn1.iconfinder.com/data/icons/iconza-circle-social/64/697061-github-64.png">
						<img src="https://cdn1.iconfinder.com/data/icons/iconza-circle-social/64/697035-wordpress-64.png">
					
					</div>

				</div>
				
					<div class="copyright">
				
						<div class="footer-bottom"><p>&copy; <?php echo date('Y'); ?> <?php bloginfo( 'name' ); ?>. Legal notices</p></div>
						<div class="footer-bottom"><?php echo L_PageLoadTime(); ?></div>
				
					</div>

			</footer>

		</div>

		<?php // all js scripts are loaded in library/core.php ?>
		<?php wp_footer(); ?>

	</body>

</html> <!-- end of site. what a ride! -->
