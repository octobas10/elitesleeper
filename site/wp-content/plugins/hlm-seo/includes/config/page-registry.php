<?php
/**
 * Default page registry - override via `hlm_seo_page_registry` filter.
 *
 * @package HLM_SEO
 */

defined( 'ABSPATH' ) || exit;

/**
 * Default SEO configuration keyed by page slug.
 *
 * @return array<string, array<string, mixed>>
 */
function hlm_seo_page_registry(): array {
	$defaults = array();

	/**
	 * Filter the per-page SEO registry.
	 *
	 * Themes or site-specific plugins should populate this array keyed by page slug.
	 *
	 * @param array<string, array<string, mixed>> $defaults Page SEO config.
	 */
	return apply_filters( 'hlm_seo_page_registry', $defaults );
}

/**
 * Organization data for schema - override via `hlm_seo_organization_data` filter.
 *
 * @return array<string, mixed>
 */
function hlm_seo_organization_data(): array {
	$logo = function_exists( 'get_site_icon_url' ) ? get_site_icon_url( 512 ) : '';

	$defaults = array(
		'name'        => get_bloginfo( 'name' ),
		'legal_name'  => get_bloginfo( 'name' ),
		'url'         => home_url( '/' ),
		'logo'        => $logo ?: '',
		'description' => get_bloginfo( 'description' ),
		'email'       => '',
		'telephone'   => '',
		'address'     => array(
			'street'  => '',
			'city'    => '',
			'region'  => '',
			'postal'  => '',
			'country' => '',
		),
		'same_as'     => array(),
		'area_served' => '',
		'knows_about' => array(),
	);

	/**
	 * Filter organization schema and meta data.
	 *
	 * @param array<string, mixed> $defaults Organization data.
	 */
	return apply_filters( 'hlm_seo_organization_data', $defaults );
}
