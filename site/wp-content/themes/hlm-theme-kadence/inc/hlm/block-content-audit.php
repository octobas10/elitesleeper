<?php
/**
 * Audit and repair marketing pages that lost native block markup.
 *
 * @package HLM_Kadence
 */

defined( 'ABSPATH' ) || exit;

/**
 * Marketing pages that must use HLM block patterns (not classic HTML).
 *
 * @return array<int, array<string, string>>
 */
function hlm_block_audit_page_registry(): array {
	return array(
		array( 'slug' => 'home', 'pattern' => 'home-page.php' ),
		array( 'slug' => 'about-us', 'pattern' => 'about-us-page.php' ),
		array( 'slug' => 'services', 'pattern' => 'services-page.php' ),
		array( 'slug' => 'affiliations', 'pattern' => 'affiliations-page.php' ),
		array( 'slug' => 'contact-us', 'pattern' => 'contact-page.php' ),
		array( 'slug' => 'privacy-policy', 'pattern' => 'privacy-page.php' ),
	);
}

/**
 * Re-apply pattern markup when a page is missing blocks or still uses legacy ACF names.
 *
 * @return array<string, int> Summary counts.
 */
function hlm_kadence_audit_block_content(): array {
	$summary = array(
		'pages_checked'  => 0,
		'pages_repaired' => 0,
		'posts_checked'  => 0,
		'posts_repaired' => 0,
	);

	if ( ! function_exists( 'hlm_get_pattern_markup' ) ) {
		return $summary;
	}

	foreach ( hlm_block_audit_page_registry() as $page ) {
		++$summary['pages_checked'];
		$existing = get_page_by_path( $page['slug'] );

		if ( ! $existing instanceof WP_Post ) {
			continue;
		}

		$needs_repair = ! has_blocks( $existing->post_content )
			|| str_contains( $existing->post_content, 'acf/hlm-' )
			|| ( '' === trim( $existing->post_content ) && empty( $page['allow_empty'] ?? false ) );

		if ( ! $needs_repair ) {
			continue;
		}

		$content = hlm_get_pattern_markup( $page['pattern'] );
		if ( '' === $content ) {
			continue;
		}

		wp_update_post(
			array(
				'ID'           => (int) $existing->ID,
				'post_content' => $content,
			)
		);
		++$summary['pages_repaired'];
	}

	if ( function_exists( 'hlm_service_catalog' ) && function_exists( 'hlm_services_parent_page_id' ) ) {
		$parent_id = hlm_services_parent_page_id();

		foreach ( hlm_service_catalog() as $slug => $service ) {
			++$summary['pages_checked'];
			$path     = 'services/' . $slug;
			$existing = get_page_by_path( $path );

			if ( ! $existing instanceof WP_Post || empty( $service['content'] ) ) {
				continue;
			}

			if ( has_blocks( $existing->post_content ) ) {
				continue;
			}

			wp_update_post(
				array(
					'ID'           => (int) $existing->ID,
					'post_content' => (string) $service['content'],
				)
			);
			++$summary['pages_repaired'];
		}
	}

	if ( function_exists( 'hlm_kadence_seo_blog_posts_content' ) && class_exists( 'HLM_SEO\Content_Bootstrap' ) ) {
		$posts = hlm_kadence_seo_blog_posts_content();

		foreach ( $posts as $slug => $data ) {
			++$summary['posts_checked'];
			$existing = get_page_by_path( $slug, OBJECT, 'post' );

			if ( ! $existing instanceof WP_Post ) {
				continue;
			}

			$content = (string) ( $data['content'] ?? '' );
			if ( '' === $content || ! has_blocks( $content ) ) {
				continue;
			}

			$existing_is_valid = has_blocks( $existing->post_content )
				&& ( ! class_exists( 'HLM_SEO\Content_Parser' ) || \HLM_SEO\Content_Parser::is_native_block_storage( $existing->post_content ) );

			if ( $existing_is_valid && empty( $data['force'] ) ) {
				$plain_length = function_exists( 'hlm_seo_get_plain_text' )
					? strlen( hlm_seo_get_plain_text( $existing->post_content, $existing ) )
					: strlen( wp_strip_all_tags( $existing->post_content ) );
				if ( $plain_length > 3500 ) {
					continue;
				}
			}

			if ( $existing_is_valid && $existing->post_content === $content ) {
				continue;
			}

			wp_update_post(
				array(
					'ID'           => (int) $existing->ID,
					'post_content' => function_exists( 'hlm_seo_normalize_dashes' )
						? hlm_seo_normalize_dashes( $content )
						: $content,
				)
			);
			++$summary['posts_repaired'];
		}
	}

	return $summary;
}

/**
 * Run one-time block content audit after cluster conversion.
 */
function hlm_kadence_run_block_content_audit_v1(): void {
	if ( get_option( 'hlm_block_content_audit_v1' ) ) {
		return;
	}

	hlm_kadence_audit_block_content();

	if ( class_exists( 'HLM_SEO\Content_Bootstrap' ) ) {
		do_action( 'hlm_seo_bootstrap_posts' );
	}

	update_option( 'hlm_block_content_audit_v1', 1, false );
}
add_action( 'init', 'hlm_kadence_run_block_content_audit_v1', 43 );

/**
 * Re-audit cluster posts for native block storage and SEO plugin compatibility.
 */
function hlm_kadence_run_block_content_audit_v2(): void {
	if ( get_option( 'hlm_block_content_audit_v2' ) ) {
		return;
	}

	if ( ! class_exists( 'HLM_SEO\Content_Parser' ) ) {
		return;
	}

	hlm_kadence_audit_block_content();

	if ( class_exists( 'HLM_SEO\Content_Bootstrap' ) ) {
		do_action( 'hlm_seo_bootstrap_posts' );
	}

	update_option( 'hlm_block_content_audit_v2', 1, false );
}
add_action( 'init', 'hlm_kadence_run_block_content_audit_v2', 44 );
