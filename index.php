<?php get_header(); ?>

			<div id="content">

				<div id="inner-content" class="wrap cf">

						<main id="main" class="m-all t-2of3 d-5of7m cf" role="main" itemscope itemprop="mainContentOfPage" itemtype="http://schema.org/Blog">

						<div class="grid-container">
						
							<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
							
								<div class="t-c">

							<article id="post-<?php the_ID(); ?>" <?php post_class( 'cf' ); ?> role="article">

								<header class="article-header-main">
								
								
									
									<div class="article-header-info">
									
										<div class="article-header-image">
								
										<?php the_post_thumbnail( 'leonite-thumb-60' ); ?>
								
									</div>

									<h1><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h1>
									<p class="byline entry-meta vcard">
                                    
									<?php echo "<time class='updated entry-time' datetime='" . get_the_time('Y-m-d') . "' itemprop='datePublished'>" . get_the_time(get_option('date_format')) . "</time>" . "&nbsp;/&nbsp;" . "<span class='entry-author author' itemprop='author' itemscope itemptype='http://schema.org/Person'>" . get_the_author_link( get_the_author_meta( 'ID' ) ) . "</span>";

									?>
									
									</p>
									
									</div>

								</header>

								<section class="entry-content-main cf">
									<?php the_excerpt(); ?>
								</section>

								<footer class="article-footer-main cf">
									
											<p class="footer-comment-count">
										
												<?php comments_number( __( '<span>No</span> Comments', 'leonitetheme' ), __( '<span>One</span> Comment', 'leonitetheme' ), __( '<span>%</span> Comments', 'leonitetheme' ) );?>
									
											</p>
											
											<?php
				
												//get the categories of post
												$cats = L_GetPostCats();
				
												if ( $cats != false ) {
					
												echo "<div class='post-categories'><span class='glyphicon glyphicon-bookmark cat-glyph'></span>" . $cats . "</div>";
					
												}
				
												//get the tags of post
												$tags = L_GetPostTags();
				
												if ( $tags != false ) {
					
												echo "<div class='tags'><span class='glyphicon glyphicon-tags tags-glyph'></span>" . $tags . "</div>";
					
												}
				  
											?>


											<?php //printf( '<p class="footer-category">' . __('filed under', 'leonitetheme' ) . ': %1$s</p>' , get_the_category_list(', ') ); ?>

											<?php //the_tags( '<p class="footer-tags tags"><span class="tags-title">' . __( 'Tags:', 'leonitetheme' ) . '</span> ', ', ', '</p>' ); ?>

										</footer>

							</article>
							
							</div>

							<?php endwhile; ?>
							
							</div>

									<?php leonite_pagination(); ?>

							<?php else : ?>

									<article id="post-not-found" class="hentry cf">
											<header class="article-header">
												<h1><?php _e( 'Oops, Post Not Found!', 'leonitetheme' ); ?></h1>
										</header>
											<section class="entry-content">
												<p><?php _e( 'Uh Oh. Something is missing. Try double checking things.', 'leonitetheme' ); ?></p>
										</section>
										<footer class="article-footer">
												<p><?php _e( 'This is the error message in the index.php template.', 'leonitetheme' ); ?></p>
										</footer>
									</article>

							<?php endif; ?>


						</main>

					<?php get_sidebar(); ?>

				</div>

			</div>


<?php get_footer(); ?>
