<?php
/**
 * Plugin Name: HLM Lead Capture
 * Description: Two-tab lead capture forms (Student, University) with Elite Biz Panel API integration.
 * Version:     1.7.0
 * Author:      Higher Learning Marketers
 * Text Domain: hlm-lead-capture
 *
 * @package HLM_Lead_Capture
 */

defined( 'ABSPATH' ) || exit;

define( 'HLM_LC_VERSION', '1.7.0' );
define( 'HLM_LC_PLUGIN_FILE', __FILE__ );
define( 'HLM_LC_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'HLM_LC_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

require_once HLM_LC_PLUGIN_DIR . 'includes/class-settings.php';
require_once HLM_LC_PLUGIN_DIR . 'includes/class-institution-data.php';
require_once HLM_LC_PLUGIN_DIR . 'includes/form-field-options.php';
require_once HLM_LC_PLUGIN_DIR . 'includes/class-lead-storage.php';
require_once HLM_LC_PLUGIN_DIR . 'includes/class-lead-notifications.php';
require_once HLM_LC_PLUGIN_DIR . 'includes/class-elite-api-client.php';
require_once HLM_LC_PLUGIN_DIR . 'includes/class-abuse-guard.php';
require_once HLM_LC_PLUGIN_DIR . 'includes/class-form-handler.php';

if ( is_admin() ) {
	require_once HLM_LC_PLUGIN_DIR . 'includes/class-admin-leads-list-table.php';
	require_once HLM_LC_PLUGIN_DIR . 'includes/class-admin-leads.php';
	require_once HLM_LC_PLUGIN_DIR . 'includes/class-admin-settings.php';
}

register_activation_hook( HLM_LC_PLUGIN_FILE, array( 'HLM_Lead_Storage', 'install' ) );

/**
 * Main plugin bootstrap.
 */
final class HLM_Lead_Capture {

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
		add_action( 'rest_api_init', array( HLM_Form_Handler::class, 'register_routes' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'register_assets' ) );
		add_shortcode( 'hlm_lead_form', array( $this, 'render_shortcode' ) );

		HLM_Elite_API_Client::init();
		HLM_Lead_Notifications::init();

		add_action( 'init', array( 'HLM_Lead_Storage', 'maybe_install' ) );

		if ( is_admin() ) {
			HLM_Lead_Capture_Admin::init();
			HLM_Lead_Capture_Admin_Leads::init();
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_assets' ) );
		}
	}

	/**
	 * Admin styles for settings screen.
	 *
	 * @param string $hook_suffix Current admin page hook.
	 */
	public function enqueue_admin_assets( $hook_suffix ) {
		if ( ! in_array( $hook_suffix, array( 'settings_page_hlm-lead-capture', 'toplevel_page_hlm-leads' ), true ) ) {
			return;
		}

		wp_enqueue_style(
			'hlm-lead-capture-admin',
			HLM_LC_PLUGIN_URL . 'assets/admin-settings.css',
			array(),
			HLM_LC_VERSION
		);

		if ( 'settings_page_hlm-lead-capture' === $hook_suffix ) {
			wp_enqueue_script(
				'hlm-lead-capture-admin',
				HLM_LC_PLUGIN_URL . 'assets/admin-settings.js',
				array(),
				HLM_LC_VERSION,
				true
			);
		}
	}

	/**
	 * Register front-end assets (enqueued when shortcode renders).
	 */
	public function register_assets() {
		wp_register_style(
			'hlm-lead-form',
			HLM_LC_PLUGIN_URL . 'assets/hlm-lead-form.css',
			array( 'hlm-blocks-theme', 'hlm-tokens' ),
			HLM_LC_VERSION
		);

		wp_register_script(
			'hlm-lead-form',
			HLM_LC_PLUGIN_URL . 'assets/hlm-lead-form.js',
			array(),
			HLM_LC_VERSION,
			true
		);
	}

	/**
	 * Enqueue assets once per request.
	 */
	private function enqueue_assets() {
		wp_enqueue_style( 'hlm-lead-form' );

		$this->enqueue_compliance_scripts();

		wp_enqueue_script( 'hlm-lead-form' );

		$localize = array(
			'restUrl'       => rest_url( 'hlm/v1/submit' ),
			'nonce'         => wp_create_nonce( 'wp_rest' ),
			'campusData'    => HLM_Institution_Data::get_campus_data(),
			'programData'   => HLM_Institution_Data::get_program_data(),
			'institutions'  => HLM_Institution_Data::get_institutions(),
			'pageUrl'       => $this->current_page_url(),
			'searchMinItems'=> 8,
			'compliance'    => array(
				'trustedform' => HLM_Lead_Capture_Settings::compliance_enabled( 'trustedform' ),
				'leadid'      => HLM_Lead_Capture_Settings::compliance_enabled( 'leadid' ),
			),
			'messages'      => array(
				'submitting'       => __( 'Submitting...', 'hlm-lead-capture' ),
				'success'          => (string) HLM_Lead_Capture_Settings::get( 'message_success' ),
				'error'            => (string) HLM_Lead_Capture_Settings::get( 'message_error' ),
				'stickyCta'        => __( 'Start Your Inquiry', 'hlm-lead-capture' ),
				'fieldError'       => __( 'Please check the highlighted field.', 'hlm-lead-capture' ),
				'required'         => __( 'Please enter %s.', 'hlm-lead-capture' ),
				'selectRequired'   => __( 'Please select %s.', 'hlm-lead-capture' ),
				'invalidEmail'     => __( 'Please enter a valid email address.', 'hlm-lead-capture' ),
				'invalidPhone'     => __( 'Please enter a 10-digit U.S. phone number.', 'hlm-lead-capture' ),
				'invalidZip'       => __( 'Enter a valid ZIP (12345) or Canadian postal code (A1A 1A1).', 'hlm-lead-capture' ),
				'invalidName'      => __( 'Please enter at least 2 characters.', 'hlm-lead-capture' ),
				'invalidUrl'       => __( 'Please enter a full website URL starting with https://', 'hlm-lead-capture' ),
				'consentRequired'  => __( 'Please check the consent box to continue.', 'hlm-lead-capture' ),
				'searchPrograms'   => __( 'Search or select a program...', 'hlm-lead-capture' ),
				'selectProgram'    => __( 'Please select a program from the list.', 'hlm-lead-capture' ),
				'noPrograms'       => __( 'No matching programs', 'hlm-lead-capture' ),
				'programsAvailable'=> __( 'programs available', 'hlm-lead-capture' ),
			),
		);

		$localize = apply_filters( 'hlm_lead_form_localize', $localize );

		wp_localize_script(
			'hlm-lead-form',
			'hlmLeadForm',
			$localize
		);

		if ( ! empty( $localize['turnstileSiteKey'] ) ) {
			wp_enqueue_script( 'cloudflare-turnstile' );
		}
	}

	/**
	 * TrustedForm + Jornaya LeadiD for TCPA compliance fields.
	 *
	 * Loaded independently of the main form script so a blocked third-party
	 * script cannot prevent the form UI from initializing.
	 */
	private function enqueue_compliance_scripts() {
		if ( HLM_Lead_Capture_Settings::compliance_enabled( 'trustedform' ) ) {
			wp_enqueue_script(
				'hlm-trustedform',
				'https://api.trustedform.com/trustedform.js?provide_tag=true&field=trustedformcerturl',
				array(),
				null,
				true
			);
		}

		if ( HLM_Lead_Capture_Settings::compliance_enabled( 'leadid' ) ) {
			wp_enqueue_script(
				'hlm-leadid',
				'https://create.leadid.com/leadid.js',
				array(),
				null,
				true
			);
		}
	}

	/**
	 * Current page URL for lead source tracking.
	 *
	 * @return string
	 */
	private function current_page_url() {
		if ( is_singular() ) {
			return get_permalink();
		}

		return home_url( add_query_arg( array() ) );
	}

	/**
	 * Shortcode callback: [hlm_lead_form]
	 *
	 * @return string
	 */
	public function render_shortcode() {
		$this->enqueue_assets();

		ob_start();
		include HLM_LC_PLUGIN_DIR . 'templates/form-tabs.php';
		return ob_get_clean();
	}
}

HLM_Lead_Capture::instance();
