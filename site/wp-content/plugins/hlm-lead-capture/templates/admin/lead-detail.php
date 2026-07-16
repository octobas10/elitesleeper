<?php
/**
 * Single lead detail in admin.
 *
 * @package HLM_Lead_Capture
 *
 * @var object $lead
 */

defined( 'ABSPATH' ) || exit;

$payload = is_array( $lead->payload_data ?? null ) ? $lead->payload_data : array();
$name    = trim( $lead->first_name . ' ' . $lead->last_name );
?>
<div class="wrap hlm-lc-admin">
	<h1><?php esc_html_e( 'Lead Details', 'hlm-lead-capture' ); ?></h1>

	<p>
		<a href="<?php echo esc_url( admin_url( 'admin.php?page=hlm-leads' ) ); ?>">&larr; <?php esc_html_e( 'Back to all leads', 'hlm-lead-capture' ); ?></a>
	</p>

	<div class="hlm-lc-admin__status" style="margin-bottom:20px;">
		<div class="hlm-lc-admin__badge hlm-lc-admin__badge--muted">
			<strong><?php esc_html_e( 'Submission', 'hlm-lead-capture' ); ?></strong>
			<span>#<?php echo (int) $lead->id; ?> · <?php echo esc_html( $lead->created_at ); ?> · <?php echo esc_html( ucfirst( $lead->form_type ) ); ?></span>
		</div>
		<div class="hlm-lc-admin__badge <?php echo 'sent' === $lead->elite_status ? 'is-active' : 'is-inactive'; ?>">
			<strong><?php esc_html_e( 'Elite API', 'hlm-lead-capture' ); ?></strong>
			<span><?php echo esc_html( $lead->elite_status ?: 'skipped' ); ?><?php echo $lead->elite_note ? ' — ' . esc_html( $lead->elite_note ) : ''; ?></span>
		</div>
	</div>

	<table class="widefat striped">
		<tbody>
			<tr><th><?php esc_html_e( 'Name', 'hlm-lead-capture' ); ?></th><td><?php echo esc_html( $name ?: '—' ); ?></td></tr>
			<tr><th><?php esc_html_e( 'Email', 'hlm-lead-capture' ); ?></th><td><a href="mailto:<?php echo esc_attr( $lead->email ); ?>"><?php echo esc_html( $lead->email ); ?></a></td></tr>
			<tr><th><?php esc_html_e( 'Phone', 'hlm-lead-capture' ); ?></th><td><?php echo esc_html( $lead->phone ); ?></td></tr>
			<?php if ( $lead->address ?? $payload['address'] ?? '' ) : ?>
				<tr><th><?php esc_html_e( 'Address', 'hlm-lead-capture' ); ?></th><td><?php echo esc_html( $payload['address'] ?? '' ); ?></td></tr>
			<?php endif; ?>
			<tr><th><?php esc_html_e( 'City', 'hlm-lead-capture' ); ?></th><td><?php echo esc_html( $lead->city ); ?></td></tr>
			<tr><th><?php esc_html_e( 'State', 'hlm-lead-capture' ); ?></th><td><?php echo esc_html( $lead->state ); ?></td></tr>
			<tr><th><?php esc_html_e( 'ZIP', 'hlm-lead-capture' ); ?></th><td><?php echo esc_html( $lead->zip ); ?></td></tr>
			<?php if ( $lead->institution_name ) : ?>
				<tr><th><?php esc_html_e( 'Institution', 'hlm-lead-capture' ); ?></th><td><?php echo esc_html( $lead->institution_name ); ?></td></tr>
			<?php endif; ?>
			<?php if ( $lead->category ) : ?>
				<tr><th><?php esc_html_e( 'School category', 'hlm-lead-capture' ); ?></th><td><?php echo esc_html( $lead->category ); ?></td></tr>
			<?php endif; ?>
			<?php if ( $lead->campus ) : ?>
				<tr><th><?php esc_html_e( 'Campus', 'hlm-lead-capture' ); ?></th><td><?php echo esc_html( $lead->campus ); ?></td></tr>
			<?php endif; ?>
			<?php if ( $lead->program_of_interest ) : ?>
				<tr><th><?php esc_html_e( 'Program', 'hlm-lead-capture' ); ?></th><td><code><?php echo esc_html( $lead->program_of_interest ); ?></code></td></tr>
			<?php endif; ?>
			<?php if ( ! empty( $payload['education_level'] ) ) : ?>
				<tr><th><?php esc_html_e( 'Education level', 'hlm-lead-capture' ); ?></th><td><?php echo esc_html( $payload['education_level'] ); ?></td></tr>
			<?php endif; ?>
			<?php if ( ! empty( $payload['desired_degree'] ) ) : ?>
				<tr><th><?php esc_html_e( 'Desired degree', 'hlm-lead-capture' ); ?></th><td><?php echo esc_html( $payload['desired_degree'] ); ?></td></tr>
			<?php endif; ?>
			<?php if ( ! empty( $payload['grad_year'] ) ) : ?>
				<tr><th><?php esc_html_e( 'Grad year', 'hlm-lead-capture' ); ?></th><td><?php echo esc_html( $payload['grad_year'] ); ?></td></tr>
			<?php endif; ?>
			<?php if ( ! empty( $payload['page_url'] ) ) : ?>
				<tr><th><?php esc_html_e( 'Source page', 'hlm-lead-capture' ); ?></th><td><a href="<?php echo esc_url( $payload['page_url'] ); ?>" target="_blank" rel="noopener"><?php echo esc_html( $payload['page_url'] ); ?></a></td></tr>
			<?php endif; ?>
		</tbody>
	</table>

	<h2><?php esc_html_e( 'Full payload', 'hlm-lead-capture' ); ?></h2>
	<pre class="hlm-lc-admin__payload"><?php echo esc_html( wp_json_encode( $payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES ) ); ?></pre>

	<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" style="margin-top:16px;">
		<?php wp_nonce_field( 'hlm_delete_lead_' . $lead->id ); ?>
		<input type="hidden" name="action" value="hlm_delete_lead">
		<input type="hidden" name="lead_id" value="<?php echo (int) $lead->id; ?>">
		<?php submit_button( __( 'Delete Lead', 'hlm-lead-capture' ), 'delete', 'submit', false ); ?>
	</form>
</div>
