<?php
/**
 * Plugin settings storage and accessors.
 *
 * @package HLM_Lead_Capture
 */

defined( 'ABSPATH' ) || exit;

/**
 * Central settings for HLM Lead Capture.
 */
class HLM_Lead_Capture_Settings {

	const OPTION = 'hlm_lead_capture_settings';

	/**
	 * Default settings.
	 *
	 * @return array<string, mixed>
	 */
	public static function defaults() {
		return array(
			'elite_enabled'          => false,
			'elite_promo_code'       => '',
			'elite_lead_mode'        => '0',
			'elite_sub_id'           => '',
			'elite_fail_silent'      => false,
			'enable_trustedform'     => true,
			'enable_leadid'          => true,
			'tcpa_text_student'      => __( 'I agree to be contacted by phone, email, and text message regarding educational opportunities. I understand consent is not required to apply.', 'hlm-lead-capture' ),
			'tcpa_text_berkeley'     => __( 'I agree to be contacted by phone, email, and text message regarding Berkeley College opportunities. I understand consent is not required to apply.', 'hlm-lead-capture' ),
			'message_success'        => __( 'Thank you! Your inquiry was submitted successfully.', 'hlm-lead-capture' ),
			'message_error'          => __( 'Submission failed. Please check your details and try again.', 'hlm-lead-capture' ),
			'store_leads_locally'    => true,
			'notify_enabled'         => false,
			'notify_recipients'      => '',
			'notify_subject'         => '',
			'notify_include_rejected'=> false,
		);
	}

	/**
	 * All saved settings merged with defaults.
	 *
	 * @return array<string, mixed>
	 */
	public static function all() {
		$stored = get_option( self::OPTION, array() );

		if ( ! is_array( $stored ) ) {
			$stored = array();
		}

		return wp_parse_args( $stored, self::defaults() );
	}

	/**
	 * Get a single setting.
	 *
	 * @param string $key     Setting key.
	 * @param mixed  $default Optional default override.
	 * @return mixed
	 */
	public static function get( $key, $default = null ) {
		$settings = self::all();

		if ( array_key_exists( $key, $settings ) ) {
			return $settings[ $key ];
		}

		return null !== $default ? $default : ( self::defaults()[ $key ] ?? null );
	}

	/**
	 * Whether Elite API posting is active.
	 *
	 * @return bool
	 */
	public static function elite_is_enabled() {
		if ( ! self::get( 'elite_enabled', false ) ) {
			return false;
		}

		return (bool) self::elite_promo_code();
	}

	/**
	 * Promo code: wp-config constant > env > admin setting.
	 *
	 * @return string
	 */
	public static function elite_promo_code() {
		if ( defined( 'HLM_ELITE_PROMO_CODE' ) && HLM_ELITE_PROMO_CODE ) {
			return (string) HLM_ELITE_PROMO_CODE;
		}

		$env = getenv( 'HLM_ELITE_PROMO_CODE' );
		if ( $env ) {
			return sanitize_text_field( $env );
		}

		return sanitize_text_field( (string) self::get( 'elite_promo_code', '' ) );
	}

	/**
	 * Lead mode: 0 test, 1 live.
	 *
	 * @return string
	 */
	public static function elite_lead_mode() {
		if ( defined( 'HLM_ELITE_LEAD_MODE' ) ) {
			return (string) (int) HLM_ELITE_LEAD_MODE;
		}

		$env = getenv( 'HLM_ELITE_LEAD_MODE' );
		if ( false !== $env && '' !== $env ) {
			return (string) (int) $env;
		}

		return (string) (int) self::get( 'elite_lead_mode', '0' );
	}

	/**
	 * Default affiliate sub_id.
	 *
	 * @return string
	 */
	public static function elite_sub_id() {
		return sanitize_text_field( (string) self::get( 'elite_sub_id', '' ) );
	}

	/**
	 * When true, Elite errors are logged but submission still succeeds locally.
	 *
	 * @return bool
	 */
	public static function elite_fail_silent() {
		return (bool) self::get( 'elite_fail_silent', false );
	}

	/**
	 * TCPA disclosure text by form type.
	 *
	 * @param string $form_type student|berkeley.
	 * @return string
	 */
	public static function tcpa_text( $form_type ) {
		$key = 'berkeley' === $form_type ? 'tcpa_text_berkeley' : 'tcpa_text_student';
		$text = (string) self::get( $key, '' );

		return substr( trim( $text ), 0, 200 );
	}

	/**
	 * Whether compliance scripts should load.
	 *
	 * @param string $type trustedform|leadid.
	 * @return bool
	 */
	public static function compliance_enabled( $type ) {
		if ( 'trustedform' === $type ) {
			return (bool) self::get( 'enable_trustedform', true );
		}

		if ( 'leadid' === $type ) {
			return (bool) self::get( 'enable_leadid', true );
		}

		return false;
	}

	/**
	 * Whether local DB storage is enabled (alias for lead storage).
	 *
	 * @return bool
	 */
	public static function store_leads_locally() {
		$settings = self::all();
		if ( isset( $settings['store_leads_locally'] ) ) {
			return (bool) $settings['store_leads_locally'];
		}
		// Back-compat with older log_submissions setting.
		return (bool) ( $settings['log_submissions'] ?? true );
	}

	/**
	 * Human-readable Elite configuration source.
	 *
	 * @return string
	 */
	public static function elite_config_source() {
		if ( defined( 'HLM_ELITE_PROMO_CODE' ) && HLM_ELITE_PROMO_CODE ) {
			return 'wp-config.php';
		}

		if ( getenv( 'HLM_ELITE_PROMO_CODE' ) ) {
			return 'environment';
		}

		return 'admin';
	}

	/**
	 * Whether email notifications are enabled.
	 *
	 * @return bool
	 */
	public static function notify_enabled() {
		return (bool) self::get( 'notify_enabled', false );
	}

	/**
	 * Notification recipient email addresses.
	 *
	 * @return string[]
	 */
	public static function notify_recipients() {
		$raw = trim( (string) self::get( 'notify_recipients', '' ) );

		if ( ! $raw ) {
			$admin = get_option( 'admin_email' );
			return $admin ? array( sanitize_email( $admin ) ) : array();
		}

		$emails = array_map( 'trim', explode( ',', $raw ) );
		$emails = array_filter( array_map( 'sanitize_email', $emails ) );

		return array_values( $emails );
	}

	/**
	 * Notify when Elite validation rejects before save completes.
	 *
	 * @return bool
	 */
	public static function notify_include_rejected() {
		return (bool) self::get( 'notify_include_rejected', false );
	}

	/**
	 * Known SMTP plugins and their admin URLs.
	 *
	 * @return array<string, array<string, string>>
	 */
	public static function smtp_plugin_catalog() {
		return array(
			'fluent-smtp'  => array(
				'plugin_file'  => 'fluent-smtp/fluent-smtp.php',
				'label'        => 'FluentSMTP',
				'settings_url' => admin_url( 'admin.php?page=fluent-mail#/connections' ),
				'test_url'     => admin_url( 'admin.php?page=fluent-mail#/test' ),
				'install_url'  => admin_url( 'plugin-install.php?s=fluent-smtp&tab=search&type=term' ),
			),
			'wp-mail-smtp' => array(
				'plugin_file'  => 'wp-mail-smtp/wp_mail_smtp.php',
				'label'        => 'WP Mail SMTP',
				'settings_url' => admin_url( 'admin.php?page=wp-mail-smtp' ),
				'test_url'     => admin_url( 'admin.php?page=wp-mail-smtp-tools' ),
				'install_url'  => admin_url( 'plugin-install.php?s=wp-mail-smtp&tab=search&type=term' ),
			),
		);
	}

	/**
	 * SMTP plugins with install / activate / active status.
	 *
	 * @return array<int, array<string, string>>
	 */
	public static function smtp_plugins() {
		if ( ! function_exists( 'is_plugin_active' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		$plugins = array();

		foreach ( self::smtp_plugin_catalog() as $slug => $meta ) {
			$plugin_file = $meta['plugin_file'];
			$status      = 'missing';

			if ( file_exists( WP_PLUGIN_DIR . '/' . $plugin_file ) ) {
				$status = is_plugin_active( $plugin_file ) ? 'active' : 'inactive';
			}

			$activate_url = wp_nonce_url(
				admin_url( 'plugins.php?action=activate&plugin=' . rawurlencode( $plugin_file ) ),
				'activate-plugin_' . $plugin_file
			);

			$plugins[] = array_merge(
				$meta,
				array(
					'slug'         => $slug,
					'status'       => $status,
					'activate_url' => $activate_url,
					'plugins_url'  => admin_url( 'plugins.php' ),
				)
			);
		}

		return $plugins;
	}

	/**
	 * First active SMTP plugin, if any.
	 *
	 * @return array<string, string>|null
	 */
	public static function smtp_plugin() {
		foreach ( self::smtp_plugins() as $plugin ) {
			if ( 'active' === $plugin['status'] ) {
				return $plugin;
			}
		}

		return null;
	}

	/**
	 * From address for notification emails.
	 *
	 * @return string
	 */
	public static function mail_from_email() {
		$admin = sanitize_email( (string) get_option( 'admin_email' ) );

		return $admin ?: '';
	}

	/**
	 * From name for notification emails.
	 *
	 * @return string
	 */
	public static function mail_from_name() {
		return wp_specialchars_decode( get_bloginfo( 'name' ), ENT_QUOTES );
	}
}
