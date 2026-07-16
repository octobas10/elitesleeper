<?php
/**
 * Structured service detail body (catalog-driven).
 *
 * @package HLM_Kadence
 *
 * @var array $args Template arguments.
 */

defined( 'ABSPATH' ) || exit;

$entry = $args['entry'] ?? ( function_exists( 'hlm_service_catalog_entry' ) ? hlm_service_catalog_entry() : null );

if ( empty( $entry ) || ! is_array( $entry ) ) {
	return;
}

if ( function_exists( 'hlm_services_render_detail_body' ) ) {
	// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	echo hlm_services_render_detail_body( $entry );
}
