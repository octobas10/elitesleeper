<?php
/**
 * Bootstrap SEO meta and cluster content on site setup.
 *
 * @package HLM_SEO
 */

namespace HLM_SEO;

defined( 'ABSPATH' ) || exit;

/**
 * Applies registry meta to pages and enriches sample cluster posts.
 */
class Content_Bootstrap {

	/**
	 * Register hooks.
	 */
	public static function init(): void {
		add_action( 'hlm_seo_bootstrap_pages', array( self::class, 'bootstrap_pages' ), 10, 1 );
		add_action( 'hlm_seo_bootstrap_posts', array( self::class, 'bootstrap_posts' ) );
	}

	/**
	 * Apply SEO meta to an array of page configs.
	 *
	 * @param array<int, array<string, mixed>> $pages Page configs with slug keys.
	 */
	public static function bootstrap_pages( array $pages ): void {
		foreach ( $pages as $page ) {
			if ( empty( $page['slug'] ) ) {
				continue;
			}

			$existing = get_page_by_path( $page['slug'] );
			if ( $existing instanceof \WP_Post ) {
				Registry::apply_page_meta( (int) $existing->ID, $page['slug'] );
			}
		}
	}

	/**
	 * Enrich cluster posts with SEO-structured content if stubs exist.
	 */
	public static function bootstrap_posts(): void {
		self::ensure_cluster_posts_exist();

		$posts = self::get_cluster_post_content();

		foreach ( $posts as $slug => $data ) {
			$existing = get_page_by_path( $slug, OBJECT, 'post' );
			if ( ! $existing instanceof \WP_Post ) {
				continue;
			}

			$plain_length = strlen( hlm_seo_get_plain_text( $existing->post_content, $existing ) );
			if ( $plain_length > 3500 && empty( $data['force'] ) ) {
				continue;
			}

			wp_update_post(
				array(
					'ID'           => $existing->ID,
					'post_content' => hlm_seo_normalize_dashes( (string) $data['content'] ),
					'post_excerpt' => hlm_seo_normalize_dashes( (string) ( $data['excerpt'] ?? '' ) ),
				)
			);

			if ( ! empty( $data['focus_keyword'] ) ) {
				update_post_meta( $existing->ID, '_hlm_seo_focus_keyword', hlm_seo_normalize_dashes( (string) $data['focus_keyword'] ) );
			}
			if ( ! empty( $data['seo_title'] ) ) {
				update_post_meta( $existing->ID, '_hlm_seo_title', hlm_seo_normalize_dashes( (string) $data['seo_title'] ) );
			}
			if ( ! empty( $data['excerpt'] ) ) {
				update_post_meta( $existing->ID, '_hlm_seo_description', hlm_seo_normalize_dashes( (string) $data['excerpt'] ) );
			}

			if ( ! empty( $data['featured_image'] ) && ( ! empty( $data['force'] ) || ! has_post_thumbnail( $existing->ID ) ) ) {
				self::maybe_set_featured_image( (int) $existing->ID, (string) $data['featured_image'] );
			}
		}
	}

	/**
	 * Attach a branded featured image variant to a post.
	 *
	 * @param int    $post_id Post ID.
	 * @param string $variant Image variant slug (e.g. university, student, hlm).
	 */
	private static function maybe_set_featured_image( int $post_id, string $variant ): void {
		$attachment_id = self::get_featured_attachment_id( $variant );
		if ( $attachment_id > 0 ) {
			set_post_thumbnail( $post_id, $attachment_id );
		}
	}

	/**
	 * Resolve or upload a cached featured image attachment for a variant.
	 *
	 * @param string $variant Image variant slug.
	 * @return int Attachment ID or 0 on failure.
	 */
	private static function get_featured_attachment_id( string $variant ): int {
		$variant = sanitize_file_name( $variant );
		if ( '' === $variant ) {
			return 0;
		}

		$cache = get_option( 'hlm_seo_featured_attachments', array() );
		if ( ! empty( $cache[ $variant ] ) ) {
			$cached = (int) $cache[ $variant ];
			if ( $cached > 0 && get_post( $cached ) instanceof \WP_Post ) {
				return $cached;
			}
		}

		$theme_dir = defined( 'HLM_KADENCE_DIR' ) ? HLM_KADENCE_DIR : get_template_directory();
		$source    = $theme_dir . '/assets/hlm/blog/featured-' . $variant . '.svg';
		if ( ! is_readable( $source ) ) {
			return 0;
		}

		$contents = file_get_contents( $source );
		if ( false === $contents ) {
			return 0;
		}

		$upload_dir = wp_upload_dir();
		if ( ! empty( $upload_dir['error'] ) ) {
			return 0;
		}

		$filename = 'hlm-featured-' . $variant . '.svg';
		$dest     = trailingslashit( $upload_dir['path'] ) . $filename;

		// phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_file_put_contents
		if ( false === file_put_contents( $dest, $contents ) ) {
			return 0;
		}

		$attachment = array(
			'post_mime_type' => 'image/svg+xml',
			'post_title'     => 'HLM Blog Featured - ' . ucwords( str_replace( '-', ' ', $variant ) ),
			'post_content'   => '',
			'post_status'    => 'inherit',
		);

		$attach_id = wp_insert_attachment( $attachment, $dest );
		if ( is_wp_error( $attach_id ) || ! $attach_id ) {
			return 0;
		}

		require_once ABSPATH . 'wp-admin/includes/image.php';
		$metadata = wp_generate_attachment_metadata( $attach_id, $dest );
		if ( ! is_wp_error( $metadata ) && $metadata ) {
			wp_update_attachment_metadata( $attach_id, $metadata );
		}

		$cache[ $variant ] = (int) $attach_id;
		update_option( 'hlm_seo_featured_attachments', $cache, false );

		return (int) $attach_id;
	}

	/**
	 * Create missing cluster posts defined in topic cluster config.
	 */
	private static function ensure_cluster_posts_exist(): void {
		foreach ( hlm_seo_topic_clusters() as $cluster ) {
			if ( empty( $cluster['clusters'] ) ) {
				continue;
			}

			foreach ( $cluster['clusters'] as $item ) {
				$slug = $item['slug'] ?? '';
				if ( ! $slug || get_page_by_path( $slug, OBJECT, 'post' ) ) {
					continue;
				}

				wp_insert_post(
					array(
						'post_type'    => 'post',
						'post_status'  => 'publish',
						'post_title'   => $item['title'] ?? ucwords( str_replace( '-', ' ', $slug ) ),
						'post_name'    => $slug,
						'post_content' => '<p>' . esc_html( $item['title'] ?? '' ) . '</p>',
					)
				);
			}
		}
	}

	/**
	 * Default cluster post content - overridden by theme bridge.
	 *
	 * @return array<string, array<string, string>>
	 */
	private static function get_cluster_post_content(): array {
		/**
		 * Filter cluster post bootstrap content keyed by post slug.
		 *
		 * @param array<string, array<string, string>> $posts Post content map.
		 */
		return apply_filters( 'hlm_seo_cluster_post_content', array() );
	}
}
