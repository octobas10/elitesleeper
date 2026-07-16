<?php
/**
 * Admin list table for stored leads.
 *
 * @package HLM_Lead_Capture
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

/**
 * WP_List_Table for wp_hlm_leads.
 */
class HLM_Leads_List_Table extends WP_List_Table {

	/**
	 * Constructor.
	 */
	public function __construct() {
		parent::__construct(
			array(
				'singular' => 'lead',
				'plural'   => 'leads',
				'ajax'     => false,
			)
		);
	}

	/**
	 * Table columns.
	 *
	 * @return array
	 */
	public function get_columns() {
		return array(
			'cb'                  => '<input type="checkbox" />',
			'id'                  => __( 'ID', 'hlm-lead-capture' ),
			'created_at'          => __( 'Submitted', 'hlm-lead-capture' ),
			'form_type'           => __( 'Form', 'hlm-lead-capture' ),
			'name'                => __( 'Name', 'hlm-lead-capture' ),
			'email'               => __( 'Email', 'hlm-lead-capture' ),
			'phone'               => __( 'Phone', 'hlm-lead-capture' ),
			'campus'              => __( 'Campus', 'hlm-lead-capture' ),
			'program_of_interest' => __( 'Program', 'hlm-lead-capture' ),
			'elite_status'        => __( 'Elite API', 'hlm-lead-capture' ),
		);
	}

	/**
	 * Sortable columns.
	 *
	 * @return array
	 */
	public function get_sortable_columns() {
		return array(
			'id'         => array( 'id', false ),
			'created_at' => array( 'created_at', true ),
			'email'      => array( 'email', false ),
			'form_type'  => array( 'form_type', false ),
		);
	}

	/**
	 * Bulk actions.
	 *
	 * @return array
	 */
	public function get_bulk_actions() {
		return array(
			'delete' => __( 'Delete', 'hlm-lead-capture' ),
		);
	}

	/**
	 * Checkbox column.
	 *
	 * @param object $item Row.
	 * @return string
	 */
	public function column_cb( $item ) {
		return sprintf( '<input type="checkbox" name="lead_ids[]" value="%d" />', (int) $item->id );
	}

	/**
	 * Default column output.
	 *
	 * @param object $item Row.
	 * @param string $column_name Column.
	 * @return string
	 */
	public function column_default( $item, $column_name ) {
		switch ( $column_name ) {
			case 'created_at':
				return esc_html( $item->created_at );
			case 'form_type':
				return esc_html( ucfirst( $item->form_type ) );
			case 'email':
				return sprintf(
					'<a href="%s"><strong>%s</strong></a>',
					esc_url( HLM_Lead_Capture_Admin_Leads::view_url( $item->id ) ),
					esc_html( $item->email )
				);
			case 'phone':
				return esc_html( $item->phone );
			case 'campus':
				return esc_html( $item->campus );
			case 'program_of_interest':
				return esc_html( $item->program_of_interest );
			case 'elite_status':
				return $this->elite_badge( $item );
			default:
				return '';
		}
	}

	/**
	 * Name column with view link.
	 *
	 * @param object $item Row.
	 * @return string
	 */
	public function column_name( $item ) {
		$name = trim( $item->first_name . ' ' . $item->last_name );

		if ( 'university' === $item->form_type && $item->institution_name ) {
			$name = $item->institution_name;
		}

		return sprintf(
			'<a class="row-title" href="%s">%s</a>',
			esc_url( HLM_Lead_Capture_Admin_Leads::view_url( $item->id ) ),
			esc_html( $name ?: '—' )
		);
	}

	/**
	 * ID column with actions.
	 *
	 * @param object $item Row.
	 * @return string
	 */
	public function column_id( $item ) {
		$view_url = HLM_Lead_Capture_Admin_Leads::view_url( $item->id );
		$actions  = array(
			'view' => sprintf( '<a href="%s">%s</a>', esc_url( $view_url ), esc_html__( 'View', 'hlm-lead-capture' ) ),
		);

		return (int) $item->id . $this->row_actions( $actions );
	}

	/**
	 * Elite status badge HTML.
	 *
	 * @param object $item Row.
	 * @return string
	 */
	private function elite_badge( $item ) {
		$status = $item->elite_status ?: 'skipped';
		$labels = array(
			'sent'    => __( 'Sent', 'hlm-lead-capture' ),
			'failed'  => __( 'Failed', 'hlm-lead-capture' ),
			'skipped' => __( 'N/A', 'hlm-lead-capture' ),
		);

		$label = $labels[ $status ] ?? esc_html( $status );
		$class = 'hlm-lead-badge hlm-lead-badge--' . sanitize_html_class( $status );

		$title = $item->elite_note ? ' title="' . esc_attr( $item->elite_note ) . '"' : '';

		return '<span class="' . esc_attr( $class ) . '"' . $title . '>' . esc_html( $label ) . '</span>';
	}

	/**
	 * Load table data.
	 */
	public function prepare_items() {
		$per_page     = 20;
		$current_page = $this->get_pagenum();

		$result = HLM_Lead_Storage::query(
			array(
				'per_page'  => $per_page,
				'page'      => $current_page,
				'form_type' => sanitize_key( $_REQUEST['form_type'] ?? '' ), // phpcs:ignore WordPress.Security.NonceVerification.Recommended
				'search'    => sanitize_text_field( wp_unslash( $_REQUEST['s'] ?? '' ) ), // phpcs:ignore WordPress.Security.NonceVerification.Recommended
				'orderby'   => sanitize_key( $_REQUEST['orderby'] ?? 'created_at' ), // phpcs:ignore WordPress.Security.NonceVerification.Recommended
				'order'     => sanitize_text_field( wp_unslash( $_REQUEST['order'] ?? 'DESC' ) ), // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			)
		);

		$this->items = $result['items'];

		$columns  = $this->get_columns();
		$hidden   = array();
		$sortable = $this->get_sortable_columns();
		$this->_column_headers = array( $columns, $hidden, $sortable );

		$this->set_pagination_args(
			array(
				'total_items' => $result['total'],
				'per_page'    => $per_page,
				'total_pages' => ceil( $result['total'] / $per_page ),
			)
		);
	}
}
