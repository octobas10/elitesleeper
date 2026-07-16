<?php
/**
 * Shared HLM SEO helpers.
 *
 * @package HLM_SEO
 */

defined( 'ABSPATH' ) || exit;

/**
 * Replace Unicode em dashes only (HLM copy style). En dashes are kept for grammar.
 *
 * @param string $text Input text.
 * @return string
 */
function hlm_seo_normalize_dashes( string $text ): string {
	if ( '' === $text || false === strpos( $text, '—' ) ) {
		return $text;
	}

	$text = str_replace( ' — ', ' – ', $text );

	return str_replace( '—', '-', $text );
}

/**
 * Normalize dash characters in cluster post bootstrap arrays.
 *
 * @param array<string, array<string, mixed>> $posts Post content map.
 * @return array<string, array<string, mixed>>
 */
function hlm_seo_normalize_cluster_post_dashes( array $posts ): array {
	foreach ( $posts as $slug => $data ) {
		foreach ( array( 'content', 'excerpt', 'seo_title', 'focus_keyword' ) as $field ) {
			if ( ! empty( $data[ $field ] ) && is_string( $data[ $field ] ) ) {
				$posts[ $slug ][ $field ] = hlm_seo_normalize_dashes( $data[ $field ] );
			}
		}
	}

	return $posts;
}

/**
 * Normalize dash characters in page FAQ registry entries.
 *
 * @param array<string, array<int, array<string, string>>> $faqs FAQ map.
 * @return array<string, array<int, array<string, string>>>
 */
function hlm_seo_normalize_page_faq_dashes( array $faqs ): array {
	foreach ( $faqs as $slug => $items ) {
		foreach ( $items as $index => $item ) {
			if ( ! empty( $item['question'] ) ) {
				$faqs[ $slug ][ $index ]['question'] = hlm_seo_normalize_dashes( $item['question'] );
			}
			if ( ! empty( $item['answer'] ) ) {
				$faqs[ $slug ][ $index ]['answer'] = hlm_seo_normalize_dashes( $item['answer'] );
			}
		}
	}

	return $faqs;
}

/**
 * Extract plain text from post content for SEO fallbacks (block-aware).
 *
 * @param string        $content Post content.
 * @param \WP_Post|null $post    Optional post context.
 * @return string
 */
function hlm_seo_get_plain_text( string $content, ?\WP_Post $post = null ): string {
	if ( ! class_exists( 'HLM_SEO\Content_Parser' ) ) {
		return trim( wp_strip_all_tags( $content ) );
	}

	return \HLM_SEO\Content_Parser::get_plain_text( $content, $post );
}
