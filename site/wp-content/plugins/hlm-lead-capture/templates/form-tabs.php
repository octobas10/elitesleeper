<?php
/**
 * Two-tab lead capture form shell.
 *
 * @package HLM_Lead_Capture
 */

defined( 'ABSPATH' ) || exit;

$form_id = 'hlm-lead-form-' . wp_unique_id();
?>

<div class="hlm-lead-capture" id="<?php echo esc_attr( $form_id ); ?>">
	<div class="hlm-form-tabs" role="tablist" aria-label="<?php esc_attr_e( 'Lead capture form type', 'hlm-lead-capture' ); ?>">
		<button type="button" class="hlm-form-tab active" role="tab" aria-selected="true" aria-controls="<?php echo esc_attr( $form_id ); ?>-student" data-tab="student" title="<?php esc_attr_e( 'I am a Student', 'hlm-lead-capture' ); ?>">
			<span class="hlm-form-tab-label"><?php esc_html_e( 'Student', 'hlm-lead-capture' ); ?></span>
		</button>
		<button type="button" class="hlm-form-tab" role="tab" aria-selected="false" aria-controls="<?php echo esc_attr( $form_id ); ?>-university" data-tab="university" title="<?php esc_attr_e( 'I am a University or School', 'hlm-lead-capture' ); ?>">
			<span class="hlm-form-tab-label"><?php esc_html_e( 'University', 'hlm-lead-capture' ); ?></span>
		</button>
	</div>

	<div class="hlm-form-tabs-select-wrap">
		<label class="hlm-form-tabs-select-label" for="<?php echo esc_attr( $form_id ); ?>-type"><?php esc_html_e( 'I am a', 'hlm-lead-capture' ); ?></label>
		<select id="<?php echo esc_attr( $form_id ); ?>-type" class="hlm-form-tabs-select" aria-label="<?php esc_attr_e( 'Lead capture form type', 'hlm-lead-capture' ); ?>">
			<option value="student" selected><?php esc_html_e( 'Student', 'hlm-lead-capture' ); ?></option>
			<option value="university"><?php esc_html_e( 'University', 'hlm-lead-capture' ); ?></option>
		</select>
	</div>

	<div class="hlm-hp-field" aria-hidden="true">
		<label for="<?php echo esc_attr( $form_id ); ?>-hp"><?php esc_html_e( 'Leave blank', 'hlm-lead-capture' ); ?></label>
		<input type="text" id="<?php echo esc_attr( $form_id ); ?>-hp" name="hlm_hp_url" tabindex="-1" autocomplete="off">
	</div>

	<div class="hlm-turnstile-wrap" hidden></div>

	<?php
	include HLM_LC_PLUGIN_DIR . 'templates/partials/compliance-fields.php';
	include HLM_LC_PLUGIN_DIR . 'templates/form-student.php';
	include HLM_LC_PLUGIN_DIR . 'templates/form-university.php';
	?>
</div>
