<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Class FrmAbandonmentFormSettingsController
 *
 * @since 1.0
 *
 * @package formidable-abandonment
 */

/**
 * Controller for form setting.
 *
 * @since 1.0
 */
class FrmAbandonmentFormSettingsController {

	/**
	 * Add abandonment setting section to form settings.
	 *
	 * @since 1.0
	 *
	 * @param array<mixed> $sections Form settings sections.
	 *
	 * @return array<mixed>
	 */
	public static function add_settings_section( $sections ) {
		$sections['abandonment'] = array(
			'function' => array( __CLASS__, 'settings_section' ),
			'name'     => __( 'Form Abandonment', 'formidable-abandonment' ),
			'icon'     => 'frm_icon_font frm_abandoned_icon',
			'anchor'   => 'abandonment',
		);
		return $sections;
	}

	/**
	 * Form settings.
	 *
	 * @since 1.0
	 *
	 * @param array<mixed> $values Form settings.
	 *
	 * @return void
	 */
	public static function settings_section( $values ) {
		require FrmAbandonmentAppHelper::plugin_path() . '/views/settings/settings.php';
	}

}
