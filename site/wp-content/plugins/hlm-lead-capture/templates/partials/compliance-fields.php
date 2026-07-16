<?php
/**
 * TCPA compliance hidden fields (TrustedForm + Jornaya LeadiD).
 *
 * @package HLM_Lead_Capture
 *
 * @var string $form_id Unique form root id.
 */

defined( 'ABSPATH' ) || exit;
?>

<input type="hidden" name="trustedformcerturl" id="trustedformcerturl" value="">
<input type="hidden" name="universal_leadid" id="leadid_token" value="">
<input type="hidden" name="page_url" value="<?php echo esc_url( is_singular() ? get_permalink() : home_url( add_query_arg( array() ) ) ); ?>">
