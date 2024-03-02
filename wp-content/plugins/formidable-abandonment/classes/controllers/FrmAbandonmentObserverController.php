<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class FrmAbandonmentObserver
 *
 * @since 1.0
 *
 * @package formidable-abandonment
 */

/**
 * Observe called forms and fetch the necessary data for abandonment.
 *
 * @since 1.0
 */
class FrmAbandonmentObserverController {

	/**
	 * Collected forms with frm_pre_get_form hook.
	 *
	 * @since 1.0
	 *
	 * @var array<object> $forms
	 */
	private $forms = array();

	/**
	 * Class holder.
	 *
	 * @since 1.0
	 *
	 * @var FrmAbandonmentObserverController|null $instance
	 */
	private static $instance = null;

	/**
	 * Private constructor used in singleton.
	 *
	 * @since 1.0
	 *
	 * @return void
	 */
	private function __construct(){}

	/**
	 * Singleton pattern to prevent direct access we need to ensure this class initiated only once.
	 *
	 * @since 1.0
	 *
	 * @return FrmAbandonmentObserverController
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Collect form with frm_pre_get_form hook and store it on the property for later use.
	 *
	 * @since 1.0
	 *
	 * @param object $form Form object.
	 *
	 * @return void
	 */
	public function register_observer( $form ) {
		$this->forms[] = $form;
	}

	/**
	 * Enqueue abandonment js for front end in case there are enabled form exist.
	 *
	 * @since 1.0
	 *
	 * @return void
	 */
	public function enqueue_assets() {
		// Return on ajax requests except the preview page.
		if ( FrmAppHelper::doing_ajax() ) {
			return;
		}

		// If secret and frm_action query strings were available then it's an edit request.
		if ( FrmAppHelper::simple_get( 'frm_action', 'sanitize_title' ) && FrmAppHelper::simple_get( 'secret' ) ) {
			return;
		}

		// Don't load JS when there is no activated form on a page.
		if ( empty( $this->forms ) ) {
			return;
		}

		// Get form ids which abandonment is activated on.
		$form_ids = self::get_enabled_abandonment_form_ids();
		if ( ! $form_ids ) {
			return;
		}

		wp_register_script( 'formidable-abandoned', FrmAbandonmentAppHelper::use_minified_js_file( 'front' ), array( 'formidable' ), FrmAbandonmentAppHelper::plugin_version(), true );

		wp_localize_script(
			'formidable-abandoned',
			'formidableAbandonedGlobal',
			array(
				'nonce'             => wp_create_nonce( 'formidable-abandoned-global' ),
				'ajaxUrl'           => admin_url( 'admin-ajax.php' ),
				'formSettings'      => json_encode( $form_ids ),
			)
		);

		wp_enqueue_script( 'formidable-abandoned' );
	}

	/**
	 * Prepared enabled abandoned form ids and settings.
	 *
	 * @since 1.0
	 *
	 * @return array<mixed>
	 */
	private function get_enabled_abandonment_form_ids() {
		global $frm_vars;

		$observable_form_ids = array();
		$drafted_forms       = array();

		if ( ! empty( $frm_vars['editing_entry'] ) && is_user_logged_in() ) {
			$entry  = FrmEntry::getOne( $frm_vars['editing_entry'] );
			$form   = FrmForm::getOne( $entry->form_id );

			if ( $form && ! empty( $form->options['save_draft'] ) ) {
				$drafted_forms[ $entry->form_id ] = $entry->form_id;
			}
		}
		// Check if abandonment is enabled for a form.
		foreach ( $this->forms as $k => $form ) {
			// Temporary exclude the form being watch by abandonment when it's saved as draft.
			if ( in_array( $form->id, $drafted_forms, true ) ) {
				continue;
			}

			// Enable abandonment has the authority above the email required settings.
			if ( empty( $form->options['enable_abandonment'] ) ) {
				continue;
			}

			$observable_form_ids[ $k ]['form_id'] = $form->id;

			if ( empty( $form->options['abandon_email_required'] ) ) {
				continue;
			}

			$observable_fields = self::get_observable_fields( $form->id );
			if ( $observable_fields ) {
				$observable_form_ids[ $k ]['abandon_email_required'] = true;
				$observable_form_ids[ $k ]['observable_fields']      = $observable_fields;
			}
		}

		return $observable_form_ids;
	}

	/**
	 * When a phone or email is required to save a partial entry,
	 * this method will extract phone and email fields of a form.
	 *
	 * @since 1.0
	 *
	 * @param int $form_id form id.
	 *
	 * @return array<int>|false
	 */
	private static function get_observable_fields( $form_id ) {
		$email_fields = FrmField::get_all_types_in_form( $form_id, 'email', '', 'include' );
		$phone_fields = FrmField::get_all_types_in_form( $form_id, 'phone', '', 'include' );

		$observable_fields = wp_list_pluck( array_merge( $email_fields, $phone_fields ), 'id' );

		return $observable_fields ? $observable_fields : false;
	}

}
