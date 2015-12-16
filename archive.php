<?php get_header(); ?>

			<div id="content">

				<div id="inner-content" class="wrap cf">
				
					<div id="archive-header">
					
						<?php
							the_archive_title( '<h1 class="archive-page-title">', '</h1>' );
							the_archive_description( '<div class="taxonomy-description">', '</div>' );
							
							
							//here we make custom wp_query request 
							
							
							
							
							?>
					
					</div>

						<main id="main" class="m-all t-2of3 d-5of7 ld-5of7 cf" role="main" itemscope itemprop="mainContentOfPage" itemtype="http://schema.org/Blog">

						
<?php 

//echo '<div class="inf_admin_bar">По запросу "<strong>'. wp_specialchars(stripslashes($_GET["sq"]), 1) .'</strong>" показано: ' . $sres . ' из <strong>' . $wp_query->found_posts . '</strong> (Запрос выполнен за: <strong>' . timer_stop(0) . '</strong> сек)</div>';

	wp_reset_query(); //reset old queries
	
	$limit = 10; //get_option('posts_per_page');
	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
	
	$args = array(
               
				'post_type' => 'post',    // get only posts
				'post_status' => array('publish'), //only publish posts
				'posts_per_page' => $limit, //limit per page
				'paged' => $paged, //pages count
				'order' => 'DESC' //sort
				
				);
	
	$wp_archive_query = new WP_Query($args);						
	$numofposts = $wp_archive_query->found_posts;
	
	if ($numofposts >= 10) {
		
		$numofposts = 10;

	}

	$num_cb = $wp_archive_query->post_count;
	$id_cb = $paged;
	$r_cb=1;
	$startNum_cb = $r_cb;
	$endNum_cb = $numofposts;
	
	if ($id_cb >=2) {
		
		$s_cb=$id_cb-1;
		$r_cb=($s_cb * 10) + 1;
		$startNum_cb=$r_cb;
		$endNum_cb=$startNum_cb + ($num_cb -1);
	
	}

	$num = $startNum_cb;

	?>


<? if ($wp_archive_query->have_posts()) :	while ($wp_archive_query->have_posts()) : $wp_archive_query->the_post(); ?>
							
							

							<article id="post-<?php the_ID(); ?>" <?php post_class( 'cf archive-grid' ); ?> role="article">
							
							<div class="article-number">
							
								<p class="byline entry-meta vcard number">
							
									<?echo '#&nbsp;' .$num ?>

								</p>
							
								<p class="byline entry-meta vcard">
							
									<?php //_e( "Опубликовано ", 'leonite' ); ?><time class="updated entry-time" datetime="<?php echo get_the_time(' Y/m/d g:i')  ?>" itemprop="datePublished"><?php echo get_the_time('F j, Y g:i') ?></time>

								</p>
							
							</div>

								<header class="article-header-archive ">

									<h1><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h1>

								</header>

							</article>
							 
							<?php 
							
							//next num of post
							$num = $num+1;
							endwhile;
							
							//reset post data after custom query
							wp_reset_postdata();
							
							?>
							
							
							
							<?php //if (have_posts()) : while (have_posts()) : the_post(); ?>

							

							<?php //endwhile; ?>

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
