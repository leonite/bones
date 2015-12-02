						
				
				<div id="sidebar1" class="sidebar m-all t-1of4 d-2of7 ld-2of7 last-col cf" role="complementary">
				
				<?php 

					if ( is_active_sidebar( 'sidebar1' ) ) : 

						dynamic_sidebar( 'sidebar1' );

					else :
					
							/*
							 * This content shows up if there are no widgets defined in the backend.
							*/
							
				?>

						<div class="no-widgets">
						
							<p><?php _e( 'This is a widget ready area. Add some and they will appear here.', 'leonite' );  ?></p>
						
						</div>

				<?php endif; ?>

				</div>
