<?php
/**
 * Lead form bot defense - honeypot, timing, Turnstile, Akismet, duplicates.
 *
 * @package HLM_Security
 */

namespace HLM_Security;

defined( 'ABSPATH' ) || exit;

/**
 * Validates lead capture submissions before storage.
 */
class Form_Shield {

	const MIN_SUBMIT_SECONDS = 3;
	const MAX_TOKEN_AGE      = HOUR_IN_SECONDS;

	/**
	 * Allowed fields per form type (whitelist).
	 *
	 * @var array<string, string[]>
	 */
	private static $field_map = array(
		'student'    => array(
			'first_name', 'last_name', 'email', 'phone', 'mobile', 'address', 'city', 'state', 'zip',
			'grad_year', 'education_level', 'desired_degree', 'category', 'campus', 'program_of_interest',
			'tcpa_consent', 'tcpa_text', 'trustedformcerturl', 'universal_leadid', 'page_url',
			'submitted_at', 'source',
		),
		'university' => array(
			'institution_name', 'website', 'institution_type', 'first_name', 'last_name', 'title',
			'email', 'phone', 'department', 'service_interest', 'target_students',
			'lead_volume', 'budget', 'message', 'submitted_at', 'source',
		),
		'berkeley'   => array(
			'first_name', 'last_name', 'email', 'phone', 'mobile', 'address', 'city', 'state', 'zip',
			'grad_year', 'education_level', 'desired_degree', 'campus', 'program_of_interest',
			'tcpa_consent', 'tcpa_text', 'trustedformcerturl', 'universal_leadid', 'page_url',
			'submitted_at', 'source',
		),
	);

	/**
	 * Register form shield hooks.
	 */
	public static function init() {
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'maybe_enqueue_turnstile' ) );
		add_action( 'hlm_lead_capture_saved', array( __CLASS__, 'mark_duplicate' ), 10, 2 );
	}

	/**
	 * Return allowed fields for a form type.
	 *
	 * @param array  $fields    Existing fields.
	 * @param string $form_type Form type slug.
	 * @return array
	 */
	public static function allowed_fields( $fields, $form_type ) {
		return self::$field_map[ $form_type ] ?? array();
	}

	/**
	 * Validate a lead submission.
	 *
	 * @param true|\WP_Error       $result    Existing result.
	 * @param \WP_REST_Request     $request   REST request.
	 * @param string               $form_type Form type.
	 * @param array                $data      Submission payload.
	 * @return true|\WP_Error
	 */
	public static function validate_lead_submission( $result, $request, $form_type, $data ) {
		unset( $request );

		if ( is_wp_error( $result ) ) {
			return $result;
		}

		if ( ! empty( $data['hlm_hp_url'] ) ) {
			return new \WP_Error(
				'hlm_bot_detected',
				__( 'Submission rejected.', 'hlm-security' ),
				array( 'status' => 403 )
			);
		}

		$timing = self::verify_timing_token( $data['hlm_timing'] ?? '' );
		if ( is_wp_error( $timing ) ) {
			return $timing;
		}

		$turnstile = self::verify_turnstile( $data['hlm_turnstile'] ?? '' );
		if ( is_wp_error( $turnstile ) ) {
			return $turnstile;
		}

		$duplicate = self::check_duplicate( $form_type, $data );
		if ( is_wp_error( $duplicate ) ) {
			return $duplicate;
		}

		$spam = self::check_akismet( $form_type, $data );
		if ( is_wp_error( $spam ) ) {
			return $spam;
		}

		$suspicious = self::check_suspicious_content( $data );
		if ( is_wp_error( $suspicious ) ) {
			return $suspicious;
		}

		return true;
	}

	/**
	 * Append security config to lead form localization.
	 *
	 * @param array $data Existing localization data.
	 * @return array
	 */
	public static function localize_form_data( $data ) {
		$data['timingToken'] = self::create_timing_token();
		$data['turnstileSiteKey'] = self::turnstile_site_key();
		$data['minSubmitSeconds'] = self::MIN_SUBMIT_SECONDS;

		return $data;
	}

	/**
	 * Enqueue Cloudflare Turnstile when configured.
	 */
	public static function maybe_enqueue_turnstile() {
		if ( ! self::turnstile_site_key() ) {
			return;
		}

		wp_register_script(
			'cloudflare-turnstile',
			'https://challenges.cloudflare.com/turnstile/v0/api.js',
			array(),
			null,
			true
		);
	}

	/**
	 * Create an encrypted timing token tied to the visitor IP.
	 *
	 * @return string
	 */
	public static function create_timing_token() {
		$payload = wp_json_encode(
			array(
				't' => time(),
				'p' => md5( Plugin::client_ip() ),
			)
		);

		return self::seal( $payload );
	}

	/**
	 * Verify minimum human interaction time from token.
	 *
	 * @param string $token Timing token.
	 * @return true|\WP_Error
	 */
	private static function verify_timing_token( $token ) {
		if ( empty( $token ) ) {
			return new \WP_Error(
				'hlm_invalid_timing',
				__( 'Invalid submission. Please reload the page and try again.', 'hlm-security' ),
				array( 'status' => 403 )
			);
		}

		$payload = self::unseal( $token );

		if ( ! $payload ) {
			return new \WP_Error(
				'hlm_invalid_timing',
				__( 'Invalid submission. Please reload the page and try again.', 'hlm-security' ),
				array( 'status' => 403 )
			);
		}

		$data = json_decode( $payload, true );

		if ( empty( $data['t'] ) || empty( $data['p'] ) ) {
			return new \WP_Error(
				'hlm_invalid_timing',
				__( 'Invalid submission. Please reload the page and try again.', 'hlm-security' ),
				array( 'status' => 403 )
			);
		}

		if ( md5( Plugin::client_ip() ) !== $data['p'] ) {
			return new \WP_Error(
				'hlm_invalid_timing',
				__( 'Invalid submission. Please reload the page and try again.', 'hlm-security' ),
				array( 'status' => 403 )
			);
		}

		$elapsed = time() - (int) $data['t'];

		if ( $elapsed < self::MIN_SUBMIT_SECONDS ) {
			return new \WP_Error(
				'hlm_too_fast',
				__( 'Please take a moment to review your information before submitting.', 'hlm-security' ),
				array( 'status' => 403 )
			);
		}

		if ( $elapsed > self::MAX_TOKEN_AGE ) {
			return new \WP_Error(
				'hlm_token_expired',
				__( 'This form session expired. Please reload the page and try again.', 'hlm-security' ),
				array( 'status' => 403 )
			);
		}

		return true;
	}

	/**
	 * Verify Cloudflare Turnstile token when secret is configured.
	 *
	 * @param string $token Turnstile response token.
	 * @return true|\WP_Error
	 */
	private static function verify_turnstile( $token ) {
		$secret = self::turnstile_secret_key();

		if ( ! $secret ) {
			return true;
		}

		if ( empty( $token ) ) {
			return new \WP_Error(
				'hlm_captcha_required',
				__( 'Please complete the security check.', 'hlm-security' ),
				array( 'status' => 403 )
			);
		}

		$response = wp_remote_post(
			'https://challenges.cloudflare.com/turnstile/v0/siteverify',
			array(
				'timeout' => 10,
				'body'    => array(
					'secret'   => $secret,
					'response' => $token,
					'remoteip' => Plugin::client_ip(),
				),
			)
		);

		if ( is_wp_error( $response ) ) {
			return new \WP_Error(
				'hlm_captcha_failed',
				__( 'Security verification failed. Please try again.', 'hlm-security' ),
				array( 'status' => 503 )
			);
		}

		$body = json_decode( wp_remote_retrieve_body( $response ), true );

		if ( empty( $body['success'] ) ) {
			return new \WP_Error(
				'hlm_captcha_failed',
				__( 'Security verification failed. Please try again.', 'hlm-security' ),
				array( 'status' => 403 )
			);
		}

		return true;
	}

	/**
	 * Block rapid duplicate submissions with the same email.
	 *
	 * @param string $form_type Form type.
	 * @param array  $data      Submission data.
	 * @return true|\WP_Error
	 */
	private static function check_duplicate( $form_type, array $data ) {
		$email = sanitize_email( $data['email'] ?? '' );

		if ( ! $email ) {
			return true;
		}

		$key = 'hlm_dup_' . md5( $form_type . '|' . strtolower( $email ) );

		if ( get_transient( $key ) ) {
			return new \WP_Error(
				'hlm_duplicate',
				__( 'We already received your submission. Our team will be in touch soon.', 'hlm-security' ),
				array( 'status' => 409 )
			);
		}

		return true;
	}

	/**
	 * Record a successful submission to block rapid duplicates.
	 *
	 * @param string $form_type Form type.
	 * @param array  $data      Saved submission data.
	 */
	public static function mark_duplicate( $form_type, array $data ) {
		$email = sanitize_email( $data['email'] ?? '' );

		if ( ! $email ) {
			return;
		}

		$key = 'hlm_dup_' . md5( $form_type . '|' . strtolower( $email ) );
		set_transient( $key, 1, 30 * MINUTE_IN_SECONDS );
	}

	/**
	 * Run Akismet check when available.
	 *
	 * @param string $form_type Form type.
	 * @param array  $data      Submission data.
	 * @return true|\WP_Error
	 */
	private static function check_akismet( $form_type, array $data ) {
		if ( ! class_exists( '\Akismet' ) || ! \Akismet::get_api_key() ) {
			return true;
		}

		$comment = array(
			'comment_type'         => 'contact-form',
			'comment_author'       => trim( ( $data['first_name'] ?? '' ) . ' ' . ( $data['last_name'] ?? '' ) ),
			'comment_author_email' => sanitize_email( $data['email'] ?? '' ),
			'comment_content'      => wp_json_encode(
				array(
					'form_type' => $form_type,
					'message'   => $data['message'] ?? '',
					'source'    => $data['source'] ?? '',
				)
			),
			'referrer'             => sanitize_text_field( wp_unslash( $_SERVER['HTTP_REFERER'] ?? '' ) ),
		);

		$result = \Akismet::comment_check( $comment );

		if ( $result && ! empty( $result->is_spam ) ) {
			return new \WP_Error(
				'hlm_spam_detected',
				__( 'Submission rejected.', 'hlm-security' ),
				array( 'status' => 403 )
			);
		}

		return true;
	}

	/**
	 * Block obvious spam patterns and phishing URLs in free-text fields.
	 *
	 * @param array $data Submission data.
	 * @return true|\WP_Error
	 */
	private static function check_suspicious_content( array $data ) {
		$needles = array(
			'/\b(viagra|cialis|casino|crypto airdrop|wire transfer)\b/i',
			'/https?:\/\/[^\s]+/i',
		);

		$text_fields = array( 'message', 'institution_name', 'first_name', 'last_name', 'address' );

		foreach ( $text_fields as $field ) {
			if ( empty( $data[ $field ] ) ) {
				continue;
			}

			$value = (string) $data[ $field ];

			foreach ( $needles as $pattern ) {
				if ( preg_match( $pattern, $value ) && 'message' !== $field ) {
					return new \WP_Error(
						'hlm_suspicious_content',
						__( 'Submission rejected.', 'hlm-security' ),
						array( 'status' => 403 )
					);
				}
			}

			if ( 'message' === $field && substr_count( strtolower( $value ), 'http' ) > 2 ) {
				return new \WP_Error(
					'hlm_suspicious_content',
					__( 'Submission rejected.', 'hlm-security' ),
					array( 'status' => 403 )
				);
			}
		}

		return true;
	}

	/**
	 * Resolve Turnstile site key from env or constant.
	 *
	 * @return string
	 */
	private static function turnstile_site_key() {
		if ( defined( 'HLM_TURNSTILE_SITE_KEY' ) && HLM_TURNSTILE_SITE_KEY ) {
			return HLM_TURNSTILE_SITE_KEY;
		}

		$env = getenv( 'HLM_TURNSTILE_SITE_KEY' );

		return $env ? sanitize_text_field( $env ) : '';
	}

	/**
	 * Resolve Turnstile secret key from env or constant.
	 *
	 * @return string
	 */
	private static function turnstile_secret_key() {
		if ( defined( 'HLM_TURNSTILE_SECRET_KEY' ) && HLM_TURNSTILE_SECRET_KEY ) {
			return HLM_TURNSTILE_SECRET_KEY;
		}

		$env = getenv( 'HLM_TURNSTILE_SECRET_KEY' );

		return $env ? sanitize_text_field( $env ) : '';
	}

	/**
	 * Seal payload with WordPress salts.
	 *
	 * @param string $payload Raw payload.
	 * @return string
	 */
	private static function seal( $payload ) {
		$key = wp_salt( 'auth' );
		$sig = hash_hmac( 'sha256', $payload, $key );

		return base64_encode( $payload ) . '.' . $sig;
	}

	/**
	 * Unseal and verify signed payload.
	 *
	 * @param string $token Sealed token.
	 * @return string|false
	 */
	private static function unseal( $token ) {
		$parts = explode( '.', $token, 2 );

		if ( 2 !== count( $parts ) ) {
			return false;
		}

		$payload = base64_decode( $parts[0], true );

		if ( false === $payload ) {
			return false;
		}

		$expected = hash_hmac( 'sha256', $payload, wp_salt( 'auth' ) );

		if ( ! hash_equals( $expected, $parts[1] ) ) {
			return false;
		}

		return $payload;
	}
}
