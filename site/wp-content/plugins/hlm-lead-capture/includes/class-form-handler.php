<?php
/**
 * REST API form submission handler.
 *
 * @package HLM_Lead_Capture
 */

defined( 'ABSPATH' ) || exit;

/**
 * Handles POST /wp-json/hlm/v1/submit
 */
class HLM_Form_Handler {

	const ALLOWED_TYPES = array( 'student', 'university', 'berkeley' );

	/**
	 * Register REST routes.
	 */
	public static function register_routes() {
		register_rest_route(
			'hlm/v1',
			'/submit',
			array(
				'methods'             => WP_REST_Server::CREATABLE,
				'callback'            => array( __CLASS__, 'handle_submit' ),
				'permission_callback' => array( __CLASS__, 'verify_request' ),
			)
		);
	}

	/**
	 * Verify REST nonce header.
	 *
	 * @param WP_REST_Request $request Request object.
	 * @return bool
	 */
	public static function verify_request( WP_REST_Request $request ) {
		$nonce = $request->get_header( 'X-WP-Nonce' );

		return (bool) wp_verify_nonce( $nonce, 'wp_rest' );
	}

	/**
	 * Handle form submission.
	 *
	 * @param WP_REST_Request $request Request object.
	 * @return WP_REST_Response|WP_Error
	 */
	public static function handle_submit( WP_REST_Request $request ) {
		$data      = $request->get_json_params();
		$form_type = sanitize_key( $data['form_type'] ?? '' );

		if ( ! in_array( $form_type, self::ALLOWED_TYPES, true ) ) {
			return new WP_Error(
				'hlm_invalid_form_type',
				__( 'Invalid form type.', 'hlm-lead-capture' ),
				array( 'status' => 400 )
			);
		}

		$abuse = HLM_Abuse_Guard::check( is_array( $data ) ? $data : array() );
		if ( is_wp_error( $abuse ) ) {
			return $abuse;
		}

		$security = apply_filters( 'hlm_lead_capture_security_check', true, $request, $form_type, $data );

		if ( is_wp_error( $security ) ) {
			return $security;
		}

		unset( $data['form_type'] );
		$data = self::filter_allowed_fields( $form_type, $data );

		$validation = self::validate( $form_type, $data );

		if ( is_wp_error( $validation ) ) {
			return $validation;
		}

		$sanitized = self::sanitize( $data );
		$elite_status = 'skipped';
		$elite_note   = '';

		if ( in_array( $form_type, array( 'student', 'berkeley' ), true ) && HLM_Elite_API_Client::is_enabled() ) {
			$elite_payload = HLM_Elite_API_Client::build_payload( $form_type, $sanitized );

			if ( is_wp_error( $elite_payload ) ) {
				$lead_id = HLM_Lead_Storage::insert( $form_type, $sanitized, 'rejected', 'failed', $elite_payload->get_error_message() );
				if ( $lead_id ) {
					do_action( 'hlm_lead_capture_saved', $form_type, $sanitized, $lead_id );
				}
				return $elite_payload;
			}

			$elite_result = HLM_Elite_API_Client::post( $elite_payload );

			if ( is_wp_error( $elite_result ) ) {
				$elite_status = 'failed';
				$elite_note   = $elite_result->get_error_message();

				if ( ! HLM_Lead_Capture_Settings::elite_fail_silent() ) {
					$lead_id = HLM_Lead_Storage::insert( $form_type, $sanitized, 'saved', $elite_status, $elite_note );
					if ( $lead_id ) {
						do_action( 'hlm_lead_capture_saved', $form_type, $sanitized, $lead_id );
					}
					return new WP_Error(
						$elite_result->get_error_code(),
						$elite_result->get_error_message(),
						array( 'status' => 502 )
					);
				}
			} else {
				$elite_status = 'sent';
			}
		}

		$lead_id = HLM_Lead_Storage::insert( $form_type, $sanitized, 'saved', $elite_status, $elite_note );

		/**
		 * Fires after a lead form passes validation and sanitization.
		 *
		 * @param string   $form_type Form type slug.
		 * @param array    $sanitized Sanitized submission payload.
		 * @param int|null $lead_id   Local database row ID when stored.
		 */
		do_action( 'hlm_lead_capture_saved', $form_type, $sanitized, $lead_id );

		return new WP_REST_Response(
			array(
				'success' => true,
				'message' => __( 'Lead submitted successfully.', 'hlm-lead-capture' ),
			),
			201
		);
	}

	/**
	 * Validate required fields per form type.
	 *
	 * @param string $form_type Form type.
	 * @param array  $data      Raw submission data.
	 * @return true|WP_Error
	 */
	private static function validate( $form_type, array $data ) {
		$required = array(
			'student'    => array(
				'first_name', 'last_name', 'email', 'phone', 'address', 'city', 'state', 'zip',
				'category', 'campus', 'program_of_interest', 'education_level', 'desired_degree', 'grad_year',
			),
			'university' => array( 'institution_name', 'first_name', 'last_name', 'email', 'phone' ),
			'berkeley'   => array(
				'first_name', 'last_name', 'email', 'phone', 'address', 'city', 'state', 'zip',
				'campus', 'program_of_interest', 'education_level', 'desired_degree', 'grad_year',
			),
		);

		$missing = array();

		foreach ( $required[ $form_type ] as $field ) {
			if ( empty( $data[ $field ] ) ) {
				$missing[] = $field;
			}
		}

		if ( in_array( $form_type, array( 'student', 'berkeley' ), true ) && empty( $data['tcpa_consent'] ) ) {
			$missing[] = 'tcpa_consent';
		}

		if ( ! empty( $data['email'] ) && ! is_email( $data['email'] ) ) {
			return new WP_Error(
				'hlm_invalid_email',
				__( 'Please enter a valid email address.', 'hlm-lead-capture' ),
				array( 'status' => 400 )
			);
		}

		$phone = preg_replace( '/\D+/', '', (string) ( $data['phone'] ?? '' ) );
		if ( $phone && strlen( $phone ) !== 10 ) {
			return new WP_Error(
				'hlm_invalid_phone',
				__( 'Please enter a valid 10-digit phone number.', 'hlm-lead-capture' ),
				array( 'status' => 400 )
			);
		}

		if ( ! empty( $data['state'] ) && ! preg_match( '/^[A-Za-z]{2}$/', (string) $data['state'] ) ) {
			return new WP_Error(
				'hlm_invalid_state',
				__( 'Please select a valid state or province.', 'hlm-lead-capture' ),
				array( 'status' => 400 )
			);
		}

		if ( ! empty( $data['zip'] ) && ! self::is_valid_zip( (string) $data['zip'] ) ) {
			return new WP_Error(
				'hlm_invalid_zip',
				__( 'Please enter a valid ZIP or postal code.', 'hlm-lead-capture' ),
				array( 'status' => 400 )
			);
		}

		foreach ( array( 'first_name', 'last_name', 'institution_name' ) as $name_field ) {
			if ( empty( $data[ $name_field ] ) ) {
				continue;
			}
			if ( ! self::is_valid_name( (string) $data[ $name_field ] ) ) {
				return new WP_Error(
					'hlm_invalid_name',
					__( 'Please enter a valid name (at least 2 characters).', 'hlm-lead-capture' ),
					array( 'status' => 400 )
				);
			}
		}

		if ( ! empty( $data['website'] ) && ! filter_var( $data['website'], FILTER_VALIDATE_URL ) ) {
			return new WP_Error(
				'hlm_invalid_website',
				__( 'Please enter a valid website URL.', 'hlm-lead-capture' ),
				array( 'status' => 400 )
			);
		}

		if ( in_array( $form_type, array( 'student', 'berkeley' ), true ) ) {
			$category = 'berkeley' === $form_type ? HLM_Institution_Data::berkeley_category() : ( $data['category'] ?? '' );
			$campus   = $data['campus'] ?? '';
			$program  = $data['program_of_interest'] ?? '';

			if ( $category && $campus && ! HLM_Institution_Data::is_valid_campus( $category, $campus ) ) {
				return new WP_Error(
					'hlm_invalid_campus',
					__( 'Please select a valid campus.', 'hlm-lead-capture' ),
					array( 'status' => 400 )
				);
			}

			if ( $category && $campus && $program && ! HLM_Institution_Data::is_valid_program( $category, $campus, $program ) ) {
				return new WP_Error(
					'hlm_invalid_program',
					__( 'Please select a valid program.', 'hlm-lead-capture' ),
					array( 'status' => 400 )
				);
			}
		}

		if ( ! empty( $missing ) ) {
			return new WP_Error(
				'hlm_missing_fields',
				__( 'Please fill in all required fields.', 'hlm-lead-capture' ),
				array(
					'status' => 400,
					'fields' => $missing,
				)
			);
		}

		return true;
	}

	/**
	 * Validate a person or institution name.
	 *
	 * @param string $name Name value.
	 * @return bool
	 */
	private static function is_valid_name( $name ) {
		$name = trim( (string) $name );
		return (bool) preg_match( "/^[\p{L}\s'.-]{2,}$/u", $name );
	}

	/**
	 * Validate U.S. ZIP or Canadian postal code.
	 *
	 * @param string $zip ZIP/postal value.
	 * @return bool
	 */
	private static function is_valid_zip( $zip ) {
		$zip = trim( (string) $zip );
		return (bool) preg_match( '/^\d{5}(-\d{4})?$/', $zip )
			|| (bool) preg_match( '/^[A-Za-z]\d[A-Za-z][ -]?\d[A-Za-z]\d$/', $zip );
	}

	/**
	 * Sanitize submission payload.
	 *
	 * @param array $data Raw data.
	 * @return array
	 */
	private static function sanitize( array $data ) {
		$sanitized = array();

		foreach ( $data as $key => $value ) {
			$key = sanitize_key( $key );

			if ( is_array( $value ) ) {
				$sanitized[ $key ] = array_map( 'sanitize_text_field', $value );
				continue;
			}

			if ( 'email' === $key ) {
				$sanitized[ $key ] = sanitize_email( $value );
				continue;
			}

			if ( in_array( $key, array( 'website', 'page_url', 'trustedformcerturl' ), true ) ) {
				$sanitized[ $key ] = esc_url_raw( $value );
				continue;
			}

			if ( 'message' === $key || 'tcpa_text' === $key ) {
				$sanitized[ $key ] = sanitize_textarea_field( $value );
				continue;
			}

			$sanitized[ $key ] = sanitize_text_field( (string) $value );
		}

		if ( ! empty( $sanitized['phone'] ) ) {
			$sanitized['phone'] = substr( preg_replace( '/\D+/', '', $sanitized['phone'] ), 0, 10 );
		}

		if ( ! empty( $sanitized['mobile'] ) ) {
			$sanitized['mobile'] = substr( preg_replace( '/\D+/', '', $sanitized['mobile'] ), 0, 15 );
		}

		if ( ! empty( $sanitized['state'] ) ) {
			$sanitized['state'] = strtoupper( substr( $sanitized['state'], 0, 2 ) );
		}

		return $sanitized;
	}

	/**
	 * Strip unknown fields before validation.
	 *
	 * @param string $form_type Form type.
	 * @param array  $data      Raw submission data.
	 * @return array
	 */
	private static function filter_allowed_fields( $form_type, array $data ) {
		$allowed = apply_filters( 'hlm_lead_capture_allowed_fields', array(), $form_type );

		if ( empty( $allowed ) ) {
			return $data;
		}

		return array_intersect_key( $data, array_flip( $allowed ) );
	}
}
