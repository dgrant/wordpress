<?php
/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features
 *
 * @package Displace
 * @since Displace 1.0
 */
 
function displace_comment_date( $date ) {
	$current = current_time('timestamp');
	$month_ago = date( 'U', mktime( 0, 0, 0, date( "m", $current ) - 1, date( "d", $current ), date( "Y", $current ) ) );
	$human_date = date( get_option('date_format'), $date );

	if ( $date > $month_ago )
		$output = sprintf( __( '%s ago', 'displace' ), human_time_diff( $date, current_time('timestamp') ) );
	else
		$output = $human_date;

	echo $output;
}

if ( ! function_exists( 'displace_comment' ) ) :

/**
 * Template for comments and pingbacks.
 *
 * Used as a callback by wp_list_comments() for displaying the comments.
 *
 * @since Displace 1.0
 */
function displace_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
		case 'pingback' :
		case 'trackback' :
	?><li class="post pingback" title="<?php _e( 'Pingback', 'displace' ); ?>"><span class="screen-reader-text"><?php _e( 'Pingback', 'displace' ); ?></span> <?php comment_author_link(); ?><?php edit_comment_link( __( 'Edit', 'displace' ), ' <span class="sep">&bull;</span> ' ); ?>
	<?php
			break;
		default :
	?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
	<article id="comment-<?php comment_ID(); ?>" class="comment">
		<footer>
			<div class="comment-author vcard">
				<?php echo get_avatar( $comment, 50, null, get_comment_author() ); ?>
				<cite class="fn"><?php echo get_comment_author_link(); ?></cite>
			</div><!-- .comment-author .vcard -->

			<div class="comment-meta"><a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>" class="comment-date">
				<time datetime="<?php comment_time( 'c' ); ?>" title="<?php printf( __( '%1$s at %2$s', 'displace' ), get_comment_date(), get_comment_time() ); ?>"><?php displace_comment_date( get_comment_date('U') ); ?></time>
			</a></div><!-- .comment-meta -->
		</footer>

		<div class="comment-content"><?php comment_text(); ?></div>

		<?php if ( $comment->comment_approved == '0' ) : ?>
		<div class="comment-unapproved"><?php _e( 'Your comment is awaiting moderation.', 'displace' ); ?></div>
		<?php endif; ?>

		<div class="reply">
			<?php
				comment_reply_link( array_merge( $args, array( 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) );
				edit_comment_link( __( 'Edit', 'displace' ), ' <span class="sep">&bull;</span> ' );
			?>
		</div><!-- .reply -->
	</article><!-- #comment-## -->
	<?php
			break;
	endswitch;
}
endif; // ends check for displace_comment()

/**
 * Prints HTML with meta information for the current post-date/time.
 *
 * @since Displace 1.0
 */
function displace_posted_on() {
	printf( '<div class="posted-on icon icon-month">' . __( 'Posted on %s', 'displace' ) . '</div>',
		sprintf( '<a href="%1$s" title="%2$s" rel="bookmark"><time class="entry-date" datetime="%3$s">%4$s</time></a>',
			esc_url( get_permalink() ),
			esc_attr( sprintf( ( the_title_attribute( 'echo=0' ) ? __( 'Permalink to %s', 'displace' ) : __( 'Permalink to post', 'displace' ) ), the_title_attribute( 'echo=0' ) ) ),
			esc_attr( get_the_date( 'c' ) ),
			esc_html( get_the_date() )
		)
	);
}

/**
 * Prints HTML with meta information for the post author.
 *
 * @since Displace 1.0
 */
function displace_posted_by() {
	printf( '<div class="byline icon icon-user">' . __( 'By <span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s" rel="author">%3$s</a></span>', 'displace' ) . '</div>',
		esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
		esc_attr( sprintf( __( 'View all posts by %s', 'displace' ), get_the_author() ) ),
		esc_html( get_the_author() )
	);
}

/**
 * Prints HTML with meta information for the post author.
 *
 * @since Displace 1.0
 */
function displace_comments_link() {
	if ( ! post_password_required() && ( comments_open() || get_comments_number() > 0 ) ) {

		echo '<div class="comments-link icon icon-comment">';

		printf( '<a href="%1$s" title="%2$s">%3$s</a>',
			esc_url( get_permalink() . ( get_comments_number() > 0 ? '#comments' : '#respond' ) ),
			esc_attr( sprintf( ( the_title_attribute( 'echo=0' ) ? __( 'Comment on %s', 'displace' ) : __( 'Comment on post', 'displace' ) ), the_title_attribute( 'echo=0' ) ) ),
			( get_comments_number() > 0 ? sprintf( _n( '%1$s comment', '%1$s comments', get_comments_number(), 'displace' ), number_format_i18n( get_comments_number() ) ) : __( 'Leave a comment', 'displace' ) )
		);

		echo '</div>';
	}
}
/**
 * Returns true if a blog has more than 1 category
 *
 * @since Displace 1.0
 */
function displace_categorized_blog() {
	if ( false === ( $all_the_cool_cats = get_transient( 'all_the_cool_cats' ) ) ) {
		// Create an array of all the categories that are attached to posts
		$all_the_cool_cats = get_categories( array(
			'hide_empty' => 1
		) );

		// Count the number of categories that are attached to the posts
		$all_the_cool_cats = count( $all_the_cool_cats );

		set_transient( 'all_the_cool_cats', $all_the_cool_cats );
	}

	if ( '1' != $all_the_cool_cats ) {
		// This blog has more than 1 category so displace_categorized_blog should return true
		return true;
	} else {
		// This blog has only 1 category so displace_categorized_blog should return false
		return false;
	}
}

/**
 * Flush out the transients used in displace_categorized_blog
 *
 * @since Displace 1.0
 */
function displace_category_transient_flusher() {
	// Like, beat it. Dig?
	delete_transient( 'all_the_cool_cats' );
}
add_action( 'edit_category', 'displace_category_transient_flusher' );
add_action( 'save_post', 'displace_category_transient_flusher' );

/**
 * Lists categories while omitting the default "uncategorized".
 *
 * @since Displace 1.0
 */
function displace_list_cats() {
	if( displace_categorized_blog() ) {
		$list = '';
		foreach( ( get_the_category() ) as $category ) {
			$list .= '<li><a rel="category tag" href="' . get_category_link( $category->term_id ) . '" title="' . sprintf( __( "View all posts in %s", 'displace' ), $category->name ) . '" ' . '>' . $category->name.'</a></li>';
		}
		if ( $list )
			echo '<div class="cat-links icon icon-category"><ul class="post-categories">' . $list . '</ul></div>';
	}
}

/**
 * Post thumbnail
 *
 * @since Displace 1.0
 */
function displace_post_thumb() {
	if ( has_post_thumbnail() ) : ?>
		<div class="post-thumb">
			<?php if ( is_single() or is_page() ) : ?>
				<?php the_post_thumbnail(); ?>
			<?php else : ?>
				<a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( __( 'Permalink to %s', 'displace' ), the_title_attribute( 'echo=0' ) ) ); ?>"><?php the_post_thumbnail(); ?></a>
			<?php endif; ?>
		</div>
	<?php endif;
}

/**
 * Prints the attached image with a link to the next attached image.
 */
function displace_the_attached_image() {
	$post = get_post();
	$next_attachment_url = wp_get_attachment_url();

	/**
	 * Grab the IDs of all the image attachments in a gallery so we can get the
	 * URL of the next adjacent image in a gallery, or the first image (if
	 * we're looking at the last image in a gallery), or, in a gallery of one,
	 * just the link to that image file.
	 */
	$attachment_ids = get_posts( array(
		'post_parent'    => $post->post_parent,
		'fields'         => 'ids',
		'numberposts'    => -1,
		'post_status'    => 'inherit',
		'post_type'      => 'attachment',
		'post_mime_type' => 'image',
		'order'          => 'ASC',
		'orderby'        => 'menu_order ID'
	) );

	// If there is more than 1 attachment in a gallery...
	if ( count( $attachment_ids ) > 1 ) {
		foreach ( $attachment_ids as $attachment_id ) {
			if ( $attachment_id == $post->ID ) {
				$next_id = current( $attachment_ids );
				break;
			}
		}

		if ( $next_id ) { // get the URL of the next image attachment...
			$next_attachment_url = get_attachment_link( $next_id );
		} else { // or get the URL of the first image attachment.
			$next_attachment_url = get_attachment_link( array_shift( $attachment_ids ) );
		}
	}

	printf( '<a href="%1$s" title="%2$s" rel="attachment">%3$s</a>',
		esc_url( $next_attachment_url ),
		the_title_attribute( array( 'echo' => false ) ),
		wp_get_attachment_image( $post->ID, array( 1000, 9999 ) )
	);
}

function displace_post_image() {
	$post = get_post();
	$content = $post->post_content;
	$images_in_content = array();
	preg_match( '/src="([^"]*)"/i', $content, $images_in_content ) ;

	$images = get_children( 'post_type=attachment&post_mime_type=image&order=asc&orderby=menu_order&post_parent=' . $post->ID );

	if ($images) {
		foreach ( $images as $attachment_id => $attachment ) {
			$image = wp_get_attachment_image_src( $attachment_id, 'full' ); 

			if ( in_array( $image[0], $images_in_content ) ) {
				echo wp_get_attachment_link(  $attachment_id, 'full' );
			}
		}
	}
}

function displace_get_adjacent_image_link($prev = true, $text = false) {
	$post = get_post();
	$attachments = array_values( get_children( array( 'post_parent' => $post->post_parent, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => '', 'order' => 'ASC', 'orderby' => 'menu_order ID' ) ) );

	foreach ( $attachments as $k => $attachment )
		if ( $attachment->ID == $post->ID )
			break;

	$k = $prev ? $k - 1 : $k + 1;

	$output = $attachment_id = null;
	if ( isset( $attachments[ $k ] ) ) {
		$attachment_id = $attachments[ $k ]->ID;
		$output = wp_get_attachment_link( $attachment_id, 'full', true, false, $text );
	}

	$adjacent = $prev ? 'previous' : 'next';
	return apply_filters( "displace_{$adjacent}_image_link", $output, $attachment_id, $text );
}

/**
 * Display navigation to next/previous pages when applicable
 *
 * @since Displace 1.0
 */
function displace_content_nav( $nav_id ) {
	global $wp_query, $post;

	// Hide top navigation on first page
	if ( ! is_single() && ! is_paged() && $nav_id == 'nav-above' )
		return;

	// Don't print empty markup on single pages if there's nowhere to navigate.
	if ( is_single() ) {
		if ( ! is_attachment() ) {
			$previous = get_adjacent_post(false, '', true);
			$next = get_adjacent_post(false, '', false);
		} else {
			$previous = displace_get_adjacent_image_link( true, __( '<span class="meta-nav">&larr;</span> Previous attachment', 'displace' ) );
			$next = displace_get_adjacent_image_link( false, __( 'Next attachment <span class="meta-nav">&rarr;</span>', 'displace' ) );
			$previous = ( $previous ) ? '<div class="nav-previous">' . $previous . '</div>' : null;
			$next = ( $next ) ? '<div class="nav-next">' . $next . '</div>' : null;
		}

		if ( ! $next && ! $previous )
			return;
	}

	// Don't print empty markup in archives if there's only one page.
	if ( $wp_query->max_num_pages < 2 && ( is_home() || is_archive() || is_search() ) )
		return;

	$nav_class = 'site-navigation paging-navigation';
	if ( is_single() )
		$nav_class = 'site-navigation post-navigation';
	?>
	<nav role="navigation" id="<?php echo $nav_id; ?>" class="<?php echo $nav_class; ?>">
		<h1 class="assistive-text"><?php _e( 'Post navigation', 'displace' ); ?></h1>


	<?php if ( is_single() ) : // navigation links for single posts ?>

		<?php if ( ! is_attachment() ) :
			previous_post_link( '<div class="nav-previous">%link</div>', '<span class="meta-nav">&larr;</span> %title' );
			next_post_link( '<div class="nav-next">%link</div>', '%title <span class="meta-nav">&rarr;</span>' );
		else : // navigation links for attachment pages
			echo $previous;
			echo $next;
		endif; ?>

	<?php elseif ( $wp_query->max_num_pages > 1 && ( is_home() || is_archive() || is_search() ) ) : // navigation links for home, archive, and search pages ?>

		<?php if ( get_next_posts_link() ) : ?>
			<div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts', 'displace' ) ); ?></div>
		<?php endif; ?>

		<?php if ( get_previous_posts_link() ) : ?>
			<div class="nav-next"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'displace' ) ); ?></div>
		<?php endif; ?>

	<?php endif; ?>

	</nav><!-- #<?php echo $nav_id; ?> -->
	<?php
}