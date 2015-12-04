<?php get_header(); ?>

			<div id="content">

				<div id="inner-content" class="wrap cf">

						<main id="main" class="m-all t-2of3 d-5of7 cf" role="main" itemscope itemprop="mainContentOfPage" itemtype="http://schema.org/Blog">

							<?php
							the_archive_title( '<h1 class="archive-page-title">', '</h1>' );
							the_archive_description( '<div class="taxonomy-description">', '</div>' );
							
							
							//here we make custom wp_query request 
							
							
							
							
							?>
							
							
							
							<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

							<article id="post-<?php the_ID(); ?>" <?php post_class( 'cf archive-grid' ); ?> role="article">
							
							<div class="article-number">
							
								<p class="byline entry-meta vcard number">
							
									01

								</p>
							
								<p class="byline entry-meta vcard">
							
									<?php //_e( "Опубликовано ", 'leonite' ); ?><time class="updated entry-time" datetime="<?php echo get_the_time(' Y/m/d g:i')  ?>" itemprop="datePublished"><?php echo get_the_time('F j, Y g:i') ?></time>

								</p>
							
							</div>

								<header class="article-header-archive ">

									<h1><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h1>

								</header>

							</article>

							<?php endwhile; ?>

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
												<p><?php _e( 'This is the error message in the archive.php template.', 'leonitetheme' ); ?></p>
										</footer>
									</article>

							<?php endif; ?>

						</main>

					<?php get_sidebar(); ?>

				</div>

			</div>

<?php get_footer(); ?>
