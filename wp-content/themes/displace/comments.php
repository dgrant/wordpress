<?php
/**
 * The template for displaying Comments.
 *
 * The area of the page that contains both current comments
 * and the comment form. The actual display of comments is
 * handled by a callback to displace_comment() which is
 * located in the functions.php file.
 *
 * @package Displace
 * @since Displace 1.0
 *
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
	if ( post_password_required() )
		return;
?>

<?php if ( have_comments() or comments_open() ) : ?>
<div id="comments" class="comments-area">
<?php endif; ?>

<?php if ( have_comments() ) : ?>
	<h2 class="comments-title">
		<?php
			if ( get_the_title() ) {
				printf( _n( '%1$s thought on %2$s', '%1$s thoughts on %2$s', get_comments_number(), 'displace' ),
					number_format_i18n( get_comments_number() ), '<q>' . get_the_title() . '</q>' );
			} else {
				_e( 'Thoughts', 'displace' );
			}
		?>
	</h2>

	<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // are there comments to navigate through ?>
	<nav role="navigation" id="comment-nav-above" class="site-navigation comment-navigation">
		<h1 class="assistive-text"><?php _e( 'Comment navigation', 'displace' ); ?></h1>
		<div class="nav-previous"><?php previous_comments_link( __( '&larr; Older Comments', 'displace' ) ); ?></div>
		<div class="nav-next"><?php next_comments_link( __( 'Newer Comments &rarr;', 'displace' ) ); ?></div>
	</nav><!-- #comment-nav-before .site-navigation .comment-navigation -->
	<?php endif; // check for comment navigation ?>

	<ol class="commentlist">
		<?php wp_list_comments( array( 'callback' => 'displace_comment' ) ); ?>
	</ol><!-- .commentlist -->

	<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // are there comments to navigate through ?>
	<nav role="navigation" id="comment-nav-below" class="site-navigation comment-navigation">
		<h1 class="assistive-text"><?php _e( 'Comment navigation', 'displace' ); ?></h1>
		<div class="nav-previous"><?php previous_comments_link( __( '&larr; Older Comments', 'displace' ) ); ?></div>
		<div class="nav-next"><?php next_comments_link( __( 'Newer Comments &rarr;', 'displace' ) ); ?></div>
	</nav><!-- #comment-nav-below .site-navigation .comment-navigation -->
	<?php endif; // check for comment navigation ?>

<?php endif; // have_comments() ?>

<?php
	// If comments are closed and there are comments, let's leave a little note, shall we?
	if ( ! comments_open() && '0' != get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) :
?>
	<p class="nocomments"><?php _e( 'Comments are closed', 'displace' ); ?></p>
<?php endif; ?>

<?php

$commenter = wp_get_current_commenter();
$req = get_option( 'require_name_email' );
$aria_req = ( $req ? 'aria-required="true"' : '' );

$comment_form_args = array(
	'comment_notes_before' => '',
	'comment_field' => '<p class="comment-form-comment"><textarea id="comment" name="comment" cols="45" rows="8" aria-required="true" placeholder="' . __( 'Your comment', 'displace' ) . '"></textarea></p>',
	'title_reply' => __( 'Leave a Reply', 'displace' ),
	'title_reply_to' => __( 'Leave a Reply to %s', 'displace' ),
	'cancel_reply_link' => __( 'Cancel reply', 'displace' ),
	'label_submit' => __( 'Send', 'displace' ),
	'fields' => apply_filters( 'comment_form_default_fields', array(

	'author' =>
		'<p class="comment-form-author">' .
			'<label for="author" class="assistive-text">' . __( 'Name', 'displace' ) . '</label> ' .
			/*( $req ? '<span class="required">*</span> ' : '' ) .*/
			'<input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" placeholder="' . __( 'Name', 'displace' ) . '" size="30" ' . $aria_req . ' />' .
		'</p>',

		'email' =>
		'<p class="comment-form-email">' .
			'<label for="email" class="assistive-text">' . __( 'Email', 'displace' ) . '</label> ' .
			/*( $req ? '<span class="required">*</span> ' : '' ) .*/
			'<input id="email" name="email" type="email" value="' . esc_attr(  $commenter['comment_author_email'] ) . '" placeholder="' . __( 'Email', 'displace' ) . '" size="30" ' . $aria_req . ' />' .
		'</p>',

		'url' =>
		'<p class="comment-form-url">' .
			'<label for="url" class="assistive-text">' . __( 'Website', 'displace' ) . '</label> ' .
			'<input id="url" name="url" type="url" value="' . esc_attr( $commenter['comment_author_url'] ) . '" placeholder="' . __( 'Website', 'displace' ) . '" size="30" />' .
		'</p>'
	) )
);

comment_form($comment_form_args);

// If comments are closed and there are comments, let's leave a little note, shall we?
if ( have_comments() or comments_open() ) : ?>
</div><!-- #comments .comments-area -->
<?php endif; ?>
