<?php
/**
 * The template for displaying image attachments.
 *
 * @package Displace
 * @since Displace 1.0
 */

get_header(); ?>

	<main id="content" class="site-content" role="main">

	<?php while ( have_posts() ) : the_post(); ?>

		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>

			<div class="entry-content">

				<div class="entry-attachment">
					<?php the_attachment_link(); ?>
				</div><!-- .entry-attachment -->

				<div class="entry-description">
					<?php if ( has_excerpt() ) : ?>
					<div class="entry-caption">
						<?php the_excerpt(); ?>
					</div><!-- .entry-caption -->
					<?php endif; ?>

					<?php
						the_content();
						wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'displace' ), 'after' => '</div>' ) );
					?>
				</div><!-- .entry-description -->

			</div><!-- .entry-content -->

			<footer class="entry-meta">
				<?php
					displace_comments_link();

					if ( $post->post_parent ) {
						$parent_title = get_the_title( $post->post_parent );
						if ( empty( $parent_title ) )
							$parent_title = __( '(no title)', 'displace' );

						printf( '<div class="icon icon-document">' . __( 'Attached to <a href="%1$s" title="Return to %2$s">%3$s</a>', 'displace' ) . '</div>',
							esc_url( get_permalink( $post->post_parent ) ),
							esc_attr( strip_tags( $parent_title ) ),
							$parent_title
						);
					}

					edit_post_link( __( 'Edit', 'displace' ), '<div class="edit-link icon icon-edit">', '</div>' );
				?>
			</footer><!-- .entry-meta -->
		</article><!-- #post-<?php the_ID(); ?> -->

		<?php displace_content_nav( 'nav-image' ); ?>

		<?php
			// If comments are open or we have at least one comment, load up the comment template
			if ( comments_open() || '0' != get_comments_number() )
				comments_template();
		?>

	<?php endwhile; // end of the loop. ?>

	</main><!-- #content .site-content -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>