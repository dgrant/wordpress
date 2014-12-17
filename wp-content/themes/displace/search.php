<?php
/**
 * The template for displaying Search Results pages.
 *
 * @package Displace
 * @since Displace 1.0
 */

get_header(); ?>

	<main id="content" class="site-content" role="main">

	<?php if ( have_posts() ) : ?>

		<header class="page-header">
			<h1 class="page-title"><?php printf( __( 'Search Results for: %s', 'displace' ), '<span>' . get_search_query() . '</span>' ); ?></h1>
		</header><!-- .page-header -->

		<?php displace_content_nav( 'nav-above' ); ?>

		<?php /* Start the Loop */ ?>
		<?php while ( have_posts() ) : the_post(); ?>

			<?php get_template_part( 'content', 'search' ); ?>

		<?php endwhile; ?>

		<?php displace_content_nav( 'nav-below' ); ?>

	<?php else : ?>

		<?php get_template_part( 'no-results', 'search' ); ?>

	<?php endif; ?>

	</main><!-- #content .site-content -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>