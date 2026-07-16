<?php
/**
 * Persistent lead storage in wp_hlm_leads.
 *
 * @package HLM_Lead_Capture
 */

defined( 'ABSPATH' ) || exit;

/**
 * Saves and queries lead submissions.
 */
class HLM_Lead_Storage {

	const DB_VERSION = '1.0';

	/**
	 * Full table name including prefix.
	 *
	 * @return string
	 */
	public static function table_name() {
		global $wpdb;
		return $wpdb->prefix . 'hlm_leads';
	}

	/**
	 * Create or upgrade the leads table.
	 */
	public static function install() {
		global $wpdb;

		$table   = self::table_name();
		$charset = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE {$table} (
			id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
			form_type varchar(20) NOT NULL DEFAULT '',
			status varchar(30) NOT NULL DEFAULT 'saved',
			elite_status varchar(30) NOT NULL DEFAULT '',
			elite_note text NOT NULL,
			first_name varchar(100) NOT NULL DEFAULT '',
			last_name varchar(100) NOT NULL DEFAULT '',
			email varchar(191) NOT NULL DEFAULT '',
			phone varchar(20) NOT NULL DEFAULT '',
			city varchar(100) NOT NULL DEFAULT '',
			state varchar(10) NOT NULL DEFAULT '',
			zip varchar(20) NOT NULL DEFAULT '',
			category varchar(50) NOT NULL DEFAULT '',
			campus varchar(100) NOT NULL DEFAULT '',
			program_of_interest varchar(100) NOT NULL DEFAULT '',
			institution_name varchar(200) NOT NULL DEFAULT '',
			payload longtext NOT NULL,
			created_at datetime NOT NULL,
			PRIMARY KEY  (id),
			KEY form_type (form_type),
			KEY email (email),
			KEY created_at (created_at)
		) {$charset};";

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		dbDelta( $sql );

		update_option( 'hlm_lead_capture_db_version', self::DB_VERSION );
	}

	/**
	 * Ensure table exists (for sites that updated without reactivation).
	 */
	public static function maybe_install() {
		if ( get_option( 'hlm_lead_capture_db_version' ) !== self::DB_VERSION ) {
			self::install();
		}
	}

	/**
	 * Whether local DB storage is enabled.
	 *
	 * @return bool
	 */
	public static function is_enabled() {
		return HLM_Lead_Capture_Settings::store_leads_locally();
	}

	/**
	 * Admin URL to view a stored lead (safe on front-end REST requests).
	 *
	 * @param int $id Lead ID.
	 * @return string
	 */
	public static function admin_view_url( $id ) {
		return add_query_arg(
			array(
				'page'    => 'hlm-leads',
				'action'  => 'view',
				'lead_id' => (int) $id,
			),
			admin_url( 'admin.php' )
		);
	}

	/**
	 * Insert a lead row.
	 *
	 * @param string $form_type    Form slug.
	 * @param array  $data         Sanitized submission.
	 * @param string $status       Local status (saved, rejected).
	 * @param string $elite_status Elite post result (sent, failed, skipped).
	 * @param string $elite_note   Optional Elite message.
	 * @return int|false Insert ID or false.
	 */
	public static function insert( $form_type, array $data, $status = 'saved', $elite_status = '', $elite_note = '' ) {
		if ( ! self::is_enabled() ) {
			return false;
		}

		self::maybe_install();

		global $wpdb;

		$inserted = $wpdb->insert(
			self::table_name(),
			array(
				'form_type'           => sanitize_key( $form_type ),
				'status'              => sanitize_key( $status ),
				'elite_status'        => sanitize_key( $elite_status ),
				'elite_note'          => sanitize_textarea_field( $elite_note ),
				'first_name'          => sanitize_text_field( $data['first_name'] ?? '' ),
				'last_name'           => sanitize_text_field( $data['last_name'] ?? '' ),
				'email'               => sanitize_email( $data['email'] ?? '' ),
				'phone'               => sanitize_text_field( $data['phone'] ?? '' ),
				'city'                => sanitize_text_field( $data['city'] ?? '' ),
				'state'               => sanitize_text_field( $data['state'] ?? '' ),
				'zip'                 => sanitize_text_field( $data['zip'] ?? '' ),
				'category'            => sanitize_text_field( $data['category'] ?? '' ),
				'campus'              => sanitize_text_field( $data['campus'] ?? '' ),
				'program_of_interest' => sanitize_text_field( $data['program_of_interest'] ?? '' ),
				'institution_name'    => sanitize_text_field( $data['institution_name'] ?? '' ),
				'payload'             => wp_json_encode( $data ),
				'created_at'          => current_time( 'mysql' ),
			),
			array(
				'%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s',
				'%s', '%s', '%s', '%s', '%s', '%s',
			)
		);

		return $inserted ? (int) $wpdb->insert_id : false;
	}

	/**
	 * Get a single lead by ID.
	 *
	 * @param int $id Lead ID.
	 * @return object|null
	 */
	public static function get( $id ) {
		self::maybe_install();

		global $wpdb;

		$row = $wpdb->get_row(
			$wpdb->prepare(
				'SELECT * FROM ' . self::table_name() . ' WHERE id = %d',
				$id
			)
		);

		if ( $row && ! empty( $row->payload ) ) {
			$row->payload_data = json_decode( $row->payload, true );
		}

		return $row;
	}

	/**
	 * Query leads for admin list table.
	 *
	 * @param array $args Query args.
	 * @return array{items: object[], total: int}
	 */
	public static function query( array $args = array() ) {
		self::maybe_install();

		global $wpdb;

		$defaults = array(
			'per_page'  => 20,
			'page'      => 1,
			'form_type' => '',
			'search'    => '',
			'orderby'   => 'created_at',
			'order'     => 'DESC',
		);

		$args    = wp_parse_args( $args, $defaults );
		$table   = self::table_name();
		$where   = array( '1=1' );
		$values  = array();

		if ( $args['form_type'] ) {
			$where[]  = 'form_type = %s';
			$values[] = sanitize_key( $args['form_type'] );
		}

		if ( $args['search'] ) {
			$like     = '%' . $wpdb->esc_like( $args['search'] ) . '%';
			$where[]  = '(email LIKE %s OR first_name LIKE %s OR last_name LIKE %s OR phone LIKE %s)';
			$values[] = $like;
			$values[] = $like;
			$values[] = $like;
			$values[] = $like;
		}

		$allowed_orderby = array( 'created_at', 'email', 'form_type', 'id' );
		$orderby         = in_array( $args['orderby'], $allowed_orderby, true ) ? $args['orderby'] : 'created_at';
		$order           = 'ASC' === strtoupper( $args['order'] ) ? 'ASC' : 'DESC';

		$where_sql = implode( ' AND ', $where );
		$offset    = max( 0, ( (int) $args['page'] - 1 ) * (int) $args['per_page'] );

		if ( $values ) {
			$total = (int) $wpdb->get_var(
				$wpdb->prepare(
					"SELECT COUNT(*) FROM {$table} WHERE {$where_sql}",
					$values
				)
			);

			$query_values   = $values;
			$query_values[] = (int) $args['per_page'];
			$query_values[] = $offset;

			$items = $wpdb->get_results(
				$wpdb->prepare(
					"SELECT * FROM {$table} WHERE {$where_sql} ORDER BY {$orderby} {$order} LIMIT %d OFFSET %d",
					$query_values
				)
			);
		} else {
			$total = (int) $wpdb->get_var( "SELECT COUNT(*) FROM {$table} WHERE {$where_sql}" );

			$items = $wpdb->get_results(
				$wpdb->prepare(
					"SELECT * FROM {$table} WHERE {$where_sql} ORDER BY {$orderby} {$order} LIMIT %d OFFSET %d",
					(int) $args['per_page'],
					$offset
				)
			);
		}

		return array(
			'items' => $items ?: array(),
			'total' => $total,
		);
	}

	/**
	 * Delete a lead by ID.
	 *
	 * @param int $id Lead ID.
	 * @return bool
	 */
	public static function delete( $id ) {
		global $wpdb;

		return (bool) $wpdb->delete(
			self::table_name(),
			array( 'id' => (int) $id ),
			array( '%d' )
		);
	}

	/**
	 * Total lead count.
	 *
	 * @return int
	 */
	public static function count() {
		self::maybe_install();

		global $wpdb;

		return (int) $wpdb->get_var( 'SELECT COUNT(*) FROM ' . self::table_name() );
	}

	/**
	 * Fetch leads for CSV export (respects filters, capped at 10k rows).
	 *
	 * @param array $args Same filters as query().
	 * @return object[]
	 */
	public static function export_rows( array $args = array() ) {
		$args['per_page'] = 10000;
		$args['page']     = 1;

		$result = self::query( $args );

		return $result['items'];
	}

	/**
	 * Stream a CSV download to the browser.
	 *
	 * @param array $args Query filters.
	 */
	public static function stream_csv( array $args = array() ) {
		$rows = self::export_rows( $args );

		$filename = 'hlm-leads-' . gmdate( 'Y-m-d-His' ) . '.csv';

		header( 'Content-Type: text/csv; charset=utf-8' );
		header( 'Content-Disposition: attachment; filename=' . $filename );
		header( 'Pragma: no-cache' );
		header( 'Expires: 0' );

		$out = fopen( 'php://output', 'w' );

		if ( false === $out ) {
			return;
		}

		// UTF-8 BOM for Excel compatibility.
		fprintf( $out, chr( 0xEF ) . chr( 0xBB ) . chr( 0xBF ) );

		fputcsv(
			$out,
			array(
				'id',
				'created_at',
				'form_type',
				'status',
				'elite_status',
				'elite_note',
				'first_name',
				'last_name',
				'email',
				'phone',
				'city',
				'state',
				'zip',
				'category',
				'campus',
				'program_of_interest',
				'institution_name',
			)
		);

		foreach ( $rows as $row ) {
			fputcsv(
				$out,
				array(
					$row->id,
					$row->created_at,
					$row->form_type,
					$row->status,
					$row->elite_status,
					$row->elite_note,
					$row->first_name,
					$row->last_name,
					$row->email,
					$row->phone,
					$row->city,
					$row->state,
					$row->zip,
					$row->category,
					$row->campus,
					$row->program_of_interest,
					$row->institution_name,
				)
			);
		}

		fclose( $out );
		exit;
	}
}
