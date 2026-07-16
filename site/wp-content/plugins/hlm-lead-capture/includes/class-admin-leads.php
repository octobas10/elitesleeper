<?php
/**
 * Admin UI for viewing stored leads.
 *
 * @package HLM_Lead_Capture
 */

defined( 'ABSPATH' ) || exit;

/**
 * Leads admin menu and screens.
 */
class HLM_Lead_Capture_Admin_Leads {

	/**
	 * Register hooks.
	 */
	public static function init() {
		add_action( 'admin_menu', array( __CLASS__, 'register_menu' ) );
		add_action( 'admin_post_hlm_delete_lead', array( __CLASS__, 'handle_delete' ) );
		add_action( 'admin_post_hlm_export_leads', array( __CLASS__, 'handle_export' ) );
	}

	/**
	 * Admin menu page.
	 */
	public static function register_menu() {
		$count = HLM_Lead_Storage::count();
		$badge = $count > 0 ? sprintf( ' <span class="awaiting-mod count-%d"><span class="pending-count">%d</span></span>', $count, $count ) : '';

		add_menu_page(
			__( 'Lead Submissions', 'hlm-lead-capture' ),
			__( 'Leads', 'hlm-lead-capture' ) . $badge,
			'manage_options',
			'hlm-leads',
			array( __CLASS__, 'render_list_page' ),
			'dashicons-id-alt',
			58
		);
	}

	/**
	 * URL to view a single lead.
	 *
	 * @param int $id Lead ID.
	 * @return string
	 */
	public static function view_url( $id ) {
		return HLM_Lead_Storage::admin_view_url( $id );
	}

	/**
	 * List or detail screen router.
	 */
	public static function render_list_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$action = sanitize_key( $_REQUEST['action'] ?? '' ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended

		if ( 'view' === $action ) {
			self::render_detail_page();
			return;
		}

		self::process_bulk_actions();

		$table = new HLM_Leads_List_Table();
		$table->prepare_items();

		include HLM_LC_PLUGIN_DIR . 'templates/admin/leads-list.php';
	}

	/**
	 * Single lead detail view.
	 */
	private static function render_detail_page() {
		$id   = (int) ( $_REQUEST['lead_id'] ?? 0 ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$lead = HLM_Lead_Storage::get( $id );

		if ( ! $lead ) {
			wp_die( esc_html__( 'Lead not found.', 'hlm-lead-capture' ) );
		}

		include HLM_LC_PLUGIN_DIR . 'templates/admin/lead-detail.php';
	}

	/**
	 * Process bulk delete from list table.
	 */
	private static function process_bulk_actions() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		if ( empty( $_REQUEST['_wpnonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['_wpnonce'] ) ), 'bulk-leads' ) ) {
			return;
		}

		$action = self::get_current_bulk_action();

		if ( 'delete' !== $action || empty( $_REQUEST['lead_ids'] ) || ! is_array( $_REQUEST['lead_ids'] ) ) {
			return;
		}

		foreach ( array_map( 'intval', $_REQUEST['lead_ids'] ) as $lead_id ) {
			HLM_Lead_Storage::delete( $lead_id );
		}
	}

	/**
	 * Resolve bulk action from request.
	 *
	 * @return string
	 */
	private static function get_current_bulk_action() {
		if ( ! empty( $_REQUEST['action'] ) && '-1' !== $_REQUEST['action'] ) {
			return sanitize_key( $_REQUEST['action'] );
		}

		if ( ! empty( $_REQUEST['action2'] ) && '-1' !== $_REQUEST['action2'] ) {
			return sanitize_key( $_REQUEST['action2'] );
		}

		return '';
	}

	/**
	 * Delete single lead.
	 */
	public static function handle_delete() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'Unauthorized', 'hlm-lead-capture' ) );
		}

		$id = (int) ( $_REQUEST['lead_id'] ?? 0 );
		check_admin_referer( 'hlm_delete_lead_' . $id );

		HLM_Lead_Storage::delete( $id );

		wp_safe_redirect( admin_url( 'admin.php?page=hlm-leads&deleted=1' ) );
		exit;
	}

	/**
	 * Export leads as CSV (honours current list filters).
	 */
	public static function handle_export() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'Unauthorized', 'hlm-lead-capture' ) );
		}

		check_admin_referer( 'hlm_export_leads' );

		HLM_Lead_Storage::stream_csv(
			array(
				'form_type' => sanitize_key( $_REQUEST['form_type'] ?? '' ), // phpcs:ignore WordPress.Security.NonceVerification.Recommended
				'search'    => sanitize_text_field( wp_unslash( $_REQUEST['s'] ?? '' ) ), // phpcs:ignore WordPress.Security.NonceVerification.Recommended
				'orderby'   => 'created_at',
				'order'     => 'DESC',
			)
		);
	}

	/**
	 * Export URL preserving current filters.
	 *
	 * @return string
	 */
	public static function export_url() {
		$args = array(
			'action'   => 'hlm_export_leads',
			'_wpnonce' => wp_create_nonce( 'hlm_export_leads' ),
		);

		if ( ! empty( $_REQUEST['s'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$args['s'] = sanitize_text_field( wp_unslash( $_REQUEST['s'] ) );
		}
		if ( ! empty( $_REQUEST['form_type'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$args['form_type'] = sanitize_key( $_REQUEST['form_type'] );
		}

		return add_query_arg( $args, admin_url( 'admin-post.php' ) );
	}
}
