<?php
/**
 * Platform hardening - headers, XML-RPC, fingerprint reduction.
 *
 * @package HLM_Security
 */

namespace HLM_Security;

defined( 'ABSPATH' ) || exit;

/**
 * Core WordPress hardening.
 */
class Hardening {

	/**
	 * Register hardening hooks.
	 */
	public static function init() {
		Rate_Limiter::init_rest_limits();

		add_filter( 'xmlrpc_enabled', '__return_false' );
		add_filter( 'wp_headers', array( __CLASS__, 'security_headers' ) );
		add_action( 'send_headers', array( __CLASS__, 'send_security_headers' ) );
		add_action( 'init', array( __CLASS__, 'block_author_enumeration' ), 1 );
		add_filter( 'the_generator', '__return_empty_string' );
		add_filter( 'rest_authentication_errors', array( __CLASS__, 'restrict_user_endpoints' ) );
		add_filter( 'upload_mimes', array( __CLASS__, 'restrict_svg_uploads' ), 20 );
		add_filter( 'wp_handle_upload_prefilter', array( __CLASS__, 'sanitize_svg_upload' ) );
	}

	/**
	 * Send security headers on front-end and admin responses.
	 */
	public static function send_security_headers() {
		if ( headers_sent() ) {
			return;
		}

		header( 'X-Content-Type-Options: nosniff' );
		header( 'X-Frame-Options: SAMEORIGIN' );
		header( 'Referrer-Policy: strict-origin-when-cross-origin' );
		header( 'Permissions-Policy: camera=(), microphone=(), geolocation=(), payment=()' );
		header( 'X-XSS-Protection: 0' );

		if ( is_ssl() ) {
			header( 'Strict-Transport-Security: max-age=31536000; includeSubDomains' );
		}
	}

	/**
	 * Add security headers via WordPress filter (REST/admin compatibility).
	 *
	 * @param array $headers Existing headers.
	 * @return array
	 */
	public static function security_headers( $headers ) {
		$headers['X-Content-Type-Options'] = 'nosniff';
		$headers['X-Frame-Options']        = 'SAMEORIGIN';
		$headers['Referrer-Policy']        = 'strict-origin-when-cross-origin';
		$headers['Permissions-Policy']     = 'camera=(), microphone=(), geolocation=(), payment=()';

		return $headers;
	}

	/**
	 * Block ?author= user enumeration probes.
	 */
	public static function block_author_enumeration() {
		if ( is_admin() || wp_doing_ajax() || wp_doing_cron() || ( defined( 'REST_REQUEST' ) && REST_REQUEST ) ) {
			return;
		}

		if ( isset( $_GET['author'] ) && is_numeric( $_GET['author'] ) ) {
			wp_safe_redirect( home_url( '/' ), 301 );
			exit;
		}
	}

	/**
	 * Hide user listing from unauthenticated REST consumers.
	 *
	 * @param mixed $result Existing auth error.
	 * @return mixed
	 */
	public static function restrict_user_endpoints( $result ) {
		if ( ! empty( $result ) ) {
			return $result;
		}

		if ( ! is_user_logged_in() ) {
			$route = isset( $GLOBALS['wp']->query_vars['rest_route'] )
				? $GLOBALS['wp']->query_vars['rest_route']
				: ( $_SERVER['REQUEST_URI'] ?? '' );

			if ( false !== strpos( $route, '/wp/v2/users' ) ) {
				return new \WP_Error(
					'hlm_rest_forbidden',
					__( 'Forbidden.', 'hlm-security' ),
					array( 'status' => 403 )
				);
			}
		}

		return $result;
	}

	/**
	 * Restrict SVG uploads to administrators.
	 *
	 * @param array $mimes Allowed mime types.
	 * @return array
	 */
	public static function restrict_svg_uploads( $mimes ) {
		if ( ! current_user_can( 'manage_options' ) ) {
			unset( $mimes['svg'], $mimes['svgz'] );
		}

		return $mimes;
	}

	/**
	 * Strip dangerous SVG content on upload.
	 *
	 * @param array $file Upload file data.
	 * @return array
	 */
	public static function sanitize_svg_upload( $file ) {
		$ext = strtolower( pathinfo( $file['name'], PATHINFO_EXTENSION ) );

		if ( 'svg' !== $ext && 'svgz' !== $ext ) {
			return $file;
		}

		$contents = file_get_contents( $file['tmp_name'] );

		if ( false === $contents ) {
			$file['error'] = __( 'Unable to read SVG file.', 'hlm-security' );
			return $file;
		}

		$blocked = array(
			'/<script[\s>]/i',
			'/javascript:/i',
			'/on[a-z]+\s*=/i',
			'/<iframe[\s>]/i',
			'/<embed[\s>]/i',
			'/<object[\s>]/i',
		);

		foreach ( $blocked as $pattern ) {
			if ( preg_match( $pattern, $contents ) ) {
				$file['error'] = __( 'SVG contains disallowed content.', 'hlm-security' );
				return $file;
			}
		}

		return $file;
	}
}
