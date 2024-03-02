<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * App helper
 *
 * @package formidable-abandonment
 */

/**
 * Class FrmAbandonmentAppHelper
 */
class FrmAbandonmentAppHelper {

	/**
	 * "In Progress" entry status.
	 *
	 * @since 1.0
	 * @var int
	 */
	const IN_PROGRESS_ENTRY_STATUS = 2;

	/**
	 * "Abandoned" entry status.
	 *
	 * @since 1.0
	 * @var int
	 */
	const ABANDONED_ENTRY_STATUS = 3;

	/**
	 * Plugin version.
	 *
	 * @var string
	 */
	public static $plug_version = '1.0.1';

	/**
	 * Get plugin version.
	 *
	 * @return string
	 */
	public static function plugin_version() {
		return self::$plug_version;
	}

	/**
	 * Gets plugin folder name.
	 *
	 * @return string
	 */
	public static function plugin_folder() {
		return basename( self::plugin_path() );
	}

	/**
	 * Gets plugin file path.
	 *
	 * @return string
	 */
	public static function plugin_file() {
		return self::plugin_path() . '/formidable-abandonment.php';
	}

	/**
	 * Gets plugin path.
	 *
	 * @return string
	 */
	public static function plugin_path() {
		return dirname( dirname( dirname( __FILE__ ) ) );
	}

	/**
	 * Gets plugin URL.
	 *
	 * @return string
	 */
	public static function plugin_url() {
		return plugins_url( '', self::plugin_path() . '/formidable-abandonment.php' );
	}

	/**
	 * Gets plugin relative URL.
	 *
	 * @return string
	 */
	public static function relative_plugin_url() {
		return str_replace( array( 'https:', 'http:' ), '', self::plugin_url() );
	}

	/**
	 * Checks if this plugin is safe to run.
	 *
	 * @return bool
	 */
	public static function is_compatible() {
		$is_formidable_lite_compatible = class_exists( 'FrmAppHelper' ) && version_compare( FrmAppHelper::$plug_version, '6.4.2', '>=' );
		$is_formidable_pro_compatible  = class_exists( 'FrmProDb' ) && version_compare( FrmProDb::$plug_version, '6.5.2', '>=' ); // Todo this must be changed to 6.5.3 https://github.com/Strategy11/formidable-pro/pull/4492.

		return $is_formidable_lite_compatible && $is_formidable_pro_compatible;
	}

	/**
	 * Get JS url.
	 *
	 * @since 1.0
	 *
	 * @param string $type Front or admin to load script.
	 *
	 * @return string File path.
	 */
	public static function use_minified_js_file( $type = 'front' ) {
		$type    = 'admin' === $type ? $type : 'front';
		$min_url = self::has_unminified_js_url( $type );
		return self::debug_scripts_are_on() && $min_url ? $min_url : self::get_minified_js_url( $type );
	}

	/**
	 * Weather WP script debug is enabled.
	 *
	 * @since 1.0
	 *
	 * @return bool
	 */
	public static function debug_scripts_are_on() {
		return defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG;
	}

	/**
	 * Get unminified JS url if the file exists.
	 *
	 * @since 1.0
	 *
	 * @param string $type Front or admin to load script.
	 *
	 * @return string|false File path.
	 */
	private static function has_unminified_js_url( $type = 'front' ) {
		$url = self::get_js_url( $type );
		return is_readable( self::plugin_path() . $url ) ? self::plugin_url() . $url : false;
	}

	/**
	 * Get minified JS url.
	 *
	 * @since 1.0
	 *
	 * @param string $type Front or admin to load script.
	 *
	 * @return string File path.
	 */
	private static function get_minified_js_url( $type = 'front' ) {
		return self::plugin_url() . self::get_js_url( $type, '.min' );
	}

	/**
	 * Get the relative path to the js file with the file name.
	 *
	 * @since 1.0
	 *
	 * @param string $type Front or admin to load script.
	 * @param string $min  Maybe append .min to the url.
	 *
	 * @return string
	 */
	private static function get_js_url( $type, $min = '' ) {
		return '/assets/js/formidable-abandonment' . ( 'admin' === $type ? '-admin' : '' ) . $min . '.js';
	}

	/**
	 * Get UUID from cookie and return it with form id to collect for an entry.
	 *
	 * @since 1.0
	 *
	 * @param int $form_id Form ID.
	 *
	 * @return string
	 */
	public static function build_uuid_with_cookie( $form_id ) {
		$user_uuid = isset( $_COOKIE['_frmuuid'] ) ? sanitize_key( wp_unslash( $_COOKIE['_frmuuid'] ) ) : '';

		if ( ! $user_uuid ) {
			return '';
		}

		return $user_uuid . '-' . $form_id;
	}

	/**
	 * Get entry id from token value.
	 *
	 * @since 1.0
	 *
	 * @param string $decrypted_value Decrypted string value of a token.
	 *
	 * @return false|int
	 */
	public static function get_entry_id_from_token( $decrypted_value ) {
		$pos = strrpos( $decrypted_value, '-' );

		if ( false === $pos ) {
			return false;
		}

		$entry_id = substr( $decrypted_value, $pos + 1 );

		return (int) $entry_id;
	}

	/**
	 * Check whether abandonment is enabled for a form.
	 *
	 * @since x.x
	 *
	 * @param int $form_id Form id.
	 *
	 * @return bool
	 */
	public static function is_abandonment_enabled( $form_id ) {
		$form   = FrmForm::getOne( $form_id );

		if ( ! $form ) {
			return false;
		}

		return ! empty( $form->options['enable_abandonment'] );
	}

}
