<?php
/**
 * Baseline spam / abuse checks for lead submissions.
 *
 * Always active in the lead-capture plugin. HLM Security Shield adds
 * stronger layers (timing tokens, Turnstile, Akismet) when installed.
 *
 * @package HLM_Lead_Capture
 */

defined( 'ABSPATH' ) || exit;

/**
 * Lightweight honeypot and per-IP rate limiting.
 */
class HLM_Abuse_Guard {

	const SUBMIT_LIMIT  = 5;
	const SUBMIT_WINDOW = 900; // 15 minutes.

	/**
	 * Run baseline abuse checks before field validation.
	 *
	 * @param array $data Raw submission payload.
	 * @return true|WP_Error
	 */
	public static function check( array $data ) {
		if ( ! empty( $data['hlm_hp_url'] ) ) {
			return new WP_Error(
				'hlm_bot_detected',
				__( 'Submission rejected.', 'hlm-lead-capture' ),
				array( 'status' => 403 )
			);
		}

		if ( class_exists( 'HLM_Security\Rate_Limiter' ) ) {
			return true;
		}

		return self::rate_limit_submit();
	}

	/**
	 * Per-IP submit cap when HLM Security is not handling REST limits.
	 *
	 * @return true|WP_Error
	 */
	private static function rate_limit_submit() {
		$ip       = self::client_ip();
		$store_key = 'hlm_lc_rl_' . md5( 'submit|' . $ip );
		$record   = get_transient( $store_key );

		if ( ! is_array( $record ) ) {
			$record = array(
				'count' => 0,
				'start' => time(),
			);
		}

		if ( ( time() - (int) $record['start'] ) >= self::SUBMIT_WINDOW ) {
			$record = array(
				'count' => 0,
				'start' => time(),
			);
		}

		if ( $record['count'] >= self::SUBMIT_LIMIT ) {
			return new WP_Error(
				'hlm_rate_limited',
				__( 'Too many submissions from your connection. Please wait and try again.', 'hlm-lead-capture' ),
				array( 'status' => 429 )
			);
		}

		++$record['count'];
		set_transient( $store_key, $record, self::SUBMIT_WINDOW );

		return true;
	}

	/**
	 * Resolve visitor IP for rate limiting.
	 *
	 * @return string
	 */
	private static function client_ip() {
		if ( class_exists( 'HLM_Security\Plugin' ) ) {
			return \HLM_Security\Plugin::client_ip();
		}

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
