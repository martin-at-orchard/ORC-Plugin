<?php // phpcs:ignore

namespace ORC;

if ( ! defined( 'ABSPATH' ) ) {
	exit;     // Exit if accessed directly.
}

?>

<div class="wrap">
	<h1><?php _e( 'ORC Plugin Settings', Plugin::TEXT_DOMAIN ); // phpcs:ignore ?></h1>
	<ul class="tabs">

		<li class="tab">
			<input type="radio" name="tabs" checked="checked" id="tab1" />
			<label for="tab1"><?php _e( 'Contact', Plugin::TEXT_DOMAIN ); // phpcs:ignore ?></label>
			<div id="tab-content1" class="content">
				<h2><?php _e( 'ORC Contact Options', Plugin::TEXT_DOMAIN ); // phpcs:ignore ?></h2>
				<p><?php _e( 'This section contains the contact phone numbers for the Orchard Recovery Center', Plugin::TEXT_DOMAIN); // phpcs:ignore ?></p>
				<ul class="info">
					<li><?php _e('Local Phone Number', Plugin::TEXT_DOMAIN); // phpcs:ignore ?></li>
					<li><?php _e('Toll Free Phone Number', Plugin::TEXT_DOMAIN); // phpcs:ignore ?></li>
					<li><?php _e('Text Number', Plugin::TEXT_DOMAIN); // phpcs:ignore ?></li>
					<li><?php _e('Fax Number', Plugin::TEXT_DOMAIN); // phpcs:ignore ?></li>
				</ul>
			</div>
		</li>

		<li class="tab">
			<input type="radio" name="tabs" id="tab2" />
			<label for="tab2"><?php _e( 'Video', Plugin::TEXT_DOMAIN ); // phpcs:ignore ?></label>
			<div id="tab-content2" class="content">
				<h2><?php _e( 'ORC Video Options', Plugin::TEXT_DOMAIN ); // phpcs:ignore ?></h2>
				<p><?php _e( 'This section contains videos to display on the Orchard Recovery Center website', Plugin::TEXT_DOMAIN); // phpcs:ignore ?></p>
				<ul class="info">
					<li><?php _e('Main Video ID - Normal Orchard Video (YouTube ID)', Plugin::TEXT_DOMAIN); // phpcs:ignore ?></li>
					<li><?php _e('Christmas Video ID - Video to play at Christmas (YouTube ID)', Plugin::TEXT_DOMAIN); // phpcs:ignore ?></li>
				</ul>
			</div>
		</li>

		<li class="tab">
			<input type="radio" name="tabs" id="tab3" />
			<label for="tab3"><?php _e( 'Analytics', Plugin::TEXT_DOMAIN ); // phpcs:ignore ?></label>
			<div id="tab-content3" class="content">
				<h2><?php _e( 'ORC Analytics/Tracking Codes', Plugin::TEXT_DOMAIN ); // phpcs:ignore ?></h2>
				<p><?php _e( 'This section contains the analytics/tracking codes/id for the Orchard Recovery Center website', Plugin::TEXT_DOMAIN ); // phpcs:ignore ?></p>
				<ul class="info">
					<li><?php _e('Google Analytics Code', Plugin::TEXT_DOMAIN); // phpcs:ignore ?></li>
					<li><?php _e('Facebook App ID', Plugin::TEXT_DOMAIN); // phpcs:ignore ?></li>
					<li><?php _e('Facebook Pixel ID', Plugin::TEXT_DOMAIN); // phpcs:ignore ?></li>
					<li><?php _e('Bing Tracking', Plugin::TEXT_DOMAIN); // phpcs:ignore ?></li>
					<li><?php _e('LinkedIn Partner Code', Plugin::TEXT_DOMAIN); // phpcs:ignore ?></li>
					<li><?php _e('Twitter Universal Website Tag', Plugin::TEXT_DOMAIN); // phpcs:ignore ?></li>
				</ul>
			</div>
		</li>

		<li class="tab">
			<input type="radio" name="tabs" id="tab100" />
			<label for="tab100"><?php _e( 'Shortcodes', Plugin::TEXT_DOMAIN ); // phpcs:ignore ?></label>
			<div id="tab-content100" class="content">
				<h2><?php _e( 'ORC Shortcodes', Plugin::TEXT_DOMAIN ); // phpcs:ignore ?></h2>
				<p><?php _e( 'Shortcodes available for use', Plugin::TEXT_DOMAIN ); // phpcs:ignore ?></p>
				<ul class="info">
					<li>
						<?php _e( '[orc_years_since year="Y" month="M" day="D"]', Plugin::TEXT_DOMAIN ); // phpcs:ignore ?>
						<p><?php _e( 'Display the number of days since a certain date</p>', Plugin::TEXT_DOMAIN ); // phpcs:ignore ?></p>
						<p><?php _e( 'Where:', Plugin::TEXT_DOMAIN ); // phpcs:ignore ?></p>
						<ul>
							<li><?php _e('- Y is the year (required)', Plugin::TEXT_DOMAIN ); // phpcs:ignore ?>
							<li><?php _e('- M is the month number (1-12) (optional - defaults to 1)', Plugin::TEXT_DOMAIN ); // phpcs:ignore ?>
							<li><?php _e('- D is the day number (1-31) (optional - defaults to 1)', Plugin::TEXT_DOMAIN ); // phpcs:ignore ?>
						</ul>
					</li>
				</ul>
			</div>
		</li>

		<li class="tab">
			<input type="radio" name="tabs" id="tab101" />
			<label for="tab101"><?php _e( 'Actions', Plugin::TEXT_DOMAIN ); // phpcs:ignore ?></label>
			<div id="tab-content101" class="content">
				<h2><?php _e( 'ORC Actions', Plugin::TEXT_DOMAIN ); // phpcs:ignore ?></h2>
				<p><?php _e( 'Actions available for use', Plugin::TEXT_DOMAIN ); // phpcs:ignore ?></p>
				<ul class="info">
					<li>
						<?php _e( 'orc_before_years_since - At the beginning of the orc_years_since shortcode', Plugin::TEXT_DOMAIN ); // phpcs:ignore ?>
					</li>
					<li>
						<?php _e( 'orc_after_years_since - At the end of the orc_years_since shortcode', Plugin::TEXT_DOMAIN ); // phpcs:ignore ?>
					</li>
				</ul>
			</div>
		</li>

		<li class="tab">
			<input type="radio" name="tabs" id="tab102" />
			<label for="tab102"><?php _e( 'Filters', Plugin::TEXT_DOMAIN ); // phpcs:ignore ?></label>
			<div id="tab-content102" class="content">
				<h2><?php _e( 'ORC Filters', Plugin::TEXT_DOMAIN ); // phpcs:ignore ?></h2>
				<p><?php _e( 'Filters available for use', Plugin::TEXT_DOMAIN ); // phpcs:ignore ?></p>
				<ul class="info">
					<li><?php _e( 'orc_validate_data, $output, $input - At the end of the validate data in the settings ($output array of validated data from $input). ', Plugin::TEXT_DOMAIN ); // phpcs:ignore ?></li>
					<li>
						<?php _e( 'orc_years_since, $html - At the end of the orc_years_since shortcode ($html string)', Plugin::TEXT_DOMAIN ); // phpcs:ignore ?>
					</li>
				</ul>
			</div>
		</li>

	</ul>
</div><!-- .wrap -->
