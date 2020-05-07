<?php
/**
 * Template Name: Success
 *
 * @package ORC
 */

namespace ORC;

$data = array(
	'email-to' => '',
	'success'  => '',
);

session_start();
if ( isset( $_SESSION['post-data'] ) ) {

	foreach ( $_SESSION['post-data'] as $index => $value ) {
		$data[ $index ] = $value;
	}

	unset( $_SESSION['post-data'] );
}

$to = Contact::get_email_to( $data['email-to'] );

get_header();

echo '<main id="site-content" role="main">';

if ( have_posts() ) {
	the_post();

	\ob_start();

	echo '<article class="post-<?php get_the_ID(); ?> page type-page status-publish hentry" id="post-' . get_the_ID() . '">';
	echo '<header class="entry-header has-text-align-center header-footer-group">';
	echo '<div class="entry-header-inner section-inner medium">';
	echo '<h1 class="entry-title">Email successfully sent</h1>';     // phpcs:ignore
	echo '</div>';
	echo '</header>';

	echo '<div class="post-inner thin">';

	echo '<div class="entry-content">';

	echo '<div class="success" id="success">Thank you for your message. Your email has been successfully sent to the ' . esc_attr( $to ) . '. We will get back to you with a response as soon as possible.</div>';

	echo '</div> <!-- ./entry-content -->';

	echo '</div> <!-- /.post-inner -->';

	echo '</article>';

}

\ob_end_flush();

echo '</main>';

get_footer();
