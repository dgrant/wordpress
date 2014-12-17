<?php
/**
 * @package Displace
 * @since Displace 1.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php displace_post_thumb();

		if ( is_single() ) :
			if ( get_the_title() ) : ?>
				<h1 class="entry-title"><?php the_title(); ?></h1>
			<?php endif;
		else :
			if ( get_the_title() ) : ?>
				<h1 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( __( 'Permalink to %s', 'displace' ), the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h1>
			<?php endif;
		endif; // End if is_single() ?>
	</header><!-- .entry-header -->

	<?php if ( is_search() ) : // Only display Excerpts for Search ?>
	<div class="entry-summary">
		<?php the_excerpt(); ?>
	</div><!-- .entry-summary -->
	<?php else : ?>
	<div class="entry-content">
		<?php if ( ! get_post_gallery() ) : ?>
			<?php
				the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'displace' ) );
				wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'displace' ), 'after' => '</div>' ) );
			?>
		<?php else : ?>
			<?php echo get_post_gallery(); ?>
		<?php endif; // is_single() ?>
	</div><!-- .entry-content -->
	<?php endif; ?>

	<footer class="entry-meta">
		<?php
			displace_comments_link();

			echo ( is_sticky() ? '<div class="sticky-icon icon icon-pinned">' . __( 'Featured', 'displace' ) . '</div>' : '' );

			displace_posted_on();
			displace_posted_by();
			displace_list_cats();

			$tags_list = get_the_tag_list( '<ul class="post-tags"><li>', '</li><li>', '</li></ul>' );

			if ( $tags_list ) :
				?><div class="tag-links"><?php printf($tags_list); ?></div><?php
			endif; // End if $tags_list

			edit_post_link( __( 'Edit', 'displace' ), '<div class="edit-link icon icon-edit">', '</div>' );
		?>
	</footer><!-- .entry-meta -->
</article><!-- #post-<?php the_ID(); ?> -->
