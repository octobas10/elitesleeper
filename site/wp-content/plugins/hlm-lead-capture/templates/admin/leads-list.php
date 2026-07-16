<?php
/**
 * Admin leads list page.
 *
 * @package HLM_Lead_Capture
 *
 * @var HLM_Leads_List_Table $table
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="wrap hlm-lc-admin">
	<h1 class="wp-heading-inline"><?php esc_html_e( 'Lead Submissions', 'hlm-lead-capture' ); ?></h1>
	<a href="<?php echo esc_url( HLM_Lead_Capture_Admin_Leads::export_url() ); ?>" class="page-title-action"><?php esc_html_e( 'Export CSV', 'hlm-lead-capture' ); ?></a>
	<hr class="wp-header-end">

	<?php if ( isset( $_GET['deleted'] ) ) : // phpcs:ignore WordPress.Security.NonceVerification.Recommended ?>
		<div class="notice notice-success is-dismissible"><p><?php esc_html_e( 'Lead deleted.', 'hlm-lead-capture' ); ?></p></div>
	<?php endif; ?>

	<?php if ( ! HLM_Lead_Storage::is_enabled() ) : ?>
		<div class="notice notice-warning"><p>
			<?php
			printf(
				/* translators: %s: settings URL */
				esc_html__( 'Local storage is disabled. Enable it in %s.', 'hlm-lead-capture' ),
				'<a href="' . esc_url( admin_url( 'options-general.php?page=hlm-lead-capture' ) ) . '">' . esc_html__( 'Lead Capture Settings', 'hlm-lead-capture' ) . '</a>'
			);
			?>
		</p></div>
	<?php endif; ?>

	<form method="get">
		<input type="hidden" name="page" value="hlm-leads">
		<p class="search-box">
			<label class="screen-reader-text" for="hlm-lead-search"><?php esc_html_e( 'Search leads', 'hlm-lead-capture' ); ?></label>
			<input type="search" id="hlm-lead-search" name="s" value="<?php echo esc_attr( sanitize_text_field( wp_unslash( $_REQUEST['s'] ?? '' ) ) ); ?>">
			<select name="form_type">
				<option value=""><?php esc_html_e( 'All forms', 'hlm-lead-capture' ); ?></option>
				<option value="student" <?php selected( sanitize_key( $_REQUEST['form_type'] ?? '' ), 'student' ); ?>><?php esc_html_e( 'Student', 'hlm-lead-capture' ); ?></option>
				<option value="berkeley" <?php selected( sanitize_key( $_REQUEST['form_type'] ?? '' ), 'berkeley' ); ?>><?php esc_html_e( 'Berkeley', 'hlm-lead-capture' ); ?></option>
				<option value="university" <?php selected( sanitize_key( $_REQUEST['form_type'] ?? '' ), 'university' ); ?>><?php esc_html_e( 'University', 'hlm-lead-capture' ); ?></option>
			</select>
			<?php submit_button( __( 'Filter', 'hlm-lead-capture' ), 'secondary', '', false ); ?>
		</p>
	</form>

	<form method="post">
		<input type="hidden" name="page" value="hlm-leads">
		<?php
		if ( ! empty( $_REQUEST['s'] ) ) {
			echo '<input type="hidden" name="s" value="' . esc_attr( sanitize_text_field( wp_unslash( $_REQUEST['s'] ) ) ) . '">';
		}
		if ( ! empty( $_REQUEST['form_type'] ) ) {
			echo '<input type="hidden" name="form_type" value="' . esc_attr( sanitize_key( $_REQUEST['form_type'] ) ) . '">';
		}
		$table->display();
		?>
	</form>

	<p class="description">
		<?php
		printf(
			/* translators: %s: table name */
			esc_html__( 'Stored in database table %s.', 'hlm-lead-capture' ),
			'<code>' . esc_html( HLM_Lead_Storage::table_name() ) . '</code>'
		);
		?>
	</p>
</div>
