<?php
/**
 * Builders for converting legacy SEO HTML into Gutenberg block markup.
 *
 * @package HLM_Kadence
 */

defined( 'ABSPATH' ) || exit;

/**
 * Parse legacy cluster HTML (h2 sections + paragraphs) into block markup.
 *
 * @param string $html Raw HTML with h2/p structure.
 * @return string
 */
function hlm_article_from_legacy_html( string $html ): string {
	$blocks = hlm_article_parse_legacy_html( $html );

	return implode( "\n\n", array_filter( $blocks ) );
}

/**
 * Parse legacy HTML into an array of block markup strings.
 *
 * @param string $html Raw HTML.
 * @return array<int, string>
 */
function hlm_article_parse_legacy_html( string $html ): array {
	$html   = trim( $html );
	$blocks = array();

	if ( '' === $html || ! preg_match_all( '/<h2>(.*?)<\/h2>\s*(.*?)(?=<h2>|$)/s', $html, $sections, PREG_SET_ORDER ) ) {
		return $blocks;
	}

	foreach ( $sections as $section ) {
		$heading = trim( html_entity_decode( wp_strip_all_tags( $section[1] ), ENT_QUOTES | ENT_HTML5, 'UTF-8' ) );
		$body    = (string) $section[2];

		if ( '' !== $heading ) {
			$blocks[] = hlm_article_heading( $heading );
		}

		if ( preg_match_all( '/<p>(.*?)<\/p>/s', $body, $paragraphs, PREG_SET_ORDER ) ) {
			foreach ( $paragraphs as $paragraph ) {
				$blocks[] = hlm_article_paragraph( (string) $paragraph[1] );
			}
		}
	}

	return $blocks;
}

/**
 * Build article-callout block attributes helper.
 *
 * @param string               $headline   Callout headline.
 * @param array<int, string>   $paragraphs Body paragraphs (links allowed).
 * @param string               $variant    help|cta|tip.
 * @return string
 */
function hlm_article_callout_markup( string $headline, array $paragraphs, string $variant = 'help' ): string {
	return hlm_article_block(
		'article-callout',
		array(
			'variant'    => $variant,
			'headline'   => $headline,
			'paragraphs' => $paragraphs,
		)
	);
}

/**
 * Build article-checklist block markup helper.
 *
 * @param string             $headline   Checklist headline.
 * @param array<int, string> $paragraphs Intro paragraphs.
 * @param array<int, string> $items      Checklist items.
 * @param array<int, string> $outro      Closing paragraphs.
 * @return string
 */
function hlm_article_checklist_markup( string $headline, array $paragraphs, array $items, array $outro = array() ): string {
	return hlm_article_block(
		'article-checklist',
		array(
			'headline'   => $headline,
			'paragraphs' => $paragraphs,
			'items'      => $items,
			'outro'      => $outro,
		)
	);
}

/**
 * Build article-compare block markup helper.
 *
 * @param array<string, mixed> $args Compare block arguments.
 * @return string
 */
function hlm_article_compare_markup( array $args ): string {
	return hlm_article_block( 'article-compare', $args );
}
