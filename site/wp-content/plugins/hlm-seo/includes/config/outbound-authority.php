<?php
/**
 * Authoritative outbound link registry.
 *
 * @package HLM_SEO
 */

defined( 'ABSPATH' ) || exit;

/**
 * Trusted outbound sources keyed by reference ID.
 *
 * @return array<string, array<string, string>>
 */
function hlm_seo_outbound_authority(): array {
	/**
	 * Filter authoritative outbound link registry.
	 *
	 * @param array<string, array<string, string>> $defaults Outbound refs.
	 */
	return apply_filters( 'hlm_seo_outbound_authority', array() );
}

/**
 * Resolve outbound reference IDs to link HTML.
 *
 * @param array<int, string> $ref_ids Reference IDs.
 * @return string
 */
function hlm_seo_render_outbound_refs( array $ref_ids ): string {
	$registry = hlm_seo_outbound_authority();
	$links    = array();

	foreach ( $ref_ids as $ref_id ) {
		if ( empty( $registry[ $ref_id ] ) ) {
			continue;
		}

		$ref     = $registry[ $ref_id ];
		$links[] = sprintf(
			'<li><a href="%s" rel="%s" target="_blank">%s</a></li>',
			esc_url( $ref['url'] ),
			esc_attr( $ref['rel'] ?? 'noopener noreferrer' ),
			esc_html( $ref['title'] )
		);
	}

	if ( empty( $links ) ) {
		return '';
	}

	return '<aside class="hlm-seo-sources" aria-label="' . esc_attr__( 'Sources and references', 'hlm-seo' ) . '"><h3>' . esc_html__( 'Sources & References', 'hlm-seo' ) . '</h3><ul>' . implode( '', $links ) . '</ul></aside>';
}
