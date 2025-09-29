<?php

namespace ahrefs\AhrefsSeo;

$locals     = Ahrefs_Seo_View::get_template_variables();
$am_i_alive = isset( $locals['am_i_alive'] ) ? $locals['am_i_alive'] : true;
?>
	<!-- deprecation message -->
	<div class="notice ahrefs-content-tip tip-notice">
		<?php
		if ( $am_i_alive ) {
			?>
			<div class="caption">
			<?php
			_e( 'Ahrefs SEO WordPress Plugin is being deprecated', 'ahrefs-seo' );
			?>
	</div>
			<div class="text">
			<?php
			/* translators: %s: date */
			printf( esc_html__( 'We’re retiring the Ahrefs SEO WordPress plugin on %s.', 'ahrefs-seo' ), '<strong>' . __( 'October 31, 2025', 'ahrefs-seo' ) . '</strong>' );
			echo ' ';
			_e( 'Before that date, please export any content audits you want to keep, as the plugin will no longer be available afterward.', 'ahrefs-seo' );
			?>
				</div>
				<?php
		} else {
			?>
			<div class="caption">
			<?php
			_e( 'Ahrefs SEO WordPress Plugin has been deprecated', 'ahrefs-seo' );
			?>
	</div>
			<div class="text">
			<?php
			/* translators: %s: date */
			printf( esc_html__( 'The Ahrefs SEO WordPress plugin was retired on %s.', 'ahrefs-seo' ), '<strong>' . __( 'October 31, 2025', 'ahrefs-seo' ) . '</strong>' );
			?>
				</div>
				<?php
		}
		?>
		<div class="text">
		<?php
		esc_html_e( 'You can continue checking your traffic, ranking keywords, backlinks, and running Site Audits — all inside Ahrefs Webmaster Tools, completely free for domains you own.', 'ahrefs-seo' );
		?>
		</div>
		<div class="text">
			<?php
			Ahrefs_Seo::get()->get_view()->learn_more_link( 'https://ahrefs.com/webmaster-tools', __( 'Learn more at ahrefs.com/webmaster-tools', 'ahrefs-seo' ) );
			?>
		</div>
		<div class="text">
		<?php
		esc_html_e( 'We’re also bringing the same functionality as this plugin directly into the Ahrefs Toolkit, so stay tuned for announcements.', 'ahrefs-seo' );
		?>
		</div>
		<div class="text">
			<?php
			esc_html_e( 'Have questions? Our support team is here to help:', 'ahrefs-seo' );
			?>
			&nbsp;<a
				href="<?php echo esc_attr( Ahrefs_Seo::get_support_url() ); ?>" target="_blank">support@ahrefs.com</a>
		</div>
	</div>
<?php 