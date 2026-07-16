<?php
/**
 * Optional hint text below a form field.
 *
 * @package HLM_Lead_Capture
 *
 * @var string $hint_id  Unique id for aria-describedby.
 * @var string $hint_text Hint copy.
 */

defined( 'ABSPATH' ) || exit;

if ( empty( $hint_text ) ) {
	return;
}
?>
<p class="hlm-field-hint" id="<?php echo esc_attr( $hint_id ); ?>"><?php echo esc_html( $hint_text ); ?></p>
