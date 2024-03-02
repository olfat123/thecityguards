<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * App controller
 *
 * @package formidable-abandonment
 */

/**
 * Class FrmAbandonmentEditEntry
 *
 * @since 1.0
 */
class FrmAbandonmentEditEntry {

	/**
	 * Add abandonment shortcode to helper shortcode options used in actions and other places.
	 *
	 * @since 1.0
	 *
	 * @param array<string> $options Helper shortcodes.
	 *
	 * @return array<string>
	 */
	public static function helper_shortcodes_options( $options ) {
		$adv_opts = array(
			'frm-signed-edit-link id=[id]' => __( 'Abandonment Edit Link', 'formidable-abandonment' ),
		);

		$options = array_merge( $options, $adv_opts );
		return $options;
	}

	/**
	 * Create an abandonment entry edit link which could be sent via email action and
	 * give a possibility to the link holder to edit the abandoned entry.
	 *
	 * @since 1.0
	 *
	 * @param array<string|int> $atts The params from the shortcode.
	 *
	 * @return string
	 */
	public static function entry_edit_link_shortcode( $atts ) {
		$atts = shortcode_atts(
			array(
				'id'       => isset( $atts['entry_id'] ) ? $atts['entry_id'] : 0, // Fallback to entry_id.
				'label'    => __( 'Continue', 'formidable-abandonment' ),
				'class'    => '',
				'page_id'  => 0,
			),
			$atts
		);

		$url = self::get_edit_link( $atts );

		if ( $url && $atts['label'] ) {
			$url = '<a href="' . esc_url( $url ) . '" class="' . esc_attr( $atts['class'] ) . '">' . $atts['label'] . '</a>';
		}

		return $url;
	}

	/**
	 * Parse together the link to edit an entry.
	 *
	 * @param array<string> $atts Shortcode parameters.
	 *
	 * @return string
	 */
	private static function get_edit_link( $atts ) {
		$token      = FrmAbandonmentEntries::get_token( absint( $atts['id'] ) );
		$base_url   = $atts['page_id'] ? get_permalink( (int) $atts['page_id'] ) : admin_url( 'admin-ajax.php?action=frm_forms_preview' );

		if ( ! $token ) {
			return '';
		}

		// Create a bypass link using openssl encryption.
		return add_query_arg(
			array(
				'frm_action' => 'continue',
				'secret'     => urlencode( base64_encode( $token ) ),
			),
			$base_url
		);
	}

	/**
	 * Load the correct form in the preview.
	 *
	 * @param object $form The form to display.
	 *
	 * @return object
	 */
	public static function set_current_form( $form ) {
		if ( ! FrmAppHelper::is_preview_page() ) {
			return $form;
		}

		global $frm_vars;

		if ( empty( $frm_vars['editing_entry'] ) ) {
			$frm_action = FrmAppHelper::simple_get( 'frm_action' );
			self::allow_form_edit( 'new' );

			// Switch to the form for the secret key.
			if ( empty( $frm_vars['editing_entry'] ) ) {
				return $form;
			}
		}

		$entry = FrmEntry::getOne( absint( $frm_vars['editing_entry'] ) );
		if ( $entry ) {
			$form = FrmForm::getOne( $entry->form_id );
		}

		return $form;
	}

	/**
	 * Check if form should automatically be in edit mode.
	 *
	 * @since 1.0
	 *
	 * @param string $action The action this form will take.
	 *
	 * @return string
	 */
	public static function allow_form_edit( $action ) {
		$frm_action = FrmAppHelper::simple_get( 'frm_action' );
		if ( $action !== 'new' || $frm_action !== 'continue' ) {
			return $action;
		}

		$entry_id = self::check_permission_to_bypass();
		if ( is_wp_error( $entry_id ) ) {
			return $action;
		}

		global $frm_vars;
		$frm_vars['editing_entry'] = $entry_id;

		return 'edit';
	}

	/**
	 * Bypass user permission check and get all entry data.
	 *
	 * @since 1.0
	 *
	 * @param mixed        $allowed Allowed users.
	 * @param array<mixed> $args {
	 *   Form args.
	 *   @type object     $form
	 *   @type int|object $entry
	 * }
	 *
	 * @return mixed
	 */
	public static function get_all_fields_and_bypass_permission( $allowed, $args ) {
		if ( is_wp_error( self::check_permission_to_bypass( is_numeric( $args['entry'] ) ? $args['entry'] : 0 ) ) ) {
			return $allowed;
		}

		/* @phpstan-ignore-next-line */
		$where               = array( 'fr.id' => $args['form']->id );
		$where_key           = is_numeric( $args['entry'] ) ? 'it.id' : 'item_key';
		$where[ $where_key ] = $args['entry'];
		return FrmEntry::getAll( $where, ' ORDER BY created_at DESC', 1, true );
	}

	/**
	 * Set the preview form key from the abandonment secret in the edit link.
	 * This hooks in before the FrmFormsController::preview function is called.
	 *
	 * @since 1.0
	 *
	 * @return void
	 */
	public static function before_preview() {
		if ( ! empty( $_GET['form'] ) ) {
			return;
		}

		$encrypted_token = FrmAppHelper::simple_get( 'secret' );
		if ( ! $encrypted_token ) {
			return;
		}

		$decrypted_value  = ( new FrmAbandonmentEncryptionHelper() )->decrypt( base64_decode( urldecode( $encrypted_token ) ) );
		if ( is_wp_error( $decrypted_value ) ) {
			return;
		}

		$entry_id = FrmAbandonmentAppHelper::get_entry_id_from_token( $decrypted_value );
		if ( ! $entry_id ) {
			return;
		}

		$form_id  = FrmDb::get_var( 'frm_items', array( 'id' => $entry_id ), 'form_id' );
		if ( ! $form_id ) {
			return;
		}

		$_GET['form'] = FrmForm::get_key_by_id( $form_id );
	}

	/**
	 * Bypass user permission check and get all entry data.
	 *
	 * @since 1.0
	 *
	 * @param float|int|string $current_entry_id The id of the entry to edit.
	 *
	 * @return WP_Error|int
	 */
	private static function check_permission_to_bypass( $current_entry_id = 0 ) {
		$not_allowed_parameters = array( 'entry_id', 'id' );
		foreach ( $not_allowed_parameters as $qs_key ) {
			if ( isset( $_GET[ $qs_key ] ) ) {
				return new WP_Error(
					'http_request_failed',
					__( 'Not authorized.', 'formidable-abandonment' )
				);
			}
		}

		$encrypted_token = base64_decode( urldecode( FrmAppHelper::get_param( 'secret', '', 'get', 'sanitize_text_field' ) ) );
		if ( ! $encrypted_token ) {
			return new WP_Error(
				'http_wrong_secret',
				__( 'That link has expired.', 'formidable-abandonment' )
			);
		}

		$decrypted_value  = ( new FrmAbandonmentEncryptionHelper() )->decrypt( $encrypted_token );
		if ( is_wp_error( $decrypted_value ) ) {
			return new WP_Error(
				'wrong_encrypted',
				__( 'That link has expired. The entry has already been submitted.', 'formidable-abandonment' )
			);
		}

		$entry_id = FrmAbandonmentAppHelper::get_entry_id_from_token( $decrypted_value );

		// If entry id not accessible from this stage it means link is expired or submitted before etc.
		if ( ! $entry_id || ( $current_entry_id && absint( $current_entry_id ) !== absint( $entry_id ) ) ) {
			return new WP_Error(
				'http_request_failed',
				__( 'Not authorized.', 'formidable-abandonment' )
			);
		}

		$edit_token = FrmAbandonmentEntries::get_token( (int) $entry_id );

		if ( $edit_token !== $encrypted_token ) {
			return new WP_Error(
				'http_request_failed',
				__( 'Not authorized.', 'formidable-abandonment' )
			);
		}

		return (int) $entry_id;
	}

}
