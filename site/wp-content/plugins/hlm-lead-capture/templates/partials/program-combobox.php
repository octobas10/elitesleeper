<?php
/**
 * Searchable program-of-interest combobox markup.
 *
 * @package HLM_Lead_Capture
 *
 * @var string $form_id   Form instance id prefix.
 * @var string $suffix    Unique suffix (student, berk, etc.).
 * @var bool   $required  Whether selection is required.
 */

defined( 'ABSPATH' ) || exit;

$required = ! empty( $required );
$input_id = $form_id . '-' . $suffix . '-program';
?>

<div class="hlm-combobox hlm-program-combobox" data-required="<?php echo $required ? '1' : '0'; ?>">
	<label for="<?php echo esc_attr( $input_id ); ?>-search">
		<?php esc_html_e( 'Program of interest', 'hlm-lead-capture' ); ?>
		<?php if ( $required ) : ?>
			<span class="hlm-form-required" aria-hidden="true">*</span>
		<?php endif; ?>
	</label>
	<input type="hidden" name="program_of_interest" id="<?php echo esc_attr( $input_id ); ?>" value="" <?php echo $required ? 'required' : ''; ?>>
	<div class="hlm-combobox-control">
		<input
			type="text"
			class="hlm-combobox-input"
			id="<?php echo esc_attr( $input_id ); ?>-search"
			placeholder="<?php esc_attr_e( 'Select school and campus first...', 'hlm-lead-capture' ); ?>"
			autocomplete="off"
			role="combobox"
			aria-autocomplete="list"
			aria-expanded="false"
			aria-controls="<?php echo esc_attr( $input_id ); ?>-list"
			disabled
		>
		<span class="hlm-combobox-count" hidden></span>
	</div>
	<ul class="hlm-combobox-list" id="<?php echo esc_attr( $input_id ); ?>-list" role="listbox" hidden></ul>
</div>
