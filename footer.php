			<footer id="footer" class="footer" role="contentinfo" itemscope itemtype="http://schema.org/WPFooter">

				<div id="inner-footer" class="wrap cf" style="margin:0 auto;">
				
					<nav role="navigation">
						
						<?php
							
							if (is_active_sidebar('footer-links-1')) {
								
								echo "<div id='footer-links1'>";
								
								dynamic_sidebar('footer-links-1');
							
								echo "</div>";
							
							}

							if (is_active_sidebar('footer-links-2')) {

								echo "<div id='footer-links2'>";
								
								dynamic_sidebar('footer-links-2');
						
								echo "</div>";
								
							}
						
						?>

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

					<?php
					
					$options = get_option( 'mytheme_options' );
					
					//var_dump($options);
											
						//echo $options['vk'];
						
					//check social links
					if ($options['vk'] or $options['instagram'] or $options['github']) {
						
						echo "<div class='social'>";
					
						if ($options['vk']) {
							
							echo "<a href='http://www.vk.com/". $options['vk'] ."' target='_blank' title='". __('Открыть в новой вкладке') ."'><img src='https://cdn1.iconfinder.com/data/icons/iconza-circle-social/64/697032-vkontakte-64.png'></a>";
						
						} 
						
						if ($options['instagram']) {
							
							echo "<a href='http://www.instagram.com/". $options['instagram'] ."' target='_blank' title='". __('Открыть в новой вкладке') ."'><img src='https://cdn1.iconfinder.com/data/icons/iconza-circle-social/64/697067-instagram-64.png'></a>";
						
						}
						
						if ($options['github']) {
							
							echo "<a href='http://www.github.com/". $options['github'] ."' target='_blank' title='". __('Открыть в новой вкладке') ."'><img src='https://cdn1.iconfinder.com/data/icons/iconza-circle-social/64/697061-github-64.png'></a>";
							
						}
					
						echo "</div>";
					
					} ?>

				</div>
				
					<div class="copyright">
				
						<div class="footer-bottom"><p>&copy; <?php echo date('Y'); ?> <?php bloginfo( 'name' ); ?>. Legal notices</p></div>
						
						<?php 
						
						if ($options['pageloadtime_checkbox']) {
							
							$t = timer_stop(1);
							
							$pagetime = sprintf( __('Page load info: %1$s queries in %2$s seconds', 'leonite'), '<strong>' . get_num_queries() . '</strong>', '<strong>' . $t . '</strong>' );
							
							echo "<div class='footer-bottom' style='text-align:center'>" . $pagetime . "</div>"; 
							
						}
						
						?>	

					</div>						

			</footer>

	

		<?php // all js scripts are loaded in library/core.php ?>
		<?php wp_footer(); ?>

	</body>

</html> <!-- end of site. what a ride! -->
