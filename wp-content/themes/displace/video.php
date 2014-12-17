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
			<div class="entry-content">

				<div class="entry-attachment">
				<?php
					$stored_content_width = $content_width;
					$content_width = 960; /* make sure video player is as wide as the whole page */

					echo do_shortcode( '[video src="' . wp_get_attachment_url() . '"][/video]' );

					$content_width = $stored_content_width;
					unset( $stored_content_width );
				?>
				</div><!-- .entry-attachment -->

				<div class="entry-description">
					<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>

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

					$metadata = wp_get_attachment_metadata();

					printf( '<div class="icon icon-youtube">' . __( 'Video resolution: <b>%1$s &times; %2$s</b>', 'displace' ) . '</div>',
						$metadata['width'],
						$metadata['height']
					);
					
					$file_size = number_format ( ( $metadata['filesize'] / 1000000 ), 1, '.', '' ) . ' ' . __( 'MB', 'displace' );

					printf( '<div class="">' . __( 'File size: <b>%1$s</b>', 'displace' ) . '</div>', $file_size );

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