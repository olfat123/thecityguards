<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Hooks controller
 *
 * @package formidable-abandonment
 */

/**
 * Class FrmAbandonmentHooksController
 */
class FrmAbandonmentHooksController {

	/**
	 * Adds this class to hook controllers list.
	 *
	 * @since 1.0
	 *
	 * @param array<string> $controllers Hooks controllers.
	 *
	 * @return array<string>
	 */
	public static function add_hooks_controller( $controllers ) {
		if ( ! FrmAbandonmentAppHelper::is_compatible() ) {
			self::load_incompatible_hooks();
			return $controllers;
		}

		$controllers[] = __CLASS__;
		return $controllers;
	}

	/**
	 * Loads hooks when this plugin isn't safe to run.
	 *
	 * @since 1.0
	 *
	 * @return void
	 */
	private static function load_incompatible_hooks() {
		self::load_translation();

		add_action( 'admin_notices', array( 'FrmAbandonmentAppController', 'show_incompatible_notice' ) );
	}

	/**
	 * Loads translation.
	 *
	 * @since 1.0
	 *
	 * @return void
	 */
	private static function load_translation() {
		add_action( 'init', array( 'FrmAbandonmentAppController', 'init_translation' ) );
	}

	/**
	 * Loads plugin hooks.
	 *
	 * @since 1.0
	 *
	 * @return void
	 */
	public static function load_hooks() {
		add_action( 'init', array( 'FrmAbandonmentCronController', 'init' ) );
		$observer_controller = FrmAbandonmentObserverController::get_instance();
		add_filter( 'frm_pre_get_form', array( $observer_controller, 'register_observer' ) );
		add_action( 'wp_footer', array( $observer_controller, 'enqueue_assets' ) );
		add_shortcode( 'frm-signed-edit-link', 'FrmAbandonmentEditEntry::entry_edit_link_shortcode' );
		add_filter( 'frm_entry_statuses', 'FrmAbandonmentAppController::add_entry_status', 1 );
		add_filter( 'frm_skip_form_action', 'FrmAbandonmentActionController::maybe_skip_action', 99, 2 );
		add_filter( 'frm_user_can_edit', 'FrmAbandonmentEditEntry::get_all_fields_and_bypass_permission', 10, 2 );
		add_filter( 'frm_after_create_entry', 'FrmAbandonmentEntries::clean_after_submit', 99, 1 );
		add_action( 'frm_after_draft_entry_processed', 'FrmAbandonmentEntries::clean_after_save_draft' );
		add_filter( 'frm_continue_to_create', 'FrmAbandonmentEntries::observe_create_entry', 99, 2 );
		add_filter( 'frm_pro_autosave_on_page_turn', 'FrmAbandonmentEntries::include_inprogress_to_update_entry', 11, 2 );
		add_filter( 'frm_pro_process_update_entry', 'FrmAbandonmentEntries::include_inprogress_to_update_entry', 9, 1 );

		self::load_translation();
	}

	/**
	 * These hooks are only needed for front-end forms.
	 *
	 * @since 1.0
	 *
	 * @return void
	 */
	public static function load_form_hooks() {
		add_filter( 'frm_action_triggers', 'FrmAbandonmentActionController::add_abandoned_trigger' );
		add_filter( 'frm_email_control_settings', 'FrmAbandonmentActionController::email_action_control' );

		if ( isset( $_GET['secret'] ) ) {
			add_action( 'wp_ajax_frm_forms_preview', 'FrmAbandonmentEditEntry::before_preview', 9 );
			add_action( 'wp_ajax_nopriv_frm_forms_preview', 'FrmAbandonmentEditEntry::before_preview', 9 );
			add_filter( 'frm_pre_display_form', 'FrmAbandonmentEditEntry::set_current_form' );
			add_filter( 'frm_show_new_entry_page', 'FrmAbandonmentEditEntry::allow_form_edit', 20 );
		}
	}

	/**
	 * These hooks only load during ajax request.
	 *
	 * @since 1.0
	 *
	 * @return void
	 */
	public static function load_ajax_hooks() {
		add_action( 'wp_ajax_frm_abandoned', 'FrmAbandonmentAppController::maybe_insert_abandoned_entry' );
		add_action( 'wp_ajax_nopriv_frm_abandoned', 'FrmAbandonmentAppController::maybe_insert_abandoned_entry' );
	}

	/**
	 * These hooks only load in the admin area.
	 *
	 * @since 1.0
	 *
	 * @return void
	 */
	public static function load_admin_hooks() {
		add_action( 'admin_init', 'FrmAbandonmentAppController::include_updater' );
		add_filter( 'frm_add_form_settings_section', 'FrmAbandonmentFormSettingsController::add_settings_section', 11 );
		add_filter( 'frm_form_email_action_settings', 'FrmAbandonmentActionController::add_customized_email_action' );
		add_action( 'frm_enqueue_builder_scripts', 'FrmAbandonmentAppController::enqueue_admin_assets' );
		add_filter( 'frm_helper_shortcodes', 'FrmAbandonmentEditEntry::helper_shortcodes_options' );
		add_filter( 'frm_views_entry_status_options', 'FrmAbandonmentAppController::add_entry_status_views_filter_options' );
	}

}
