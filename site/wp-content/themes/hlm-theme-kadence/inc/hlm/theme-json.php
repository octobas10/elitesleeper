<?php
/**
 * HLM theme.json integration.
 *
 * Ensures WordPress global styles are active and documents the token pipeline.
 *
 * @package HLM_Kadence
 */

defined( 'ABSPATH' ) || exit;

/**
 * Enable Kadence theme.json mode so the block editor reads theme.json settings.
 *
 * HLM theme.json lives at the theme root; this flag keeps Kadence editor paths aligned.
 */
function hlm_kadence_enable_theme_json_mode() {
	if ( get_option( 'hlm_theme_json_v1' ) ) {
		return;
	}

	set_theme_mod( 'theme_json_mode', true );
	update_option( 'hlm_theme_json_v1', 1, false );
}
add_action( 'after_setup_theme', 'hlm_kadence_enable_theme_json_mode', 25 );

/**
 * Merge HLM settings into the theme.json data layer (defensive merge).
 *
 * @param WP_Theme_JSON_Data $theme_json Theme JSON data object.
 * @return WP_Theme_JSON_Data
 */
function hlm_kadence_filter_theme_json( $theme_json ) {
	$new_data = array(
		'version'  => 3,
		'settings' => array(
			'color' => array(
				'defaultPalette' => false,
			),
		),
	);

	return $theme_json->update_with( $new_data );
}
add_filter( 'wp_theme_json_data_theme', 'hlm_kadence_filter_theme_json' );
