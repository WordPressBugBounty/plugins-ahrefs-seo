<?php

namespace ahrefs\AhrefsSeo;

/**
 * Plugin deprecation class.
 *
 * @since 0.11.0
 */
class Ahrefs_Seo_Deprecated {

	/**
	 * Last day when plugin executes requests to API. We discontinue APIv2 on Nov 1.
	 * So it make no senses to modify date here: unfortunately plugin will receive only errors from API.
	 *
	 * Plugin will work at 'read-only' mode after this date, so you can export your Content audit results to CSV file.
	 *
	 * @var string
	 */
	private static $last_date = '2025-10-31';
	/**
	 * @var string Nonce and user meta key for an admin message
	 */
	private static $notice_key = 'ahrefs_seo_admin_notice_dismissed';
	/**
	 * @var bool Need to add JS script
	 */
	private static $is_script_required = false;
	/**
	 * Initialize admin part
	 */
	public static function admin_init() {
		add_action( 'admin_notices', array( self::class, 'render' ) );
		add_action( 'wp_ajax_dismiss_plugin_notice', array( self::class, 'dismiss_notice' ) );
		add_action( 'admin_footer', array( self::class, 'add_inline_script' ) );
	}
	/**
	 * Has the plugin reached its deprecation date?
	 *
	 * @return bool
	 */
	public static function am_i_alive() {
		$last_date = apply_filters( 'ahrefs_seo_last_date', self::$last_date );
		return gmdate( 'Y-m-d' ) < $last_date;
	}
	/**
	 * Displays message on the admin screen.
	 *
	 * @return void
	 */
	public static function render() {
		if ( current_user_can( 'manage_options' ) ) {
			if ( Ahrefs_Seo::get()->get_view()->is_plugin_screen() ) {
				Ahrefs_Seo::get()->get_view()->show_part( 'notices/plugin-deprecated', [ 'am_i_alive' => self::am_i_alive() ] );
			} else {
				if ( self::am_i_alive() && ! self::is_notice_dismissed() ) {
					// Mark that we need to add the script
					self::$is_script_required = true;
					?>
					<div class="notice notice-warning is-dismissible ahrefs-seo-admin-notice">
						<p>
						<?php
					/* translators: %s: date */
						printf( esc_html__( 'The Ahrefs SEO WordPress plugin will be retired on %s.', 'ahrefs-seo' ), '<strong>' . __( 'October 31', 'ahrefs-seo' ) . '</strong>' );
						?>
							&nbsp;<a
									href="<?php echo esc_attr( Links::content_audit() ); ?>">
									<?php
									_e( 'Read more', 'ahrefs-seo' );
									?>
					</a>
						</p>
					</div>
					<?php
				}
			}
		}
	}
	/**
	 * Add JS code for admin message closing.
	 *
	 * @return void
	 */
	public static function add_inline_script() {
		// Only add script if we actually showed a notice
		if ( ! self::$is_script_required ) {
			return;
		}
		wp_enqueue_script( 'jquery' );
		$ajax_url = admin_url( 'admin-ajax.php' );
		$nonce    = wp_create_nonce( self::$notice_key );
		?>
		<script type="text/javascript">
			jQuery(document).ready(function($) {
				$(document).on('click', '.ahrefs-seo-admin-notice .notice-dismiss', function(e) {
					$.post('
					<?php
					echo esc_js( $ajax_url );
					?>
		', {
						action: 'dismiss_plugin_notice',
						nonce: '
						<?php
						echo esc_js( $nonce );
						?>
		'
					}).fail(function(xhr, status, error) {
							console.log('AJAX error:', error);
					});
				});
			});
		</script>
		<?php
	}
	/**
	 * Closing of admin message AJAX handler
	 *
	 * @return void
	 */
	public static function dismiss_notice() {
		// Verify nonce
		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], self::$notice_key ) ) {
			wp_send_json_error( 'Invalid nonce' );
			return;
		}
		// Verify user capability
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( 'Insufficient permissions' );
			return;
		}
		$user_id = get_current_user_id();
		if ( 0 !== $user_id ) {
			// Store dismissal per user
			update_user_meta( $user_id, self::$notice_key, true );
		}
		wp_send_json_success( array( 'dismissed' => true ) );
	}
	/**
	 * Did user close the admin message?
	 *
	 * @param null|int $user_id
	 *
	 * @return bool
	 * */
	private static function is_notice_dismissed( $user_id = null ) {
		return get_user_meta( isset( $user_id ) ? $user_id : get_current_user_id(), self::$notice_key, true );
	}
}