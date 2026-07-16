<?php
/**
 * Gutenberg block markup for service detail page bodies.
 *
 * @package HLM_Kadence
 */

defined( 'ABSPATH' ) || exit;

/**
 * Open a full-width group section (no constrained inner max-width).
 *
 * @param string $class_name Section class names.
 * @return string
 */
function hlm_service_section_group_open( string $class_name ): string {
	$attrs = array(
		'layout'    => array( 'type' => 'default' ),
		'className' => $class_name,
	);

	return sprintf(
		"<!-- wp:group %s -->\n<div class=\"wp-block-group %s\">",
		wp_json_encode( $attrs ),
		esc_attr( $class_name )
	);
}

/**
 * Close a group section.
 *
 * @return string
 */
function hlm_service_section_group_close(): string {
	return "</div>\n<!-- /wp:group -->";
}

/**
 * Serialize an HTML block for structured service components.
 *
 * @param string $html Safe HTML.
 * @return string
 */
function hlm_services_html_block( string $html ): string {
	return sprintf( "<!-- wp:html -->\n%s\n<!-- /wp:html -->", $html );
}

/**
 * Heading block with HLM utility classes.
 *
 * @param string $text       Heading text.
 * @param string $class_name Utility class (e.g. hlm-h3).
 * @param string $anchor     Optional HTML id.
 * @return string
 */
function hlm_service_heading_block( string $text, string $class_name = 'hlm-h3', string $anchor = '' ): string {
	$attrs = array(
		'level'     => 2,
		'className' => $class_name,
	);

	if ( '' !== $anchor ) {
		$attrs['anchor'] = $anchor;
	}

	$id_attr = '' !== $anchor ? sprintf( ' id="%s"', esc_attr( $anchor ) ) : '';
	$html    = sprintf(
		'<h2 class="wp-block-heading %1$s"%2$s>%3$s</h2>',
		esc_attr( $class_name ),
		$id_attr,
		esc_html( $text )
	);

	if ( ! function_exists( 'hlm_serialize_block' ) ) {
		return $html;
	}

	return hlm_serialize_block(
		array(
			'blockName'    => 'core/heading',
			'attrs'        => $attrs,
			'innerBlocks'  => array(),
			'innerHTML'    => $html,
			'innerContent' => array( $html ),
		)
	);
}

/**
 * Build metrics row as columns inside a group section.
 *
 * @param array<int, array{value?: string, label?: string}> $metrics Metric rows.
 * @return string
 */
function hlm_services_build_metrics_markup( array $metrics ): string {
	$columns = array();

	foreach ( $metrics as $metric ) {
		if ( empty( $metric['value'] ) || empty( $metric['label'] ) ) {
			continue;
		}

		$inner = sprintf(
			'<div class="hlm-single-service-metric" role="listitem"><div class="hlm-single-service-metric__value">%1$s</div><div class="hlm-single-service-metric__label">%2$s</div></div>',
			esc_html( $metric['value'] ),
			esc_html( $metric['label'] )
		);

		$columns[] = "<!-- wp:column -->\n<div class=\"wp-block-column\">"
			. hlm_services_html_block( $inner )
			. "\n</div>\n<!-- /wp:column -->";
	}

	if ( empty( $columns ) ) {
		return '';
	}

	$columns_block = "<!-- wp:columns {\"className\":\"hlm-single-service-metrics\"} -->\n<div class=\"wp-block-columns hlm-single-service-metrics\" role=\"list\">"
		. implode( "\n\n", $columns )
		. "\n</div>\n<!-- /wp:columns -->";

	return hlm_service_section_group_open( 'hlm-single-service-section' )
		. $columns_block
		. hlm_service_section_group_close();
}

/**
 * Build highlights grid inside a group section.
 *
 * @param array<int, string> $highlights Highlight strings.
 * @return string
 */
function hlm_services_build_highlights_markup( array $highlights ): string {
	if ( empty( $highlights ) ) {
		return '';
	}

	$items = array();

	foreach ( $highlights as $highlight ) {
		$items[] = sprintf(
			'<li class="hlm-single-service-highlight"><span class="hlm-single-service-highlight__icon" aria-hidden="true">✓</span><span class="hlm-single-service-highlight__text">%s</span></li>',
			esc_html( $highlight )
		);
	}

	$grid = sprintf(
		'<ul class="hlm-single-service-highlights__grid" role="list">%s</ul>',
		implode( '', $items )
	);

	return hlm_service_section_group_open( 'hlm-single-service-section hlm-single-service-highlights' )
		. hlm_service_heading_block( __( "What's Included", 'hlm-kadence' ), 'hlm-h3', 'hlm-service-highlights-title' )
		. hlm_services_html_block( $grid )
		. hlm_service_section_group_close();
}

/**
 * Build audience callout inside a group section.
 *
 * @param string $audience Audience copy.
 * @return string
 */
function hlm_services_build_audience_markup( string $audience ): string {
	if ( '' === trim( $audience ) ) {
		return '';
	}

	$html = sprintf(
		'<section class="hlm-single-service-audience" aria-labelledby="hlm-service-audience-title"><div class="hlm-single-service-audience__inner"><div class="hlm-overline" id="hlm-service-audience-title">%1$s</div><p class="hlm-body">%2$s</p></div></section>',
		esc_html__( "Who It's For", 'hlm-kadence' ),
		esc_html( $audience )
	);

	return hlm_service_section_group_open( 'hlm-single-service-section hlm-single-service-audience-wrap' )
		. hlm_services_html_block( $html )
		. hlm_service_section_group_close();
}

/**
 * Compose the full service detail body as native block storage markup.
 *
 * @param array<string, mixed> $entry Service catalog entry.
 * @return string
 */
function hlm_services_build_detail_block_content( array $entry ): string {
	$parts   = array();
	$parts[] = hlm_service_section_group_open( 'hlm-single-service-body' );

	$metrics_markup = hlm_services_build_metrics_markup( $entry['metrics'] ?? array() );
	if ( '' !== $metrics_markup ) {
		$parts[] = $metrics_markup;
	}

	$highlights_markup = hlm_services_build_highlights_markup( $entry['highlights'] ?? array() );
	if ( '' !== $highlights_markup ) {
		$parts[] = $highlights_markup;
	}

	$audience_markup = hlm_services_build_audience_markup( (string) ( $entry['audience'] ?? '' ) );
	if ( '' !== $audience_markup ) {
		$parts[] = $audience_markup;
	}

	$parts[] = hlm_service_section_group_close();

	return implode( "\n\n", $parts );
}

/**
 * Render the service detail body (SEO intro + block sections).
 *
 * @param array<string, mixed> $entry Service catalog entry.
 * @return string
 */
function hlm_services_render_detail_body( array $entry ): string {
	ob_start();

	if ( function_exists( 'hlm_kadence_render_service_seo_intro' ) ) {
		hlm_kadence_render_service_seo_intro();
	}

	echo do_blocks( hlm_services_build_detail_block_content( $entry ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

	return (string) ob_get_clean();
}
