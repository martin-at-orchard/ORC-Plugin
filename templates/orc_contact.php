<?php
/**
 * Template Name: Contact
 *
 * @package ORC
 */

namespace ORC;

// TODO: Add captcha

$to = filter_input( INPUT_GET, 'send-to', FILTER_SANITIZE_STRING );

$data = array(
	'name'          => '',
	'email'         => '',
	'subject'       => '',
	'message'       => '',
	'privacy'       => '',
	'email-to'      => ( empty( $to ) ? '' : $to ),
	'privacy-error' => '',
	'name-error'    => '',
	'email-error'   => '',
	'subject-error' => '',
	'message-error' => '',
	'error'         => '',
	'success'       => '',
);

session_start();
if ( isset( $_SESSION['post-data'] ) ) {

	foreach ( $_SESSION['post-data'] as $index => $value ) {
		$data[ $index ] = $value;
	}

	unset( $_SESSION['post-data'] );
}

get_header();

echo '<main id="site-content" role="main">';

if ( have_posts() ) {
	the_post();

	\ob_start();

	echo '<article class="post-<?php get_the_ID(); ?> page type-page status-publish hentry" id="post-' . get_the_ID() . '">';
	echo '<header class="entry-header has-text-align-center header-footer-group">';
	echo '<div class="entry-header-inner section-inner medium">';
	echo '<h1 class="entry-title">' . get_the_title() . '</h1>';     // phpcs:ignore
	echo '</div>';
	echo '</header>';

	echo '<div class="post-inner thin">';

	echo '<div class="entry-content">';

	if ( '' !== $data['success'] ) {
		echo '<div class="success" id="success">' . esc_attr( $data['success'] ) . '</div>';
	} elseif ( '' !== $data['error'] ) {
		echo '<div class="error" id="error">' . esc_attr( $data['error'] ) . '</div>';
	}

	echo '<h2 class="email-to-title">Email ' . esc_attr( Contact::get_email_to( $data['email-to'] ) ) . '</h2>';
	echo '<form action="' . esc_url( admin_url( 'admin-post.php' ) ) . '" method="post" id="contact-form">';

	echo '<label for="name">Name:</label>';
	echo '<input type="text" name="name" id="name" required aria-required="true" aria-invalid="' . ( '' === $data['name-error'] ? 'false' : 'true' ) . '" value="' . $data['name'] . '">';     // phpcs:ignore
	echo '<div class="input-error">' . esc_attr( $data['name-error'] ) . '</div>';

	echo '<label for="email">Email:</label>';
	echo '<input type="text" name="email" id="email" required aria-required="true" aria-invalid="' . ( '' === $data['email-error'] ? 'false' : 'true' ) . '" value="' . $data['email'] . '">';     // phpcs:ignore
	echo '<div class="input-error">' . esc_attr( $data['email-error'] ) . '</div>';

	echo '<label for="subject">Subject:</label>';
	echo '<input type="text" name="subject" id="subject" required aria-required="true" aria-invalid="' . ( '' === $data['subject-error'] ? 'false' : 'true' ) . '" value="' . $data['subject'] . '">';     // phpcs:ignore
	echo '<div class="input-error">' . esc_attr( $data['subject-error'] ) . '</div>';

	echo '<label for="message">Message</label>';
	echo '<textarea cols="40" rows="10" name="message" id="message" aria-invalid="' . ( '' === $data['message-error'] ? 'false' : 'true' ) . '">' . $data['message'] . '</textarea>';     // phpcs:ignore
	echo '<div class="input-error">' . esc_attr( $data['message-error'] ) . '</div>';

	echo '<label for="privacy">Privacy</label>';
	echo '<input type="checkbox" name="privacy" id="privacy" value="1" required aria-required="true" aria-invalid="' . ( '' === $data['privacy-error'] ? 'false' : 'true' ) . '">';      // phpcs:ignore
	echo '<span class="privacy-message">I have read and agree to the privacy policy outlined on the privacy page <a href="http://blocks.local/privacy" target="_blank" title="View Privacy Page">Privacy Page</a></span>';
	echo '<div class="input-error">' . esc_attr( $data['privacy-error'] ) . '</div>';

	wp_nonce_field( 'Contact::orc-contact.php', Contact::NONCE );
	echo '<input type="hidden" name="action" value="contact_form">';
	echo '<input type="hidden" name="email-to" value="' . $data['email-to'] . '">';
	echo '<input type="submit" name="submit" id="submit" value="submit">';

	echo '</form>';

	echo '</div> <!-- ./entry-content -->';

	echo '</div> <!-- /.post-inner -->';

	echo '</article>';

}

\ob_end_flush();

echo '</main>';

get_footer();
