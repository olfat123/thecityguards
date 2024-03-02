<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class responsible for encrypting and decrypting data.
 *
 * @since 1.0
 */
final class FrmAbandonmentEncryptionHelper {

	/**
	 * Key to use for encryption.
	 *
	 * @since 1.0
	 * @var string $key
	 */
	private $key;

	/**
	 * Salt to use for encryption.
	 *
	 * @since 1.0
	 * @var string $salt
	 */
	private $salt;

	/**
	 * Salt Settings.
	 *
	 * @since 1.0
	 * @var array<string> $settings
	 */
	private $settings;

	/**
	 * Constructor.
	 *
	 * @since 1.0
	 */
	public function __construct() {
		$this->settings = array();
		$settings = get_option( 'frm_abandonment_encryption' );
		if ( is_array( $settings ) ) {
			$this->settings = $settings;
		}

		$this->key      = $this->get_key();
		$this->salt     = $this->get_salt();
	}

	/**
	 * Destructor | Updating the setting on class destruction.
	 *
	 * @since 1.0
	 */
	public function __destruct() {
		update_option( 'frm_abandonment_encryption', $this->settings );
	}

	/**
	 * Encrypts a value.
	 *
	 * If a user-based key is set, that key is used. Otherwise the default key is used.
	 *
	 * @since 1.0
	 *
	 * @param string $value Value to encrypt.
	 * @return string|WP_Error Encrypted value, or WP_Error on failure.
	 */
	public function encrypt( $value ) {
		$method = 'aes-256-ctr';
		$ivlen  = openssl_cipher_iv_length( $method );

		if ( ! $ivlen ) {
			return new WP_Error( 'php_openssl', $this->fail_message() );
		}

		$iv = openssl_random_pseudo_bytes( $ivlen );
		if ( ! $iv ) {
			return new WP_Error( 'php_openssl', $this->fail_message() );
		}

		$raw_value = openssl_encrypt( $value . $this->salt, $method, $this->key, 0, $iv );
		if ( ! $raw_value ) {
			return new WP_Error( 'php_openssl', __( 'Oops, Something is wrong with openssl.', 'formidable-abandonment' ) );
		}

		return base64_encode( $iv . $raw_value ); // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_encode
	}

	/**
	 * Decrypts a value.
	 *
	 * If a user-based key is set, that key is used. Otherwise the default key is used.
	 *
	 * @since 1.0
	 *
	 * @param string $raw_value Value to decrypt.
	 * @return string|WP_Error Decrypted value, or WP_Error on failure.
	 */
	public function decrypt( $raw_value ) {
		$raw_value = base64_decode( $raw_value, true ); // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_decode
		if ( ! $raw_value ) {
			return new WP_Error( 'php_openssl', __( 'That access key has a problem.', 'formidable-abandonment' ) );
		}

		$method = 'aes-256-ctr';
		$ivlen  = openssl_cipher_iv_length( $method );

		if ( ! $ivlen ) {
			return new WP_Error( 'php_openssl', $this->fail_message() );
		}

		$iv = substr( $raw_value, 0, $ivlen );

		$raw_value = substr( $raw_value, $ivlen );

		$value = openssl_decrypt( $raw_value, $method, $this->key, 0, $iv );
		if ( ! $value || substr( $value, - strlen( $this->salt ) ) !== $this->salt ) {
			return new WP_Error( 'php_openssl', __( 'Oops, the salt key has been changed or is unreadable.', 'formidable-abandonment' ) );
		}

		return substr( $value, 0, - strlen( $this->salt ) );
	}

	/**
	 * Get the standard error message.
	 *
	 * @since 1.0
	 *
	 * @return string
	 */
	private function fail_message() {
		return __( 'Oops, that link is invalid.', 'formidable-abandonment' );
	}

	/**
	 * Gets the default encryption key to use.
	 *
	 * @since 1.0
	 *
	 * @return string Default (not user-based) encryption key.
	 */
	private function get_key() {
		if ( ! empty( $this->settings['encrypt_key'] ) ) {
			return $this->settings['encrypt_key'];
		}

		$this->settings['encrypt_key'] = $this->generate_crypto_bytes();

		return $this->settings['encrypt_key'];
	}

	/**
	 * Gets the default encryption salt to use.
	 *
	 * @since 1.0
	 *
	 * @return string Encryption salt.
	 */
	private function get_salt() {
		if ( ! empty( $this->settings['encrypt_salt'] ) ) {
			return $this->settings['encrypt_salt'];
		}

		$this->settings['encrypt_salt'] = $this->generate_crypto_bytes();

		return $this->settings['encrypt_salt'];
	}

	/**
	 * Generate crypto key.
	 *
	 * @since 1.0
	 *
	 * @return string key for encryption.
	 */
	private function generate_crypto_bytes() {
		// Ready for easy migration from old php versions.
		if ( version_compare( phpversion(), '7.0', '>' ) ) {
			return bin2hex( random_bytes( 25 ) );
		}

		return wp_generate_password( 20 );
	}
}
