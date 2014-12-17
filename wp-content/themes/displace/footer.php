<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the id=main div and all content after
 *
 * @package Displace
 * @since Displace 1.0
 */
?>

	<footer id="colophon" class="site-footer" role="contentinfo">
		<div class="site-info">
			<a href="http://wordpress.org/" title="<?php esc_attr_e( 'A Semantic Personal Publishing Platform', 'displace' ); ?>" rel="generator"><?php printf( __( 'Proudly powered by %s', 'displace' ), 'WordPress' ); ?></a>
			<span class="sep"> &bull; </span>
			<?php printf( __( '%1$s theme by %2$s', 'displace' ), 'Displace', '<a href="http://profiles.wordpress.org/awesome110/">' . __( 'Anton Kulakov', 'displace' ) . '</a>' ); ?>
		</div><!-- .site-info -->
	</footer><!-- #colophon .site-footer -->
</div><!-- #page .hfeed .site -->

<?php wp_footer(); ?>

</body>
</html>