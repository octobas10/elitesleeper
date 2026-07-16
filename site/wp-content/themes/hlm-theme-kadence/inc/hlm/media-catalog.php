<?php
/**
 * Curated stock photography for HLM marketing pages.
 *
 * Use {{HLM_MEDIA:key}} tokens in pattern files; resolved in hlm_get_pattern_markup().
 *
 * @package HLM_Kadence
 */

defined( 'ABSPATH' ) || exit;

/**
 * Stock image catalog keyed by usage.
 *
 * @return array<string, string>
 */
function hlm_media_catalog() {
	return array(
		'hero_home_poster'  => 'https://images.unsplash.com/photo-1523240795612-9a054b0db644?w=1920&h=1080&fit=crop',
		'hero_campus'       => 'https://images.unsplash.com/photo-1523240795612-9a054b0db644?w=1920&h=1080&fit=crop',
		'page_about'        => 'https://images.unsplash.com/photo-1522071820081-009f0129c71c?w=1920&h=800&fit=crop',
		'page_services'     => 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=1920&h=800&fit=crop',
		'page_affiliations' => 'https://images.unsplash.com/photo-1523050854058-8df87810e8fa?w=1920&h=800&fit=crop',
		'page_contact'      => 'https://images.unsplash.com/photo-1497366216548-37526070297c?w=1920&h=800&fit=crop',
		'partner_story'     => 'https://images.unsplash.com/photo-1600880292203-757bb62b4baf?w=800&h=600&fit=crop',
		'partner_ygo'       => 'https://images.unsplash.com/photo-1529156069898-49953e39b3ac?w=800&h=600&fit=crop',
		'uni_berkeley'      => 'https://images.unsplash.com/photo-1562774053-701939374585?w=800&h=450&fit=crop',
		'uni_ecpi'          => 'https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?w=800&h=450&fit=crop',
		'uni_south'         => 'https://images.unsplash.com/photo-1576091160399-112ba8d25d1d?w=800&h=450&fit=crop',
		'team_victor'       => 'https://images.unsplash.com/photo-1560250097-0b93528c311a?w=600&h=400&fit=crop&crop=face',
		'team_robert'       => 'https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?w=600&h=400&fit=crop&crop=face',
		'testimonial_1'     => 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=96&h=96&fit=crop&crop=face',
		'testimonial_2'     => 'https://images.unsplash.com/photo-1580489944761-15a19d654956?w=96&h=96&fit=crop&crop=face',
		'testimonial_3'     => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=96&h=96&fit=crop&crop=face',
	);
}

/**
 * Resolve a catalog key to a URL.
 *
 * @param string $key Catalog key.
 * @return string
 */
function hlm_media_url( $key ) {
	$catalog = hlm_media_catalog();

	return $catalog[ $key ] ?? '';
}

/**
 * Replace {{HLM_MEDIA:key}} tokens in pattern markup.
 *
 * @param string $markup Block pattern markup.
 * @return string
 */
function hlm_replace_media_tokens( $markup ) {
	if ( false === strpos( $markup, '{{HLM_MEDIA:' ) ) {
		return $markup;
	}

	return preg_replace_callback(
		'/\{\{HLM_MEDIA:([a-z0-9_]+)\}\}/',
		static function ( $matches ) {
			return esc_url( hlm_media_url( $matches[1] ) );
		},
		$markup
	);
}
