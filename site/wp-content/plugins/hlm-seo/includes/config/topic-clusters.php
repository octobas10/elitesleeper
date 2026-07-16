<?php
/**
 * Topic cluster architecture - override via `hlm_seo_topic_clusters` filter.
 *
 * @package HLM_SEO
 */

defined( 'ABSPATH' ) || exit;

/**
 * Topic cluster definitions.
 *
 * @return array<string, array<string, mixed>>
 */
function hlm_seo_topic_clusters(): array {
	/**
	 * Filter topic cluster definitions (pillar + spoke model).
	 *
	 * @param array<string, array<string, mixed>> $defaults Cluster config.
	 */
	return apply_filters( 'hlm_seo_topic_clusters', array() );
}

/**
 * Strategic internal link map - override via `hlm_seo_internal_link_map` filter.
 *
 * @return array<string, array<int, array<string, string>>>
 */
function hlm_seo_internal_link_map(): array {
	/**
	 * Filter the hub-to-spoke internal link map keyed by page slug.
	 *
	 * @param array<string, array<int, array<string, string>>> $defaults Link map.
	 */
	return apply_filters( 'hlm_seo_internal_link_map', array() );
}

/**
 * Find cluster membership for a post slug.
 *
 * @param string $post_slug Post slug.
 * @return array<string, mixed>|null
 */
function hlm_seo_get_post_cluster( string $post_slug ): ?array {
	foreach ( hlm_seo_topic_clusters() as $cluster_id => $cluster ) {
		if ( empty( $cluster['clusters'] ) ) {
			continue;
		}

		foreach ( $cluster['clusters'] as $post ) {
			if ( ( $post['slug'] ?? '' ) === $post_slug ) {
				return array_merge(
					$post,
					array(
						'cluster_id'  => $cluster_id,
						'pillar_slug' => $cluster['pillar_slug'] ?? '',
					)
				);
			}
		}
	}

	return null;
}
