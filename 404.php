<?php get_header(); ?>

			<div id="content">

				<div id="inner-content" class="wrap cf">

					<main id="main" class="m-all cf" role="main" itemscope itemprop="mainContentOfPage" itemtype="http://schema.org/Blog">

						<article id="post-not-found" class="hentry cf">

							<header class="article-header">

								<h1 style="text-align:center;"><?php _e( 'Epic 404 - Article Not Found', 'leonitetheme' ); ?></h1>

							</header>

							<section class="entry-content">

								<p style="text-align:center;"><?php _e( 'The article you were looking for was not found, but maybe try looking again!', 'leonitetheme' ); ?></p>
								<div class="grumpycat"><img src="<? echo THEME_IMAGES ?>404.jpg" alt="leonite.ru" title="<?php bloginfo('name'); ?>" class="notfound" /></div>

							</section>

							<section class="search" style="text-align:center;">

									<p><?php get_search_form(); ?></p>

							</section>

							<footer class="article-footer">

<!-- nothing here-->

							</footer>

						</article>

					</main>

				</div>

			</div>

<?php get_footer(); ?>
