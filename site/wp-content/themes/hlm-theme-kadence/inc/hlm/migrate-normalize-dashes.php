<?php
/**
 * One-time migration: replace em/en dashes in stored page and post content.
 *
 * @package HLM_Kadence
 */

defined( 'ABSPATH' ) || exit;

/**
 * Replace Unicode em dashes only. En dashes are kept for grammar (ranges, times).
 *
 * @param string $text Input text.
 * @return string
 */
function hlm_normalize_text_dashes( string $text ): string {
	if ( '' === $text || ! str_contains( $text, '—' ) ) {
		return $text;
	}

	$text = str_replace( ' — ', ' – ', $text );

	return str_replace( '—', '-', $text );
}

/**
 * Re-sync marketing pages from pattern files (hyphen-only source copy).
 */
function hlm_migrate_refresh_pattern_pages(): void {
	if ( ! function_exists( 'hlm_get_pattern_markup' ) ) {
		return;
	}

	$pages = array(
		array( 'slug' => 'home', 'pattern' => 'home-page.php', 'is_front' => true ),
		array( 'slug' => 'about-us', 'pattern' => 'about-us-page.php' ),
		array( 'slug' => 'services', 'pattern' => 'services-page.php' ),
		array( 'slug' => 'affiliations', 'pattern' => 'affiliations-page.php' ),
		array( 'slug' => 'contact-us', 'pattern' => 'contact-page.php' ),
		array( 'slug' => 'privacy-policy', 'pattern' => 'privacy-page.php' ),
	);

	foreach ( $pages as $page ) {
		$content = hlm_get_pattern_markup( $page['pattern'] );
		if ( '' === $content ) {
			continue;
		}

		$page_id = 0;
		if ( ! empty( $page['is_front'] ) ) {
			$page_id = (int) get_option( 'page_on_front' );
		}
		if ( ! $page_id ) {
			$existing = get_page_by_path( $page['slug'] );
			if ( $existing instanceof WP_Post ) {
				$page_id = (int) $existing->ID;
			}
		}

		if ( $page_id > 0 ) {
			wp_update_post(
				array(
					'ID'           => $page_id,
					'post_content' => $content,
				)
			);
		}
	}
}

/**
 * Re-sync child service detail pages from the catalog.
 */
function hlm_migrate_refresh_service_pages(): void {
	if ( ! function_exists( 'hlm_service_catalog' ) || ! function_exists( 'hlm_upsert_page' ) ) {
		return;
	}

	$parent_id = function_exists( 'hlm_services_parent_page_id' ) ? hlm_services_parent_page_id() : 0;
	if ( ! $parent_id ) {
		return;
	}

	foreach ( hlm_service_catalog() as $slug => $service ) {
		hlm_upsert_page(
			array(
				'title'   => $service['title'],
				'slug'    => $slug,
				'content' => $service['content'],
				'parent'  => $parent_id,
			)
		);
	}
}

/**
 * Force-refresh cluster blog posts from theme source content.
 */
function hlm_migrate_refresh_blog_posts(): void {
	if ( ! function_exists( 'hlm_kadence_seo_blog_posts_content' ) ) {
		return;
	}

	$posts = hlm_kadence_seo_blog_posts_content();

	foreach ( $posts as $slug => $data ) {
		$existing = get_page_by_path( $slug, OBJECT, 'post' );
		if ( ! $existing instanceof WP_Post ) {
			continue;
		}

		wp_update_post(
			array(
				'ID'           => $existing->ID,
				'post_content' => $data['content'],
				'post_excerpt' => $data['excerpt'],
			)
		);

		if ( ! empty( $data['focus_keyword'] ) ) {
			update_post_meta( $existing->ID, '_hlm_seo_focus_keyword', $data['focus_keyword'] );
		}
		if ( ! empty( $data['seo_title'] ) ) {
			update_post_meta( $existing->ID, '_hlm_seo_title', $data['seo_title'] );
		}
		if ( ! empty( $data['excerpt'] ) ) {
			update_post_meta( $existing->ID, '_hlm_seo_description', $data['excerpt'] );
		}
	}
}

/**
 * Normalize dashes in any remaining post/page fields and SEO meta.
 */
function hlm_migrate_sweep_stored_dashes(): void {
	$posts = get_posts(
		array(
			'post_type'      => array( 'post', 'page' ),
			'post_status'    => 'any',
			'posts_per_page' => -1,
		)
	);

	$meta_keys = array( '_hlm_seo_title', '_hlm_seo_description' );

	foreach ( $posts as $post ) {
		$updates = array();

		foreach ( array( 'post_content', 'post_title', 'post_excerpt' ) as $field ) {
			$normalized = hlm_normalize_text_dashes( $post->$field );
			if ( $normalized !== $post->$field ) {
				$updates[ $field ] = $normalized;
			}
		}

		if ( ! empty( $updates ) ) {
			$updates['ID'] = $post->ID;
			wp_update_post( $updates );
		}

		foreach ( $meta_keys as $meta_key ) {
			$value = get_post_meta( $post->ID, $meta_key, true );
			if ( ! is_string( $value ) || '' === $value ) {
				continue;
			}

			$normalized = hlm_normalize_text_dashes( $value );
			if ( $normalized !== $value ) {
				update_post_meta( $post->ID, $meta_key, $normalized );
			}
		}
	}
}

/**
 * Re-apply registry SEO meta (titles/descriptions) for core pages.
 */
function hlm_migrate_refresh_page_seo_meta(): void {
	if ( ! class_exists( 'HLM_SEO\Registry' ) ) {
		return;
	}

	$slugs = array( 'home', 'about-us', 'services', 'affiliations', 'contact-us', 'blog', 'privacy-policy' );

	foreach ( $slugs as $slug ) {
		$page_id = 0;

		if ( 'home' === $slug ) {
			$page_id = (int) get_option( 'page_on_front' );
		}

		if ( ! $page_id ) {
			$page = get_page_by_path( $slug );
			if ( $page instanceof WP_Post ) {
				$page_id = (int) $page->ID;
			}
		}

		if ( $page_id > 0 ) {
			HLM_SEO\Registry::apply_page_meta( $page_id, $slug );
		}
	}
}

/**
 * One-time migration: refresh content from source files and sweep em dashes from the database.
 */
function hlm_migrate_normalize_dashes(): void {
	if ( get_option( 'hlm_normalize_dashes_v1' ) ) {
		return;
	}

	hlm_migrate_refresh_pattern_pages();
	hlm_migrate_refresh_service_pages();
	hlm_migrate_refresh_blog_posts();
	hlm_migrate_refresh_page_seo_meta();
	hlm_migrate_sweep_stored_dashes();

	update_option( 'hlm_normalize_dashes_v1', 1, false );
}
add_action( 'init', 'hlm_migrate_normalize_dashes', 38 );

/**
 * Re-sync pattern pages and sweep em dashes only (preserves en dashes).
 */
function hlm_migrate_normalize_em_dashes_v2(): void {
	if ( get_option( 'hlm_normalize_em_dashes_v2' ) ) {
		return;
	}

	hlm_migrate_refresh_pattern_pages();
	hlm_migrate_refresh_service_pages();
	hlm_migrate_refresh_blog_posts();
	hlm_migrate_refresh_page_seo_meta();
	hlm_migrate_sweep_stored_dashes();

	update_option( 'hlm_normalize_em_dashes_v2', 1, false );
}
add_action( 'init', 'hlm_migrate_normalize_em_dashes_v2', 39 );

/**
 * WP-CLI: wp hlm normalize-dashes
 *
 * @param array $args       Positional args.
 * @param array $assoc_args Associative args.
 */
function hlm_cli_normalize_dashes( $args, $assoc_args ) {
	delete_option( 'hlm_normalize_dashes_v1' );
	hlm_migrate_normalize_dashes();

	WP_CLI::success( 'Page and post content refreshed; em dashes normalized (en dashes preserved).' );
}

if ( defined( 'WP_CLI' ) && WP_CLI ) {
	WP_CLI::add_command( 'hlm normalize-dashes', 'hlm_cli_normalize_dashes' );
}
