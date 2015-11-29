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
									
										<h1><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h1>
									
									</div>
									
									<p class="byline entry-meta vcard">
									<?php 
									
									$author_url = get_author_posts_url( get_the_author_meta( 'ID' ) );
									$author = get_the_author();
									
									?>
									
										<span class="entry-author author" itemprop="author" itemscope itemptype="http://schema.org/Person">
										
											<?php _e( "Автор", 'leonite' ); ?>&nbsp;<a href="<?php echo $author_url ?>"><?php echo $author; ?></a>
										
										</span>
										
										&nbsp;&#8226;&nbsp;
										
										<?php //_e( "Опубликовано ", 'leonite' ); ?><time class="updated entry-time" datetime="<?php echo get_the_time(' Y/m/d g:i')  ?>" itemprop="datePublished"><?php echo get_the_time('F j, Y g:i') ?></time>
										
										
										<?php 
											
											//$comments_count = get_comments_number( get_the_ID() );
											//echo the_ID();
											
											
											
											
									
											//echo "<span class='entry-author author' itemprop='author' itemscope itemptype='http://schema.org/Person'><a href='" . get_author_posts_url( get_the_author_meta( 'ID' ) ) . "'>" . _e( 'Автор: ', 'leonite' ) . get_the_author() . "</a></span>" . "&nbsp;&#8226;&nbsp;" . "<time class='updated entry-time' datetime='" . get_the_time(' Y/m/d g:i') . "' itemprop='datePublished'>" . get_the_time('Y/m/d') . _e( 'в' , 'leonite') . get_the_time ('g:i') . "</time>";

											//printf( esc_html__( "<span class='entry-author author' itemprop='author' itemscope itemptype='http://schema.org/Person'><a href='%1$s'>%2$s", 'leonite' ), $author_url, $author );
											
										?>
									
									</p>

								</header>
								
								<section class="entry-article-info">
									
									<div class="article-header-image">
								
										<a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_post_thumbnail( 'main-thumbnail' ); ?></a>
								
									</div>
									
									<?php 
									
										$cats = L_GetPostCats();
										
										if ( $cats != false ) {
										
											echo "<p class='byline entry-meta entrycats'><span class='glyphicon glyphicon-folder-open' style='margin-right:12px;color:#ccc;'></span>" . L_GetPostCats(True) . "</p>";
										
										}
										//$comments_count = get_comments_number( get_the_ID() );
										//echo the_ID();
									
										//echo "<span class='glyphicon glyphicon-comment' style='margin-right:4px;'></span>" . $comments_count;

									?>
									
								</section>

								<section class="entry-content-main cf">
									<?php the_excerpt(); ?>
								</section>

								<footer class="article-footer-main cf">

											<?php
				
												//get the tags of post
												$tags = L_GetPostTags();
				
												if ( $tags != false ) {
					
													echo "<div class='tags' style='margin:0'><span class='glyphicon glyphicon-tags' style='margin-right:12px;color:#ccc;'></span>" . $tags . "</div>";
					
												}
				  
											?>

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
				
				<img width="600" height="300" alt="b3277872d719e894f7f9e5dfa9624196" class="attachment-main-thumbnail wp-post-image" src="http://www.test1.ru/wpdb/wp-content/uploads/2015/11/b3277872d719e894f7f9e5dfa9624196.jpg">

			</div>


<?php get_footer(); ?>
