<?php
/**
 * The template part for displaying a message that posts cannot be found.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Displace
 * @since Displace 1.0
 */
?>

<article id="post-0" class="post no-results not-found">
	<header class="entry-header">
		<?php if ( is_search() ) : ?>

			<h1 class="entry-title"><?php _e( 'Nothing Found', 'displace' ); ?></h1>

		<?php elseif ( is_404() ) : ?>

			<h1 class="entry-title"><?php _e( 'Oops! That page can&rsquo;t be found.', 'displace' ); ?></h1>

		<?php endif; ?>
	</header><!-- .entry-header -->

	<div class="entry-content">
		<?php if ( is_home() ) : ?>

			<p><?php printf( __( 'Ready to publish your first post? <a href="%1$s">Get started here</a>.', 'displace' ), admin_url( 'post-new.php' ) ); ?></p>

		<?php elseif ( is_search() || is_404() ) : ?>

			<?php if ( is_search() ) : ?>

				<p><?php _e( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'displace' ); ?></p>

			<?php else : ?>

				<p><?php _e( 'It looks like nothing was found at this location. Maybe try one of the links below or a search?', 'displace' ); ?></p>

			<?php endif; ?>

			<?php get_search_form(); ?>

			<?php the_widget( 'WP_Widget_Recent_Posts', array( 'number' => 6 ) ); ?>

			<div class="widget widget_categories">
				<h2 class="widgettitle"><?php _e( 'Categories', 'displace' ); ?></h2>
				<ul>
				<?php wp_list_categories( array( 'orderby' => 'count', 'order' => 'DESC', 'show_count' => 1, 'title_li' => '', 'number' => 6, 'hierarchical' => 0 ) ); ?>
				</ul>
			</div><!-- .widget -->

			<div class="widget widget_archive">
				<h2 class="widgettitle"><?php _e( 'Archives', 'displace' ); ?></h2>
				<ul>
				<?php wp_get_archives( array( 'type' => 'monthly', 'format' => 'html', 'show_post_count' => 1, 'limit' => 6 ) ); ?>
				</ul>
			</div><!-- .widget -->

		<?php else : ?>

			<p><?php _e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'displace' ); ?></p>
			<?php get_search_form(); ?>

		<?php endif; ?>
	</div><!-- .entry-content -->
</article><!-- #post-0 .post .no-results .not-found -->