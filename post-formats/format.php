
              <?php
                /*
                 * This is the default post format.
                 *
                 * So basically this is a regular post. if you don't want to use post formats,
                 * you can just copy ths stuff in here and replace the post format thing in
                 * single.php.
                 *
                 * The other formats are SUPER basic so you can style them as you like.
                 *
                 * Again, If you want to remove post formats, just delete the post-formats
                 * folder and replace the function below with the contents of the "format.php" file.
                */
              ?>

              <article id="post-<?php the_ID(); ?>" <?php post_class('cf'); ?> role="article" itemscope itemprop="blogPost" itemtype="http://schema.org/BlogPosting">

                <header class="article-header entry-header">

                  <h1 class="entry-title single-title" itemprop="headline" rel="bookmark"><?php the_title(); ?></h1>

                  <p class="byline entry-meta vcard">

                    <?php printf( __( 'Posted1', 'bonestheme' ).' %1$s %2$s',
                       /* the time the post was published */
                       '<time class="updated entry-time" datetime="' . get_the_time('Y-m-d') . '" itemprop="datePublished">' . get_the_time(get_option('date_format')) . '</time>',
                       /* the author of the post */
                       '<span class="by">'.__( 'by', 'bonestheme' ).'</span> <span class="entry-author author" itemprop="author" itemscope itemptype="http://schema.org/Person">' . get_the_author_link( get_the_author_meta( 'ID' ) ) . '</span>'
                    ); ?>

                  </p>

                </header> <?php // end article header ?>

                <section class="entry-content cf" itemprop="articleBody">
                  <?php
                    // the content (pretty self explanatory huh)
                    the_content();

                    /*
                     * Link Pages is used in case you have posts that are set to break into
                     * multiple pages. You can remove this if you don't plan on doing that.
                     *
                     * Also, breaking content up into multiple pages is a horrible experience,
                     * so don't do it. While there are SOME edge cases where this is useful, it's
                     * mostly used for people to get more ad views. It's up to you but if you want
                     * to do it, you're wrong and I hate you. (Ok, I still love you but just not as much)
                     *
                     * http://gizmodo.com/5841121/google-wants-to-help-you-avoid-stupid-annoying-multiple-page-articles
                     *
                    */
                    wp_link_pages( array(
                      'before'      => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'bonestheme' ) . '</span>',
                      'after'       => '</div>',
                      'link_before' => '<span>',
                      'link_after'  => '</span>',
                    ) );
                  ?>
                </section> <?php // end article section ?>

                <footer class="article-footer">
				
				<div class="share-this">
				
					<script type="text/javascript" src="//yastatic.net/share/share.js" charset="utf-8"></script><div class="yashare-auto-init" data-yashareL10n="ru" data-yashareType="large" data-yashareQuickServices="vkontakte,facebook,twitter,odnoklassniki,moimir" data-yashareTheme="counter"></div>
				
				</div>

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
				  
				<div class="post-author-vcard">
				
					<div class="author-image">

						<a href="<?php the_author_meta('user_url'); ?>"><?php echo get_avatar( get_the_author_meta('user_email'), '80', '' ); ?></a>


					</div>   

					<div class="author-bio">

						<h4>Written by <a href="**Author Website**">**Author Name**</a></h4>

						<p>Товарищи! реализация намеченных плановых заданий играет важную роль в формировании модели развития. Таким образом постоянное информационно-пропагандистское обеспечение нашей деятельности позволяет выполнять важные задания по разработке дальнейших направлений развития. Товарищи! новая модель организационной деятельности влечет за собой процесс внедрения и модернизации систем массового участия. Таким образом сложившаяся структура организации представляет собой интересный эксперимент проверки новых предложений.</p>

					</div>
				
				</div>

                </footer> <?php // end article footer ?>

                <?php comments_template(); ?>

              </article> <?php // end article ?>
