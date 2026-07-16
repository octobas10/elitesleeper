<?php
/**
 * SEO registry - resolves per-page and per-post metadata.
 *
 * @package HLM_SEO
 */

namespace HLM_SEO;

defined( 'ABSPATH' ) || exit;

/**
 * Registry resolver for titles, descriptions, and page roles.
 */
class Registry {

	/**
	 * Get SEO config for the current queried object.
	 *
	 * @return array<string, mixed>
	 */
	public static function for_current(): array {
		if ( is_singular() ) {
			$post = get_queried_object();
			if ( $post instanceof \WP_Post ) {
				return self::for_post( $post );
			}
		}

		if ( is_home() && ! is_front_page() ) {
			return self::for_slug( 'blog' );
		}

		return array();
	}

	/**
	 * Get SEO config for a post object.
	 *
	 * @param \WP_Post $post Post object.
	 * @return array<string, mixed>
	 */
	public static function for_post( \WP_Post $post ): array {
		$custom = array_filter(
			array(
				'title'         => get_post_meta( $post->ID, '_hlm_seo_title', true ),
				'description'   => get_post_meta( $post->ID, '_hlm_seo_description', true ),
				'focus_keyword' => get_post_meta( $post->ID, '_hlm_seo_focus_keyword', true ),
				'noindex'       => (bool) get_post_meta( $post->ID, '_hlm_seo_noindex', true ),
			)
		);

		if ( 'page' === $post->post_type ) {
			$slug_config = self::for_slug( $post->post_name );
			return array_merge( $slug_config, $custom );
		}

		if ( 'post' === $post->post_type ) {
			$cluster = hlm_seo_get_post_cluster( $post->post_name );
			$config  = array(
				'title'         => $post->post_title . ' | ' . get_bloginfo( 'name' ),
				'description'   => $post->post_excerpt ?: wp_trim_words( hlm_seo_get_plain_text( $post->post_content, $post ), 30 ),
				'schema_type'   => 'Article',
				'page_role'     => 'cluster',
				'index'         => true,
				'focus_keyword' => $cluster['focus_keyword'] ?? '',
				'cluster_id'    => $cluster['cluster_id'] ?? '',
			);

			return array_merge( $config, $custom );
		}

		return $custom;
	}

	/**
	 * Get SEO config by page slug.
	 *
	 * @param string $slug Page slug.
	 * @return array<string, mixed>
	 */
	public static function for_slug( string $slug ): array {
		$registry = hlm_seo_page_registry();
		return $registry[ $slug ] ?? array();
	}

	/**
	 * Get custom SEO title from registry (no WP title fallback).
	 *
	 * @return string
	 */
	public static function custom_title(): string {
		$config = self::for_current();
		return $config['title'] ?? '';
	}

	/**
	 * Get resolved document title for output contexts (OG, schema).
	 *
	 * Safe to call outside the pre_get_document_title filter.
	 *
	 * @return string
	 */
	public static function document_title(): string {
		$custom = self::custom_title();
		return $custom ?: wp_get_document_title();
	}

	/**
	 * Get resolved meta description.
	 *
	 * @return string
	 */
	public static function meta_description(): string {
		$config = self::for_current();

		if ( ! empty( $config['description'] ) ) {
			return $config['description'];
		}

		if ( is_singular() ) {
			$post = get_queried_object();
			if ( $post instanceof \WP_Post && $post->post_excerpt ) {
				return $post->post_excerpt;
			}
		}

		return get_bloginfo( 'description' );
	}

	/**
	 * Whether current page should be indexed.
	 *
	 * @return bool
	 */
	public static function should_index(): bool {
		$config = self::for_current();

		if ( ! empty( $config['noindex'] ) ) {
			return false;
		}

		if ( isset( $config['index'] ) ) {
			return (bool) $config['index'];
		}

		return ! is_search() && ! is_404();
	}

	/**
	 * Slim SEO title filter.
	 *
	 * @param string $title   Current title.
	 * @param int    $post_id Post ID.
	 * @return string
	 */
	public static function filter_slim_meta_title( string $title, int $post_id ): string {
		$post = get_post( $post_id );
		if ( ! $post ) {
			return $title;
		}

		$config = self::for_post( $post );
		return $config['title'] ?? $title;
	}

	/**
	 * Slim SEO description filter.
	 *
	 * @param string $description Current description.
	 * @param int    $post_id     Post ID.
	 * @return string
	 */
	public static function filter_slim_meta_description( string $description, int $post_id ): string {
		$post = get_post( $post_id );
		if ( ! $post ) {
			return $description;
		}

		$config = self::for_post( $post );
		return $config['description'] ?? $description;
	}

	/**
	 * Bootstrap page meta on setup.
	 *
	 * @param int    $page_id Page ID.
	 * @param string $slug    Page slug.
	 */
	public static function apply_page_meta( int $page_id, string $slug ): void {
		$config = self::for_slug( $slug );
		if ( empty( $config ) ) {
			return;
		}

		if ( ! empty( $config['title'] ) ) {
			update_post_meta( $page_id, '_hlm_seo_title', $config['title'] );
		}
		if ( ! empty( $config['description'] ) ) {
			update_post_meta( $page_id, '_hlm_seo_description', $config['description'] );
			if ( ! get_post_field( 'post_excerpt', $page_id ) ) {
				wp_update_post(
					array(
						'ID'           => $page_id,
						'post_excerpt' => $config['description'],
					)
				);
			}
		}
		if ( ! empty( $config['focus_keyword'] ) ) {
			update_post_meta( $page_id, '_hlm_seo_focus_keyword', $config['focus_keyword'] );
		}
	}
}
