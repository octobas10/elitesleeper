<?php
/**
 * Transient-based rate limiter for REST, login, and form endpoints.
 *
 * @package HLM_Security
 */

namespace HLM_Security;

defined( 'ABSPATH' ) || exit;

/**
 * Per-key sliding-window rate limiter backed by transients.
 */
class Rate_Limiter {

	/**
	 * Check whether an action is within its limit.
	 *
	 * @param string $bucket Action bucket name.
	 * @param int    $limit  Max attempts in the window.
	 * @param int    $window Window length in seconds.
	 * @param string $key    Optional override key (defaults to client IP).
	 * @return true|\WP_Error
	 */
	public static function check( $bucket, $limit, $window, $key = '' ) {
		$key = $key ?: Plugin::client_ip();
		$hash = md5( $bucket . '|' . $key );
		$store_key = 'hlm_rl_' . $hash;
		$record = get_transient( $store_key );

		if ( ! is_array( $record ) ) {
			$record = array(
				'count' => 0,
				'start' => time(),
			);
		}

		if ( ( time() - (int) $record['start'] ) >= $window ) {
			$record = array(
				'count' => 0,
				'start' => time(),
			);
		}

		if ( $record['count'] >= $limit ) {
			return new \WP_Error(
				'hlm_rate_limited',
				__( 'Too many requests. Please wait a moment and try again.', 'hlm-security' ),
				array( 'status' => 429 )
			);
		}

		++$record['count'];
		set_transient( $store_key, $record, $window );

		return true;
	}

	/**
	 * Register REST rate limiting for HLM and auth-sensitive routes.
	 */
	public static function init_rest_limits() {
		add_filter(
			'rest_pre_dispatch',
			function ( $result, $server, $request ) {
				if ( is_wp_error( $result ) ) {
					return $result;
				}

				$route = $request->get_route();

				if ( 0 === strpos( $route, '/hlm/v1/' ) ) {
					$check = self::check( 'hlm_rest', 30, MINUTE_IN_SECONDS );
					if ( is_wp_error( $check ) ) {
						return $check;
					}
				}

				if ( '/hlm/v1/submit' === $route && 'POST' === $request->get_method() ) {
					$check = self::check( 'hlm_submit', 5, 15 * MINUTE_IN_SECONDS );
					if ( is_wp_error( $check ) ) {
						return $check;
					}
				}

				return $result;
			},
			10,
			3
		);
	}
}
