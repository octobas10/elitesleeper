<?php
/**
 * Login brute-force protection.
 *
 * @package HLM_Security
 */

namespace HLM_Security;

defined( 'ABSPATH' ) || exit;

/**
 * Rate-limits wp-login.php authentication attempts.
 */
class Login_Guard {

	/**
	 * Register login guard hooks.
	 */
	public static function init() {
		add_filter( 'authenticate', array( __CLASS__, 'check_lockout' ), 30, 1 );
		add_action( 'wp_login_failed', array( __CLASS__, 'record_failure' ) );
		add_action( 'wp_login', array( __CLASS__, 'clear_failures' ), 10, 2 );
	}

	/**
	 * Block login when too many failures recorded for IP or username.
	 *
	 * @param \WP_User|\WP_Error|null $user User or error.
	 * @return \WP_User|\WP_Error|null
	 */
	public static function check_lockout( $user ) {
		if ( is_wp_error( $user ) ) {
			return $user;
		}

		$ip_check = Rate_Limiter::check( 'login_ip', 10, 15 * MINUTE_IN_SECONDS );

		if ( is_wp_error( $ip_check ) ) {
			return new \WP_Error(
				'hlm_login_locked',
				__( 'Too many login attempts. Please try again in 15 minutes.', 'hlm-security' )
			);
		}

		$username = isset( $_POST['log'] ) ? sanitize_user( wp_unslash( $_POST['log'] ) ) : '';

		if ( $username ) {
			$user_check = Rate_Limiter::check( 'login_user_' . md5( strtolower( $username ) ), 5, 15 * MINUTE_IN_SECONDS );

			if ( is_wp_error( $user_check ) ) {
				return new \WP_Error(
					'hlm_login_locked',
					__( 'Too many login attempts for this account. Please try again later.', 'hlm-security' )
				);
			}
		}

		return $user;
	}

	/**
	 * Record a failed login attempt.
	 *
	 * @param string $username Attempted username.
	 */
	public static function record_failure( $username ) {
		Rate_Limiter::check( 'login_ip', 10, 15 * MINUTE_IN_SECONDS );

		if ( $username ) {
			Rate_Limiter::check( 'login_user_' . md5( strtolower( $username ) ), 5, 15 * MINUTE_IN_SECONDS );
		}
	}

	/**
	 * Clear failure counters on successful login.
	 *
	 * @param string   $username Username.
	 * @param \WP_User $user     User object.
	 */
	public static function clear_failures( $username, $user ) {
		unset( $user );
		delete_transient( 'hlm_rl_' . md5( 'login_ip|' . Plugin::client_ip() ) );

		if ( $username ) {
			delete_transient( 'hlm_rl_' . md5( 'login_user_' . md5( strtolower( $username ) ) . '|' . Plugin::client_ip() ) );
		}
	}
}
