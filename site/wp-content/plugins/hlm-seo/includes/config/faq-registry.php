<?php
/**
 * Per-page FAQ registry for FAQPage schema and on-page FAQ blocks.
 *
 * @package HLM_SEO
 */

defined( 'ABSPATH' ) || exit;

/**
 * FAQ items keyed by page slug.
 *
 * @return array<string, array<int, array<string, string>>>
 */
function hlm_seo_page_faqs(): array {
	/**
	 * Filter FAQ registry keyed by page slug.
	 *
	 * Each entry is an array of { question, answer } pairs.
	 *
	 * @param array<string, array<int, array<string, string>>> $defaults FAQ map.
	 */
	return apply_filters( 'hlm_seo_page_faqs', array() );
}

/**
 * Get FAQs for the current singular page.
 *
 * @return array<int, array<string, string>>
 */
function hlm_seo_get_current_faqs(): array {
	if ( ! is_singular( 'page' ) ) {
		return array();
	}

	$post = get_queried_object();
	if ( ! $post instanceof WP_Post ) {
		return array();
	}

	$faqs = hlm_seo_page_faqs();
	return $faqs[ $post->post_name ] ?? array();
}
