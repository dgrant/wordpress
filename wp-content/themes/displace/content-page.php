<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package Displace
 * @since Displace 1.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php displace_post_thumb();

		if ( get_the_title() ) : ?>
			<h1 class="entry-title"><?php the_title(); ?></h1>
		<?php endif; ?>
	</header><!-- .entry-header -->

	<div class="entry-content">
		<?php
			the_content();
			wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'displace' ), 'after' => '</div>' ) );
			edit_post_link( __( 'Edit', 'displace' ), '<div class="edit-link icon icon-edit">', '</div>' );
		?>
	</div><!-- .entry-content -->
</article><!-- #post-<?php the_ID(); ?> -->
