<?php
/**
 * Template Name: Board Templates & Content
 */
?>

<?php get_header(); ?>

		<div id="container" class="no-sidebar">
			<div id="content">
        <div id="pcw-boards" class="row-inner row-inner-fixed">
          <?php
            $board_templates = _db_getBoardTemplates();
            foreach ( $board_templates as $board )
            {
              ?>
            	<div class="board">
                <div class="logo">
                  <img src="<?= $board->logo_url ?>" onerror="this.onerror=null; this.src='<?= $board->logo_url ?>'">
                  <span><?= $board->name ?></span>
                </div>
                <div class="photo">
                  <img src="<?= $board->photo_url ?>"/>
                </div>
              </div>
              <?php
            }
          ?>
        </div>

				<?php while ( have_posts() ) : the_post(); ?>

				<article id="post-<?php the_ID(); ?>" <?php post_class('clearfix'); ?> role="article">
					<div class="entry-content clearfix">
						<?php the_content(); ?>
					</div><!-- .entry-content -->
				</article>

				<?php if (ot_get_option('page_comments') != 'off') {
					echo '<div class="row-inner"><div class="vc_span12 wpb_column column_container">';
						comments_template( '', true );
					echo '</div></div>';
				} ?>
				<?php endwhile; ?>

			</div><!-- #content -->
		</div><!-- #container -->

<?php get_footer(); ?>
