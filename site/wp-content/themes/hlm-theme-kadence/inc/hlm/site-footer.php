<?php
/**
 * Global HLM site footer.
 *
 * @package HLM_Kadence
 */

defined( 'ABSPATH' ) || exit;

/**
 * Canonical site contact details for header, footer, and schema.
 *
 * @return array{
 *     email: string,
 *     phone_display: string,
 *     phone_href: string,
 *     address_display: string,
 *     address_href: string,
 *     contact_url: string
 * }
 */
function hlm_site_contact() {
	$defaults = array(
		'email'           => 'support@higherlearningmarketers.com',
		'phone_display'   => '+1 (718) 685-2575',
		'phone_href'      => 'tel:+17186852575',
		'address_display' => '138-07 82nd Drive, Briarwood, NY 11435',
		'address_href'    => 'https://www.google.com/maps/search/?api=1&query=138-07+82nd+Drive,+Briarwood,+NY+11435',
		'contact_url'     => home_url( '/contact-us/' ),
	);

	return apply_filters( 'hlm_site_contact', $defaults );
}

/**
 * Render the HLM site footer.
 */
function hlm_render_site_footer() {
	get_template_part( 'template-parts/hlm/site-footer' );
}
