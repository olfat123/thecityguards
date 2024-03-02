<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Class FrmAbandonmentAppController
 *
 * @since 1.0
 *
 * @package formidable-abandonment
 */

/**
 * App controller to manage general services of plugin.
 *
 * @since 1.0
 */
class FrmAbandonmentAppController {

	/**
	 * Shows the incompatible notice.
	 *
	 * @since 1.0
	 *
	 * @return void
	 */
	public static function show_incompatible_notice() {
		if ( FrmAbandonmentAppHelper::is_compatible() ) {
			return;
		}
		?>
		<div class="notice notice-error">
			<p><?php esc_html_e( 'You are running an outdated version of Formidable Forms.', 'formidable-abandonment' ); ?></p>
		</div>
		<?php
	}

	/**
	 * Initializes plugin translation.
	 *
	 * @since 1.0
	 *
	 * @return void
	 */
	public static function init_translation() {
		load_plugin_textdomain( 'formidable-abandonment', false, FrmAbandonmentAppHelper::plugin_folder() . '/languages/' );
	}

	/**
	 * Includes addon updater.
	 *
	 * @since 1.0
	 *
	 * @return void
	 */
	public static function include_updater() {
		if ( class_exists( 'FrmAddon' ) ) {
			FrmAbandonmentUpdate::load_hooks();
		}
	}

	/**
	 * Adds "In Progress" and "Abandoned" to views filter.
	 *
	 * @since 1.0
	 *
	 * @param array<string> $options Entry statuses.
	 *
	 * @return array<string>
	 */
	public static function add_entry_status_views_filter_options( $options ) {
		$options[ FrmAbandonmentAppHelper::IN_PROGRESS_ENTRY_STATUS ]  = __( 'In Progress', 'formidable-abandonment' );
		$options[ FrmAbandonmentAppHelper::ABANDONED_ENTRY_STATUS ]    = __( 'Abandoned', 'formidable-abandonment' );

		return $options;
	}

	/**
	 * Add "Entry status" column to entries list.
	 *
	 * @since 1.0
	 *
	 * @param array<string> $statuses Entry statuses.
	 *
	 * @return array<string>
	 */
	public static function add_entry_status( $statuses ) {
		// "2" is reserved for in progress.
		$statuses[ FrmAbandonmentAppHelper::IN_PROGRESS_ENTRY_STATUS ]  = __( 'In Progress', 'formidable-abandonment' );
		// "3" is reserved for abandonment.
		$statuses[ FrmAbandonmentAppHelper::ABANDONED_ENTRY_STATUS ]    = __( 'Abandoned', 'formidable-abandonment' );

		return $statuses;
	}

	/**
	 * Enqueue assets for settings form and builder page.
	 *
	 * @since 1.0
	 *
	 * @return void
	 */
	public static function enqueue_admin_assets() {
		// Enqueue style.
		wp_enqueue_style( 'formidable-abandonment-admin', FrmAbandonmentAppHelper::plugin_url() . '/assets/css/admin.css', array(), FrmAbandonmentAppHelper::plugin_version() );

		if ( ! self::is_form_settings_page() ) {
			return;
		}

		// Enqueue script.
		wp_enqueue_script( 'formidable-abandonment-admin', FrmAbandonmentAppHelper::use_minified_js_file( 'admin' ), array( 'wp-i18n', 'formidable_dom', 'formidable_admin' ), FrmAbandonmentAppHelper::plugin_version(), true );
	}

	/**
	 * Check if the current page is formidable action.
	 *
	 * @since 1.0
	 *
	 * @return bool
	 */
	private static function is_form_settings_page() {
		$page   = FrmAppHelper::simple_get( 'page', 'sanitize_title' );
		$action = FrmAppHelper::simple_get( 'frm_action', 'sanitize_title' );
		return ( 'formidable' === $page && 'settings' === $action );
	}

	/**
	 * Handle ajax of insert, update sanitization and validation of abandoned entry.
	 *
	 * @since 1.0
	 *
	 * @return void
	 */
	public static function maybe_insert_abandoned_entry() {
		// Check if ajax pointer is defined.
		if ( ! FrmAppHelper::doing_ajax() ) {
			wp_die( esc_html__( 'Invalid request.', 'formidable-abandonment' ) );
		}

		$form_id = FrmAppHelper::get_post_param( 'form_id', 0, 'absint' );
		if ( ! $form_id ) {
			wp_send_json_error( esc_html__( 'Invalid form id.', 'formidable-abandonment' ), 400 );
		}

		$errors = self::validate_abandonment_entry();
		// We only need to verify entry is not a spam except recaptcha field which is not our concern to check.
		if ( isset( $errors['spam'] ) ) {
			wp_send_json_error( esc_html__( 'Spam entry.', 'formidable-abandonment' ), 400 );
		}

		$user_uuid = FrmAbandonmentAppHelper::build_uuid_with_cookie( $form_id );

		if ( ! $user_uuid ) {
			wp_send_json_error( esc_html__( 'Invalid request.', 'formidable-abandonment' ), 400 );
		}

		$entry_id = FrmAbandonmentEntries::get_entry_by_uuid( $user_uuid );
		$is_draft = (int) FrmDb::get_var( 'frm_items', array( 'id' => $entry_id ), 'is_draft' );
		if ( FrmEntriesHelper::DRAFT_ENTRY_STATUS === $is_draft ) {
			wp_send_json_success();
		}

		$abandonment_entries_model = new FrmAbandonmentEntries( $user_uuid, $form_id, $is_draft );
		$abandonment_entries_model->submit_entry();

		wp_send_json_success( esc_html__( 'Successfully created.', 'formidable-abandonment' ), 200 );
	}

	/**
	 * Prepare, Sanitize and validate entry fields.
	 *
	 * @since 1.0
	 *
	 * @return array<string>|array<bool> Sanitized value.
	 */
	private static function validate_abandonment_entry() {
		$global_post = $_POST; // phpcs:ignore WordPress.Security.NonceVerification.Missing

		// Extract data index and implement it in main post to simulate the valid entry order.
		$global_post_data = isset( $global_post['data'] ) && is_string( $global_post['data'] ) ? wp_unslash( $global_post['data'] ) : false;

		if ( ! $global_post_data ) {
			return array( 'spam' => true );
		}

		$decoded_post_data = json_decode( html_entity_decode( $global_post_data ), true );
		if ( is_array( $decoded_post_data ) ) {
			$global_post = array_merge( $global_post, $decoded_post_data );
		}

		unset( $global_post_data );
		unset( $global_post['data'] );
		unset( $global_post['create'] );

		if ( empty( $global_post['item_meta'] ) ) {
			return array( 'spam' => true );
		}

		$global_post['item_meta'] = map_deep(
			$global_post['item_meta'],
			function( $value ) {
				$json_decoded = json_decode( $value, true );
				if ( json_last_error() === JSON_ERROR_NONE ) {
					return $json_decoded;
				}
				return $value;
			}
		);

		$_POST = $global_post;

		return FrmEntryValidate::validate( wp_unslash( $global_post ) );
	}

}
