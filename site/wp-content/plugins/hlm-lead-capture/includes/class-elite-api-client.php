<?php
/**
 * Elite Biz Panel education lead API client.
 *
 * @package HLM_Lead_Capture
 */

defined( 'ABSPATH' ) || exit;

/**
 * Posts validated leads to https://elitebizpanel.com/edu/postprocess
 */
class HLM_Elite_API_Client {

	const ENDPOINT = 'https://elitebizpanel.com/edu/postprocess';

	/**
	 * Register hooks (extensibility only; primary post runs in form handler).
	 */
	public static function init() {
		// Reserved for logging or secondary integrations.
	}

	/**
	 * Whether Elite posting is configured.
	 *
	 * @return bool
	 */
	public static function is_enabled() {
		return HLM_Lead_Capture_Settings::elite_is_enabled();
	}

	/**
	 * Forward student/berkeley leads to Elite when enabled.
	 *
	 * @param string $form_type Form type slug.
	 * @param array  $data      Sanitized submission.
	 */
	public static function forward_lead( $form_type, array $data ) {
		if ( ! self::is_enabled() || ! in_array( $form_type, array( 'student', 'berkeley' ), true ) ) {
			return;
		}

		$payload = self::build_payload( $form_type, $data );

		if ( is_wp_error( $payload ) ) {
			self::log( 'payload_error', $payload->get_error_message(), $data );
			return;
		}

		$result = self::post( $payload );

		if ( is_wp_error( $result ) ) {
			self::log( 'post_error', $result->get_error_message(), $payload );
		}
	}

	/**
	 * Build Elite API field map from form submission.
	 *
	 * @param string $form_type Form type.
	 * @param array  $data      Submission data.
	 * @return array|\WP_Error
	 */
	public static function build_payload( $form_type, array $data ) {
		$category = 'berkeley' === $form_type ? HLM_Institution_Data::berkeley_category() : ( $data['category'] ?? '' );
		$campus   = $data['campus'] ?? '';
		$program  = $data['program_of_interest'] ?? '';

		if ( $category && $campus && ! HLM_Institution_Data::is_valid_campus( $category, $campus ) ) {
			return new WP_Error( 'hlm_invalid_campus', __( 'Invalid campus selection.', 'hlm-lead-capture' ) );
		}

		if ( $category && $campus && $program && ! HLM_Institution_Data::is_valid_program( $category, $campus, $program ) ) {
			return new WP_Error( 'hlm_invalid_program', __( 'Invalid program selection.', 'hlm-lead-capture' ) );
		}

		$phone  = self::digits_only( $data['phone'] ?? '' );
		$mobile = self::digits_only( $data['mobile'] ?? '' );

		if ( ! $mobile && $phone ) {
			$mobile = $phone;
		}

		$state = strtoupper( substr( sanitize_text_field( $data['state'] ?? '' ), 0, 2 ) );

		$submitted_at = $data['submitted_at'] ?? '';
		$timestamp    = $submitted_at ? gmdate( 'Y-m-d H:i:s', strtotime( $submitted_at ) ) : gmdate( 'Y-m-d H:i:s' );

		$tcpa_text = $data['tcpa_text'] ?? '';
		if ( ! $tcpa_text && ! empty( $data['tcpa_consent'] ) ) {
			$tcpa_text = __( 'I agree to be contacted by phone, email, and text message regarding educational opportunities.', 'hlm-lead-capture' );
		}
		$tcpa_text = substr( $tcpa_text, 0, 200 );

		$payload = array(
			'lead_mode'           => self::lead_mode(),
			'promo_code'          => self::promo_code(),
			'sub_id'              => self::sub_id( $form_type, $data ),
			'datetime_stamp'      => $timestamp,
			'first_name'          => $data['first_name'] ?? '',
			'last_name'           => $data['last_name'] ?? '',
			'email'               => $data['email'] ?? '',
			'phone'               => $phone,
			'mobile'              => $mobile,
			'address'             => $data['address'] ?? '',
			'city'                => $data['city'] ?? '',
			'state'               => $state,
			'zip'                 => $data['zip'] ?? '',
			'education_level'     => $data['education_level'] ?? '',
			'desired_degree'      => $data['desired_degree'] ?? '',
			'program_of_interest' => $program,
			'campus'              => $campus,
			'grad_year'           => $data['grad_year'] ?? '',
			'ipaddress'           => self::client_ip(),
			'url'                 => self::lead_url( $data ),
			'trustedformcerturl'  => esc_url_raw( $data['trustedformcerturl'] ?? '' ),
			'universal_leadid'    => sanitize_text_field( $data['universal_leadid'] ?? '' ),
			'tcpa_text'           => $tcpa_text,
		);

		if ( ! empty( $data['gender'] ) ) {
			$payload['gender'] = strtoupper( substr( $data['gender'], 0, 1 ) );
		}

		if ( ! empty( $data['dob'] ) ) {
			$payload['dob'] = $data['dob'];
		}

		if ( isset( $data['military'] ) && '' !== $data['military'] ) {
			$payload['military'] = $data['military'] ? '1' : '0';
		}

		return apply_filters( 'hlm_elite_api_payload', $payload, $form_type, $data );
	}

	/**
	 * POST urlencoded payload to Elite.
	 *
	 * @param array $payload Field map.
	 * @return true|\WP_Error
	 */
	public static function post( array $payload ) {
		$response = wp_remote_post(
			self::ENDPOINT,
			array(
				'timeout' => 20,
				'headers' => array(
					'Content-Type' => 'application/x-www-form-urlencoded; charset=utf-8',
					'Accept'       => 'text/html',
				),
				'body'    => $payload,
			)
		);

		if ( is_wp_error( $response ) ) {
			return $response;
		}

		$body   = wp_remote_retrieve_body( $response );
		$status = wp_remote_retrieve_response_code( $response );

		if ( $status < 200 || $status >= 300 ) {
			return new WP_Error(
				'hlm_elite_http_error',
				sprintf(
					/* translators: %d: HTTP status code */
					__( 'Lead partner returned HTTP %d.', 'hlm-lead-capture' ),
					$status
				),
				array( 'body' => $body )
			);
		}

		if ( false !== stripos( $body, '<Response>ACCEPTED</Response>' ) ) {
			return true;
		}

		$reason = self::parse_rejection( $body );

		return new WP_Error(
			'hlm_elite_rejected',
			$reason ?: __( 'Lead was not accepted by the partner.', 'hlm-lead-capture' ),
			array( 'body' => $body )
		);
	}

	/**
	 * Extract rejection reason from XML response.
	 *
	 * @param string $body Response body.
	 * @return string
	 */
	private static function parse_rejection( $body ) {
		if ( preg_match( '/<Reason>([^<]+)<\/Reason>/i', $body, $matches ) ) {
			return trim( $matches[1] );
		}

		if ( preg_match( '/<Error>([^<]+)<\/Error>/i', $body, $matches ) ) {
			return trim( $matches[1] );
		}

		return '';
	}

	/**
	 * Promo code from constant or env.
	 *
	 * @return string
	 */
	private static function promo_code() {
		return HLM_Lead_Capture_Settings::elite_promo_code();
	}

	/**
	 * Lead mode: 0 test, 1 live.
	 *
	 * @return string
	 */
	private static function lead_mode() {
		return HLM_Lead_Capture_Settings::elite_lead_mode();
	}

	/**
	 * Affiliate sub_id for tracking.
	 *
	 * @param string $form_type Form type.
	 * @param array  $data      Submission.
	 * @return string
	 */
	private static function sub_id( $form_type, array $data ) {
		$custom = sanitize_text_field( $data['sub_id'] ?? '' );
		if ( $custom ) {
			return $custom;
		}

		$default = HLM_Lead_Capture_Settings::elite_sub_id();
		if ( $default ) {
			return $default;
		}

		return sanitize_key( $form_type ) . '-' . sanitize_key( $data['source'] ?? 'web' );
	}

	/**
	 * Lead source URL.
	 *
	 * @param array $data Submission.
	 * @return string
	 */
	private static function lead_url( array $data ) {
		if ( ! empty( $data['page_url'] ) ) {
			return esc_url_raw( $data['page_url'] );
		}

		return home_url( '/' );
	}

	/**
	 * Visitor IP for Elite ipaddress field.
	 *
	 * @return string
	 */
	private static function client_ip() {
		if ( class_exists( '\HLM_Security\Plugin' ) ) {
			return \HLM_Security\Plugin::client_ip();
		}

		return sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ?? '' ) );
	}

	/**
	 * Strip non-digits from phone fields.
	 *
	 * @param string $value Raw phone.
	 * @return string
	 */
	private static function digits_only( $value ) {
		return substr( preg_replace( '/\D+/', '', (string) $value ), 0, 15 );
	}

	/**
	 * Log Elite API issues when WP_DEBUG_LOG is enabled.
	 *
	 * @param string $code    Error code.
	 * @param string $message Message.
	 * @param mixed  $context Extra context.
	 */
	private static function log( $code, $message, $context = null ) {
		if ( ! defined( 'WP_DEBUG_LOG' ) || ! WP_DEBUG_LOG ) {
			return;
		}

		error_log(
			sprintf(
				'[HLM Elite API] %s: %s %s',
				$code,
				$message,
				wp_json_encode( $context )
			)
		);
	}
}
