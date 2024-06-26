<?php // phpcs:ignore

namespace ORC;

if ( ! defined( 'ABSPATH' ) ) {
	exit;     // Exit if accessed directly.
}

?>

<div class="wrap">
	<h1><?php _e( 'ORC Plugin Settings', 'orc-plugin' ); // phpcs:ignore ?></h1>
	<ul class="tabs">

		<li class="tab">
			<input type="radio" name="tabs" checked="checked" id="tab1" />
			<label for="tab1"><?php _e( 'Contact', 'orc-plugin' ); // phpcs:ignore ?></label>
			<div id="tab-content1" class="content">
				<h2><?php _e( 'ORC Contact Options', 'orc-plugin' ); // phpcs:ignore ?></h2>
				<p><?php _e( 'This section contains the contact phone numbers, email addresses and social media accounts for the Orchard Recovery Center', 'orc-plugin'); // phpcs:ignore ?></p>
				<ul class="info">
					<li><?php _e('Local Phone Number', 'orc-plugin'); // phpcs:ignore ?></li>
					<li><?php _e('Toll Free Phone Number', 'orc-plugin'); // phpcs:ignore ?></li>
					<li><?php _e('Text Number', 'orc-plugin'); // phpcs:ignore ?></li>
					<li><?php _e('Fax Number', 'orc-plugin'); // phpcs:ignore ?></li>
					<li><?php _e('Intake Email Address - Where the intake emails are sent', 'orc-plugin'); // phpcs:ignore ?></li>
					<li><?php _e('Communications Email Address - Where the communications emails are sent', 'orc-plugin'); // phpcs:ignore ?></li>
					<li><?php _e('HR Email Address - Where the human resources emails are sent', 'orc-plugin'); // phpcs:ignore ?></li>
					<li><?php _e('Alumni Email Address - Where the alumni emails are sent', 'orc-plugin'); // phpcs:ignore ?></li>
					<li><?php _e('Website Email Address - Where the website emails are sent', 'orc-plugin'); // phpcs:ignore ?></li>
					<li><?php _e('Privacy Email Address - Where the privacy policy emails are sent', 'orc-plugin'); // phpcs:ignore ?></li>
					<li><?php _e('Facebook Link ID', 'orc-plugin'); // phpcs:ignore ?></li>
					<li><?php _e('Instagram Link ID', 'orc-plugin'); // phpcs:ignore ?></li>
					<li><?php _e('Twitter Link ID', 'orc-plugin'); // phpcs:ignore ?></li>
					<li><?php _e('YouTube Channel ID', 'orc-plugin'); // phpcs:ignore ?></li>
				</ul>
			</div>
		</li>

		<li class="tab">
			<input type="radio" name="tabs" id="tab2" />
			<label for="tab2"><?php _e( 'Video', 'orc-plugin' ); // phpcs:ignore ?></label>
			<div id="tab-content2" class="content">
				<h2><?php _e( 'ORC Video Options', 'orc-plugin' ); // phpcs:ignore ?></h2>
				<p><?php _e( 'This section contains videos to display on the Orchard Recovery Center website', 'orc-plugin'); // phpcs:ignore ?></p>
				<ul class="info">
					<li><?php _e('Main Video ID - Normal Orchard Video (YouTube ID)', 'orc-plugin'); // phpcs:ignore ?></li>
					<li><?php _e('Christmas Video ID - Video to play at Christmas (YouTube ID)', 'orc-plugin'); // phpcs:ignore ?></li>
				</ul>
			</div>
		</li>

		<li class="tab">
			<input type="radio" name="tabs" id="tab3" />
			<label for="tab3"><?php _e( 'Analytics', 'orc-plugin' ); // phpcs:ignore ?></label>
			<div id="tab-content3" class="content">
				<h2><?php _e( 'ORC Analytics/Tracking Codes', 'orc-plugin' ); // phpcs:ignore ?></h2>
				<p><?php _e( 'This section contains the analytics/tracking codes/id for the Orchard Recovery Center website', 'orc-plugin' ); // phpcs:ignore ?></p>
				<ul class="info">
					<li><?php _e('Google Analytics Code', 'orc-plugin'); // phpcs:ignore ?></li>
					<li><?php _e('Facebook App ID', 'orc-plugin'); // phpcs:ignore ?></li>
					<li><?php _e('Facebook Pixel ID', 'orc-plugin'); // phpcs:ignore ?></li>
					<li><?php _e('Bing Tracking', 'orc-plugin'); // phpcs:ignore ?></li>
					<li><?php _e('LinkedIn Partner Code', 'orc-plugin'); // phpcs:ignore ?></li>
					<li><?php _e('Twitter Universal Website Tag', 'orc-plugin'); // phpcs:ignore ?></li>
				</ul>
			</div>
		</li>

		<li class="tab">
			<input type="radio" name="tabs" id="tab4" />
			<label for="tab4"><?php _e( 'SMTP Options', 'orc-plugin' ); // phpcs:ignore ?></label>
			<div id="tab-content4" class="content">
				<h2><?php _e( 'Simple Mail Transer Protocol Options', 'orc-plugin' ); // phpcs:ignore ?></h2>
				<p><?php _e( 'This options for configuring mail to use SMTP rather than just the plain mailer program. This requires valid credentials to send the email.', 'orc-plugin' ); // phpcs:ignore ?></p>
				<ul class="info">
					<li><?php _e('Use SMTP - Requires ALL other fields to be filled in', 'orc-plugin'); // phpcs:ignore ?></li>
					<li><?php _e('SMTP Host - Server handling sending mail', 'orc-plugin'); // phpcs:ignore ?></li>
					<li><?php _e('SMTP Port - The port that is used to connect to the mail server', 'orc-plugin'); // phpcs:ignore ?></li>
					<li><?php _e('SMTP Authentication', 'orc-plugin'); // phpcs:ignore ?></li>
					<li><?php _e('SMTP Username - User sending the emails', 'orc-plugin'); // phpcs:ignore ?></li>
					<li><?php _e('SMTP From Name - Name displayed in the email headers', 'orc-plugin'); // phpcs:ignore ?></li>
					<li><?php _e('SMTP Secure - Email encryption protocol', 'orc-plugin'); // phpcs:ignore ?></li>
				</ul>
			</div>
		</li>

		<li class="tab">
			<input type="radio" name="tabs" id="tab99" />
			<label for="tab99"><?php _e( 'Templates', 'orc-plugin' ); // phpcs:ignore ?></label>
			<div id="tab-content99" class="content">
				<h2><?php _e( 'Templates', 'orc-plugin' ); // phpcs:ignore ?></h2>
				<p><?php _e( 'Templates are used to display the contact page and the success page after emailing.', 'orc-plugin' ); // phpcs:ignore ?></p>
				<p><?php _e( 'The templates in the plugin can be overridden by templates in the theme. The search order is shown below.', 'orc-plugin' ); // phpcs:ignore ?></p>
				<ol>
					<li>wp-content/themes/CHILD-THEME/plugin/orc/templates/FILENAME</li>
					<li>wp-content/themes/PARENT-THEME/plugin/orc/templates/FILENAME</li>
					<li>wp-content/plugins/orc/templates/FILENAME</li>
				</ol>
				<p><?php _e( 'The filenames used to override the templates are show below.', 'orc-plugin' ); // phpcs:ignore ?></p>
				<ul class="info">
					<li>orc_contact.php - <?php _e( 'Contact Page with slug ', 'orc-plugin' ); // phpcs:ignore ?>'contact'</li>
					<li>orc_status.php - <?php _e( 'Email status page with slug ', 'orc-plugin' ); // phpcs:ignore ?>'email-status'</li>
				</ul>
			</div>
		</li>

		<li class="tab">
			<input type="radio" name="tabs" id="tab100" />
			<label for="tab100"><?php _e( 'Shortcodes', 'orc-plugin' ); // phpcs:ignore ?></label>
			<div id="tab-content100" class="content">
				<h2><?php _e( 'ORC Shortcodes', 'orc-plugin' ); // phpcs:ignore ?></h2>
				<p><?php _e( 'Shortcodes available for use', 'orc-plugin' ); // phpcs:ignore ?></p>
				<ul class="info">
					<li>
						<?php _e( '[orc_years_since year="Y" month="M" day="D"]', 'orc-plugin' ); // phpcs:ignore ?>
						<p><?php _e( 'Display the number of days since a certain date</p>', 'orc-plugin' ); // phpcs:ignore ?></p>
						<p><?php _e( 'Where:', 'orc-plugin' ); // phpcs:ignore ?></p>
						<ul>
							<li><?php _e('- Y is the year (required)', 'orc-plugin' ); // phpcs:ignore ?>
							<li><?php _e('- M is the month number (1-12) (optional - defaults to 1)', 'orc-plugin' ); // phpcs:ignore ?>
							<li><?php _e('- D is the day number (1-31) (optional - defaults to 1)', 'orc-plugin' ); // phpcs:ignore ?>
						</ul>
					</li>
					<li>
						<?php _e( '[orc_contact type="T" link="L" icon="I" prefix="P" suffix="S" class="C"]', 'orc-plugin' ); // phpcs:ignore ?>
						<p><?php _e( 'Display a contact type</p>', 'orc-plugin' ); // phpcs:ignore ?></p>
						<p><?php _e( 'Where:', 'orc-plugin' ); // phpcs:ignore ?></p>
						<ul>
							<li><?php _e('- T is the contact type (required)', 'orc-plugin' ); // phpcs:ignore ?>
							<ul>
								<li>phone - <?php _e('Local Phone Number', 'orc-plugin' ); // phpcs:ignore ?></li>
								<li>tollfree - <?php _e('Toll-Free Phone Number', 'orc-plugin' ); // phpcs:ignore ?></li>
								<li>text - <?php _e('SMS Text Number', 'orc-plugin' ); // phpcs:ignore ?></li>
								<li>fax - <?php _e('Fax Number', 'orc-plugin' ); // phpcs:ignore ?></li>
							</ul>
							<li><?php _e('- I is to display an icon (optional - defaults to "false")', 'orc-plugin' ); // phpcs:ignore ?>
							<li><?php _e('- L is to display a clickable link (optional - defaults to "false")', 'orc-plugin' ); // phpcs:ignore ?>
							<li><?php _e('- P is prefix (optional - defaults to "")', 'orc-plugin' ); // phpcs:ignore ?>
							<li><?php _e('- S is suffix (optional - defaults to "")', 'orc-plugin' ); // phpcs:ignore ?>
							<li><?php _e('- C is the span class (optional - defaults to "")', 'orc-plugin' ); // phpcs:ignore ?>
						</ul>
					</li>
					<li>
						<?php _e( '[orc_social type="T" class="C"]', 'orc-plugin' ); // phpcs:ignore ?>
						<p><?php _e( 'Display a social contact type</p>', 'orc-plugin' ); // phpcs:ignore ?></p>
						<p><?php _e( 'Where:', 'orc-plugin' ); // phpcs:ignore ?></p>
						<ul>
							<li><?php _e('- T is the social contact type (required)', 'orc-plugin' ); // phpcs:ignore ?>
							<ul>
								<li>facebook - <?php _e('Facebook Icon and Link', 'orc-plugin' ); // phpcs:ignore ?></li>
								<li>instagram - <?php _e('Instagram Icon and Link', 'orc-plugin' ); // phpcs:ignore ?></li>
								<li>twitter - <?php _e('Twitter Icon and Link', 'orc-plugin' ); // phpcs:ignore ?></li>
								<li>youtube - <?php _e('YouTube Icon and Link', 'orc-plugin' ); // phpcs:ignore ?></li>
							</ul>
							<li><?php _e('- C is the span class (optional - defaults to "")', 'orc-plugin' ); // phpcs:ignore ?>
						</ul>
					</li>
				</ul>
			</div>
		</li>

		<li class="tab">
			<input type="radio" name="tabs" id="tab101" />
			<label for="tab101"><?php _e( 'Actions', 'orc-plugin' ); // phpcs:ignore ?></label>
			<div id="tab-content101" class="content">
				<h2><?php _e( 'ORC Actions', 'orc-plugin' ); // phpcs:ignore ?></h2>
				<p><?php _e( 'Actions available for use', 'orc-plugin' ); // phpcs:ignore ?></p>
				<ul class="info">
					<li><?php _e( 'orc_before_years_since - At the beginning of the orc_years_since shortcode', 'orc-plugin' ); // phpcs:ignore ?></li>
					<li><?php _e( 'orc_after_years_since - At the end of the orc_years_since shortcode', 'orc-plugin' ); // phpcs:ignore ?></li>
					<li><?php _e( 'orc_before_contact - At the beginning of the orc_contact shortcode', 'orc-plugin' ); // phpcs:ignore ?></li>
					<li><?php _e( 'orc_after_contact - At the end of the orc_contact shortcode', 'orc-plugin' ); // phpcs:ignore ?></li>
					<li><?php _e( 'orc_before_social - At the beginning of the orc_socaial shortcode', 'orc-plugin' ); // phpcs:ignore ?></li>
					<li><?php _e( 'orc_after_social - At the end of the orc_social shortcode', 'orc-plugin' ); // phpcs:ignore ?></li>
				</ul>
			</div>
		</li>

		<li class="tab">
			<input type="radio" name="tabs" id="tab102" />
			<label for="tab102"><?php _e( 'Filters', 'orc-plugin' ); // phpcs:ignore ?></label>
			<div id="tab-content102" class="content">
				<h2><?php _e( 'ORC Filters', 'orc-plugin' ); // phpcs:ignore ?></h2>
				<p><?php _e( 'Filters available for use', 'orc-plugin' ); // phpcs:ignore ?></p>
				<ul class="info">
					<li><?php _e( 'orc_validate_data, $output, $input - At the end of the validate data in the settings ($output array of validated data from $input). ', 'orc-plugin' ); // phpcs:ignore ?></li>
					<li><?php _e( 'orc_years_since, $html - At the end of the orc_years_since shortcode ($html string)', 'orc-plugin' ); // phpcs:ignore ?></li>
					<li><?php _e( 'orc_contact, $html - At the end of the orc_contact shortcode ($html string)', 'orc-plugin' ); // phpcs:ignore ?></li>
					<li><?php _e( 'orc_social, $html - At the end of the orc_social shortcode ($html string)', 'orc-plugin' ); // phpcs:ignore ?></li>
				</ul>
			</div>
		</li>

	</ul>
</div><!-- .wrap -->
