<?php
/**
 * Security plugin bootstrap.
 *
 * @package HLM_Security
 */

namespace HLM_Security;

defined( 'ABSPATH' ) || exit;

/**
 * Wires all security modules.
 */
final class Plugin {

	/**
	 * Singleton instance.
	 *
	 * @var self|null
	 */
	private static $instance = null;

	/**
	 * Get plugin instance.
	 *
	 * @return self
	 */
	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Constructor.
	 */
	private function __construct() {
		Hardening::init();
		Login_Guard::init();
		Comment_Guard::init();
		Form_Shield::init();

		add_filter( 'hlm_lead_capture_security_check', array( Form_Shield::class, 'validate_lead_submission' ), 10, 4 );
		add_filter( 'hlm_lead_capture_allowed_fields', array( Form_Shield::class, 'allowed_fields' ), 10, 2 );
		add_filter( 'hlm_lead_form_localize', array( Form_Shield::class, 'localize_form_data' ) );
	}

	/**
	 * Resolve client IP address.
	 *
	 * @return string
	 */
	public static function client_ip() {
		$candidates = array(
			'HTTP_CF_CONNECTING_IP',
			'HTTP_X_FORWARDED_FOR',
			'REMOTE_ADDR',
		);

		foreach ( $candidates as $key ) {
			if ( empty( $_SERVER[ $key ] ) ) {
				continue;
			}

			$raw = sanitize_text_field( wp_unslash( $_SERVER[ $key ] ) );

			if ( 'HTTP_X_FORWARDED_FOR' === $key ) {
				$parts = explode( ',', $raw );
				$raw   = trim( $parts[0] );
			}

			if ( filter_var( $raw, FILTER_VALIDATE_IP ) ) {
				return $raw;
			}
		}

		return '0.0.0.0';
	}
}
