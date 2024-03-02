<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class FrmAbandonmentActionController
 *
 * @since 1.0
 *
 * @package formidable-abandonment
 */

/**
 * Handling action related methods for abandoned activated forms.
 *
 * @since 1.0
 */
class FrmAbandonmentActionController {

	/**
	 * After triggering email action with "frm_add_form_action" this method will modify the created email action contents for abandoned form.
	 * Customizing email message contains submitted abandoned entry edit link.
	 *
	 * @since 1.0
	 *
	 * @param WP_Post $email_action Email action content.
	 *
	 * @return WP_Post
	 */
	public static function add_customized_email_action( $email_action ) {
		// Nonce is verified before @frm_add_form_action.
		// We are checking the abandonment_form_action to ensure this ajax triggered by quick create a abandoned email button.
		$form_id = FrmAppHelper::get_post_param( 'form_id', 0, 'absint' );
		if ( ! isset( $_POST['abandonment_form_action'] ) || ! $form_id ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
			return $email_action;
		}

		$first_email_field = '';
		$email_field       = FrmField::get_all_types_in_form( $form_id, 'email', 1, 'include' );
		if ( ! empty( $email_field ) ) {
			$first_email_field = '[' . $email_field->id . ']';
		}

		$email_action->post_title               = esc_html__( 'Abandoned Form Recovery', 'formidable-abandonment' );
		/* @phpstan-ignore-next-line */
		$email_action->post_content['email_to'] = $first_email_field;
		$email_action->post_content['event']    = array( 'abandoned' );

		$email_action->post_content['email_subject'] = sprintf(
			/* translators: %1$s: Site name shortcode */
			esc_html__( 'Your submission on %1$s is incomplete!', 'formidable-abandonment' ),
			'[sitename]'
		);

		$email_action->post_content['email_message'] = sprintf(
			/* translators: %1$s: Abandoned entry link shortcode */
			esc_html__( 'Hello there, It looks like you may have started a form that has not yet been completed. Here is a link for you to pick up right where you left off: %1$s', 'formidable-abandonment' ),
			"\n" . '[frm-signed-edit-link id=[id]]'
		);

		return $email_action;
	}

	/**
	 * Add the abandoned action trigger to actions.
	 *
	 * @since 1.0
	 *
	 * @param array<string> $triggers Array of event triggers.
	 *
	 * @return array<string>
	 */
	public static function add_abandoned_trigger( $triggers ) {
		$triggers['abandoned'] = esc_html__( 'Entry is abandoned', 'formidable-abandonment' );
		return $triggers;
	}

	/**
	 * Add the abandoned action trigger to email action.
	 *
	 * @since 1.0
	 *
	 * @param array<array<string>> $settings Array of event triggers.
	 *
	 * @return array<array<string>|int>
	 */
	public static function email_action_control( $settings ) {
		if ( ! in_array( 'abandoned', $settings['event'], true ) ) {
			$settings['event'][] = 'abandoned';
		}

		return $settings;
	}

	/**
	 * Trigger abandonment action.
	 *
	 * @since 1.0
	 *
	 * @param int $entry_id Entry id.
	 * @param int $form_id Form id.
	 *
	 * @return void
	 */
	public static function trigger_abandonment_actions( $entry_id, $form_id ) {
		FrmFormActionsController::trigger_actions( 'abandoned', $form_id, $entry_id );
	}

	/**
	 * Prepare and trigger abandoned action.
	 *
	 * @since 1.0
	 *
	 * @param bool         $skip If the form action should be skipped.
	 * @param array<mixed> $args {
	 *   Array of args.
	 *   @type array      $action
	 *   @type object|int $entry
	 *   @type object     $form
	 *   @type string     $event
	 * }
	 *
	 * @return boolean
	 */
	public static function maybe_skip_action( $skip, $args = array() ) {
		if ( $skip ) {
			return $skip;
		}

		$entry = is_object( $args['entry'] ) ? $args['entry'] : FrmEntry::getOne( $args['entry'], true );
		if ( ! $entry || ! $entry->is_draft ) {
			return $skip;
		}

		if ( FrmAbandonmentAppHelper::IN_PROGRESS_ENTRY_STATUS === $entry->is_draft ) {
			// Always skip in progress entries.
			$skip = true;
		} elseif ( FrmAbandonmentAppHelper::ABANDONED_ENTRY_STATUS === $entry->is_draft && 'abandoned' !== $args['event'] ) {
			// Skip abandoned entries if the trigger is not abandoned.
			$skip = true;
		}

		return $skip;
	}
}
