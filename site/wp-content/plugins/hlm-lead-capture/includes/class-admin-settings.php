<?php
/**
 * WordPress admin settings page.
 *
 * @package HLM_Lead_Capture
 */

defined( 'ABSPATH' ) || exit;

/**
 * Registers and renders plugin settings under Settings → Lead Capture.
 */
class HLM_Lead_Capture_Admin {

	/**
	 * Boot admin hooks.
	 */
	public static function init() {
		add_action( 'admin_menu', array( __CLASS__, 'register_menu' ) );
		add_action( 'admin_init', array( __CLASS__, 'register_settings' ) );
		add_action( 'admin_post_hlm_test_lead_email', array( __CLASS__, 'handle_test_email' ) );
		add_filter( 'plugin_action_links_' . plugin_basename( HLM_LC_PLUGIN_FILE ), array( __CLASS__, 'plugin_links' ) );
	}

	/**
	 * Settings submenu.
	 */
	public static function register_menu() {
		add_options_page(
			__( 'Lead Capture Settings', 'hlm-lead-capture' ),
			__( 'Lead Capture', 'hlm-lead-capture' ),
			'manage_options',
			'hlm-lead-capture',
			array( __CLASS__, 'render_page' )
		);
	}

	/**
	 * Register option and sanitization.
	 */
	public static function register_settings() {
		register_setting(
			'hlm_lead_capture',
			HLM_Lead_Capture_Settings::OPTION,
			array(
				'type'              => 'array',
				'sanitize_callback' => array( __CLASS__, 'sanitize' ),
				'default'           => HLM_Lead_Capture_Settings::defaults(),
			)
		);
	}

	/**
	 * Sanitize settings form input.
	 *
	 * @param array $input Raw POST values.
	 * @return array
	 */
	public static function sanitize( $input ) {
		if ( ! is_array( $input ) ) {
			return HLM_Lead_Capture_Settings::defaults();
		}

		$defaults = HLM_Lead_Capture_Settings::defaults();
		$clean    = array();

		$clean['elite_enabled']      = ! empty( $input['elite_enabled'] );
		if ( defined( 'HLM_ELITE_PROMO_CODE' ) && HLM_ELITE_PROMO_CODE ) {
			$clean['elite_promo_code'] = HLM_Lead_Capture_Settings::all()['elite_promo_code'];
		} else {
			$clean['elite_promo_code'] = sanitize_text_field( $input['elite_promo_code'] ?? '' );
		}
		if ( defined( 'HLM_ELITE_LEAD_MODE' ) ) {
			$clean['elite_lead_mode'] = HLM_Lead_Capture_Settings::all()['elite_lead_mode'];
		} else {
			$clean['elite_lead_mode'] = ! empty( $input['elite_lead_mode'] ) ? '1' : '0';
		}
		$clean['elite_sub_id']       = sanitize_text_field( $input['elite_sub_id'] ?? '' );
		$clean['elite_fail_silent']  = ! empty( $input['elite_fail_silent'] );
		$clean['enable_trustedform'] = ! empty( $input['enable_trustedform'] );
		$clean['enable_leadid']      = ! empty( $input['enable_leadid'] );
		$clean['store_leads_locally'] = ! empty( $input['store_leads_locally'] );
		$clean['notify_enabled']          = ! empty( $input['notify_enabled'] );
		$clean['notify_include_rejected'] = ! empty( $input['notify_include_rejected'] );
		$clean['notify_recipients']       = sanitize_text_field( $input['notify_recipients'] ?? '' );
		$clean['notify_subject']          = sanitize_text_field( $input['notify_subject'] ?? '' );

		$clean['tcpa_text_student']  = substr( sanitize_textarea_field( $input['tcpa_text_student'] ?? '' ), 0, 200 );
		$clean['tcpa_text_berkeley'] = substr( sanitize_textarea_field( $input['tcpa_text_berkeley'] ?? '' ), 0, 200 );
		$clean['message_success']    = sanitize_text_field( $input['message_success'] ?? '' );
		$clean['message_error']      = sanitize_text_field( $input['message_error'] ?? '' );

		if ( '' === $clean['tcpa_text_student'] ) {
			$clean['tcpa_text_student'] = $defaults['tcpa_text_student'];
		}
		if ( '' === $clean['message_success'] ) {
			$clean['message_success'] = $defaults['message_success'];
		}
		if ( '' === $clean['message_error'] ) {
			$clean['message_error'] = $defaults['message_error'];
		}
		if ( '' === $clean['tcpa_text_berkeley'] ) {
			$clean['tcpa_text_berkeley'] = $defaults['tcpa_text_berkeley'];
		}

		return wp_parse_args( $clean, $defaults );
	}

	/**
	 * Send a test notification email from settings.
	 */
	public static function handle_test_email() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'Unauthorized', 'hlm-lead-capture' ) );
		}

		check_admin_referer( 'hlm_test_lead_email' );

		$result = HLM_Lead_Notifications::send_test();
		$args   = array( 'page' => 'hlm-lead-capture', 'tab' => 'email' );

		if ( is_wp_error( $result ) ) {
			$args['hlm_email_test'] = 'failed';
			$args['hlm_email_error'] = rawurlencode( $result->get_error_message() );
		} else {
			$args['hlm_email_test'] = 'sent';
		}

		wp_safe_redirect( add_query_arg( $args, admin_url( 'options-general.php' ) ) );
		exit;
	}

	/**
	 * Add Settings link on plugins list.
	 *
	 * @param string[] $links Plugin action links.
	 * @return string[]
	 */
	public static function plugin_links( $links ) {
		$links[] = sprintf(
			'<a href="%s">%s</a>',
			esc_url( admin_url( 'admin.php?page=hlm-leads' ) ),
			esc_html__( 'Leads', 'hlm-lead-capture' )
		);
		$links[] = sprintf(
			'<a href="%s">%s</a>',
			esc_url( admin_url( 'options-general.php?page=hlm-lead-capture' ) ),
			esc_html__( 'Settings', 'hlm-lead-capture' )
		);
		return $links;
	}

	/**
	 * Render settings page.
	 */
	public static function render_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$settings = HLM_Lead_Capture_Settings::all();
		$catalog  = self::catalog_stats();
		$source   = HLM_Lead_Capture_Settings::elite_config_source();
		$promo_locked = defined( 'HLM_ELITE_PROMO_CODE' ) && HLM_ELITE_PROMO_CODE;
		$lead_count   = HLM_Lead_Storage::count();

		include HLM_LC_PLUGIN_DIR . 'templates/admin/settings-page.php';
	}

	/**
	 * Summarize institution catalog for the admin UI.
	 *
	 * @return array{campuses: int, programs: int, institutions: int}
	 */
	private static function catalog_stats() {
		$campus_data  = HLM_Institution_Data::get_campus_data();
		$program_data = HLM_Institution_Data::get_program_data();
		$campuses     = 0;
		$programs     = 0;

		foreach ( $campus_data as $inst_campuses ) {
			$campuses += count( $inst_campuses );
		}

		foreach ( $program_data as $inst_programs ) {
			foreach ( $inst_programs as $campus_programs ) {
				$programs += count( $campus_programs );
			}
		}

		return array(
			'institutions' => count( HLM_Institution_Data::get_institutions() ),
			'campuses'     => $campuses,
			'programs'     => $programs,
		);
	}
}
