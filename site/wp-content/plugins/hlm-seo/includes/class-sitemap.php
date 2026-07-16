<?php
/**
 * XML sitemap enhancements.
 *
 * @package HLM_SEO
 */

namespace HLM_SEO;

defined( 'ABSPATH' ) || exit;

/**
 * Sitemap filters for priority, exclusions, and private CPTs.
 */
class Sitemap {

	/**
	 * Register hooks.
	 */
	public static function init(): void {
		add_filter( 'wp_sitemaps_post_types', array( self::class, 'filter_post_types' ) );
		add_filter( 'wp_sitemaps_posts_query_args', array( self::class, 'filter_query_args' ), 10, 2 );
		add_filter( 'wp_sitemaps_posts_entry', array( self::class, 'filter_entry' ), 10, 3 );
	}

	/**
	 * Exclude private/internal post types from sitemap.
	 *
	 * @param array<string, \WP_Post_Type> $post_types Post types.
	 * @return array<string, \WP_Post_Type>
	 */
	public static function filter_post_types( array $post_types ): array {
		$exclude = apply_filters( 'hlm_seo_sitemap_exclude_post_types', array() );

		foreach ( $exclude as $type ) {
			unset( $post_types[ $type ] );
		}

		return $post_types;
	}

	/**
	 * Exclude noindex posts from sitemap.
	 *
	 * @param array<string, mixed> $args      Query args.
	 * @param string               $post_type Post type.
	 * @return array<string, mixed>
	 */
	public static function filter_query_args( array $args, string $post_type ): array {
		if ( 'page' !== $post_type && 'post' !== $post_type ) {
			return $args;
		}

		$args['meta_query'] = array(
			'relation' => 'OR',
			array(
				'key'     => '_hlm_seo_noindex',
				'compare' => 'NOT EXISTS',
			),
			array(
				'key'     => '_hlm_seo_noindex',
				'value'   => '1',
				'compare' => '!=',
			),
		);

		return $args;
	}

	/**
	 * Add priority and changefreq from registry.
	 *
	 * @param array<string, mixed> $entry    Sitemap entry.
	 * @param \WP_Post             $post     Post object.
	 * @param string               $post_type Post type.
	 * @return array<string, mixed>
	 */
	public static function filter_entry( array $entry, \WP_Post $post, string $post_type ): array {
		unset( $post_type );

		$config = Registry::for_post( $post );

		if ( ! empty( $config['priority'] ) ) {
			$entry['priority'] = (float) $config['priority'];
		}
		if ( ! empty( $config['changefreq'] ) ) {
			$entry['changefreq'] = $config['changefreq'];
		}

		return $entry;
	}
}
