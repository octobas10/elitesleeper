<?php
/**
 * Global HLM site header.
 *
 * @package HLM_Kadence
 */

defined( 'ABSPATH' ) || exit;

/**
 * Header CTA label and URL.
 *
 * @return array{label: string, url: string}
 */
function hlm_kadence_header_cta() {
	return array(
		'label' => __( 'Get Started', 'hlm-kadence' ),
		'url'   => home_url( '/contact-us/#form' ),
	);
}

/**
 * Whether the header should start transparent over the hero.
 *
 * @return bool
 */
function hlm_kadence_header_is_transparent() {
	return hlm_kadence_is_landing_page();
}
/**
 * Render the HLM site header.
 */
function hlm_render_site_header() {
	get_template_part( 'template-parts/hlm/site-header' );
}
