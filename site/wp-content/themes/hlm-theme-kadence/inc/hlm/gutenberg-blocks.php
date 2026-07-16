<?php
/**
 * Native Gutenberg block registration for HLM.
 *
 * @package HLM_Kadence
 */

defined( 'ABSPATH' ) || exit;

require_once HLM_KADENCE_DIR . '/inc/hlm/block-helpers.php';

/**
 * Register custom block category.
 *
 * @param array $categories Block categories.
 * @return array
 */
function hlm_gutenberg_block_categories( $categories ) {
	return array_merge(
		array(
			array(
				'slug'  => 'hlm-blocks',
				'title' => __( 'HLM Blocks', 'hlm-kadence' ),
				'icon'  => 'star-filled',
			),
		),
		$categories
	);
}
add_filter( 'block_categories_all', 'hlm_gutenberg_block_categories', 10, 1 );

/**
 * Register editor script and all HLM blocks from block.json metadata.
 */
function hlm_register_gutenberg_blocks() {
	$asset_file = HLM_KADENCE_DIR . '/gutenberg/build/index.asset.php';

	if ( ! file_exists( $asset_file ) ) {
		return;
	}

	$asset = include $asset_file;

	wp_register_script(
		'hlm-blocks-editor',
		HLM_KADENCE_URI . '/gutenberg/build/index.js',
		$asset['dependencies'],
		$asset['version'],
		true
	);

	wp_localize_script(
		'hlm-blocks-editor',
		'hlmThemeMedia',
		array(
			'demoHeroVideo'       => function_exists( 'hlm_default_hero_video_ref' ) ? hlm_default_hero_video_ref() : '',
			'demoHeroVideoUrl'    => function_exists( 'hlm_resolve_media_url' ) ? hlm_resolve_media_url( hlm_default_hero_video_ref() ) : '',
			'demoHeroVideoLabel'  => __( 'Use demo campus video', 'hlm-kadence' ),
			'demoHeroPosterUrl'   => 'https://images.unsplash.com/photo-1523240795612-9a054b0db644?w=1920&h=1080&fit=crop',
			'placeholderLabel'    => HLM_HERO_VIDEO_PLACEHOLDER_LABEL,
		)
	);

	$blocks = array(
		'hlm-hero',
		'hlm-page-hero',
		'hlm-stats',
		'hlm-universities',
		'hlm-programs',
		'hlm-process',
		'hlm-testimonials',
		'hlm-blog',
		'hlm-cta',
		'hlm-services',
		'hlm-team',
		'hlm-overview',
		'hlm-why-us',
		'hlm-contact',
		'hlm-affiliation',
		'hlm-partner-showcase',
		'hlm-partners',
		'hlm-timeline',
		'hlm-policy',
		'hlm-article-mistake',
		'hlm-article-checklist',
		'hlm-article-callout',
		'hlm-article-compare',
	);

	foreach ( $blocks as $block_slug ) {
		$block_dir = HLM_KADENCE_DIR . '/blocks/' . $block_slug;

		if ( ! file_exists( $block_dir . '/block.json' ) ) {
			continue;
		}

		register_block_type(
			$block_dir,
			array(
				'editor_script' => 'hlm-blocks-editor',
			)
		);
	}
}
add_action( 'init', 'hlm_register_gutenberg_blocks' );

/**
 * One-time migration: replace legacy ACF block names in page content.
 */
function hlm_migrate_acf_blocks_to_gutenberg() {
	if ( get_option( 'hlm_gutenberg_block_migration_v1' ) ) {
		return;
	}

	$pages = get_posts(
		array(
			'post_type'      => array( 'page', 'wp_block' ),
			'post_status'    => array( 'publish', 'draft', 'private' ),
			'posts_per_page' => -1,
		)
	);

	foreach ( $pages as $page ) {
		if ( ! str_contains( $page->post_content, 'acf/hlm-' ) ) {
			continue;
		}

		$content = preg_replace(
			'#<!-- wp:acf/hlm-([\w-]+)(?:\{[^}]*\})? /-->\s*#',
			'<!-- wp:hlm/$1 /-->' . "\n\n",
			$page->post_content
		);

		if ( $content !== $page->post_content ) {
			wp_update_post(
				array(
					'ID'           => $page->ID,
					'post_content' => $content,
				)
			);
		}
	}

	update_option( 'hlm_gutenberg_block_migration_v1', 1, false );
}
add_action( 'init', 'hlm_migrate_acf_blocks_to_gutenberg', 5 );

/**
 * Add blog block to existing home page if missing.
 */
function hlm_migrate_home_blog_block() {
	if ( get_option( 'hlm_home_blog_block_v1' ) ) {
		return;
	}

	$front_id = (int) get_option( 'page_on_front' );
	if ( ! $front_id ) {
		update_option( 'hlm_home_blog_block_v1', 1, false );
		return;
	}

	$post = get_post( $front_id );
	if ( ! $post instanceof WP_Post ) {
		update_option( 'hlm_home_blog_block_v1', 1, false );
		return;
	}

	if ( ! str_contains( $post->post_content, 'hlm/blog' ) && str_contains( $post->post_content, 'hlm/cta' ) ) {
		$blog_block = "<!-- wp:hlm/blog /-->\n\n";
		$content    = str_replace( '<!-- wp:hlm/cta', $blog_block . '<!-- wp:hlm/cta', $post->post_content );

		wp_update_post(
			array(
				'ID'           => $front_id,
				'post_content' => $content,
			)
		);
	}

	update_option( 'hlm_home_blog_block_v1', 1, false );
}
add_action( 'init', 'hlm_migrate_home_blog_block', 6 );

/**
 * Refresh affiliations page from the full layout pattern.
 */
function hlm_migrate_affiliations_page() {
	if ( get_option( 'hlm_affiliations_page_v2' ) ) {
		return;
	}

	$page = get_page_by_path( 'affiliations' );
	if ( $page instanceof WP_Post && function_exists( 'hlm_get_pattern_markup' ) ) {
		$content = hlm_get_pattern_markup( 'affiliations-page.php' );
		if ( $content ) {
			wp_update_post(
				array(
					'ID'           => $page->ID,
					'post_content' => $content,
				)
			);

			if ( function_exists( 'hlm_kadence_apply_inner_page_meta' ) ) {
				hlm_kadence_apply_inner_page_meta( $page->ID );
			}
		}
	}

	update_option( 'hlm_affiliations_page_v2', 1, false );
}
add_action( 'init', 'hlm_migrate_affiliations_page', 6 );

/**
 * Refresh inner pages from full layout patterns (About, Services, Contact, Privacy).
 */
function hlm_migrate_inner_pages_v3() {
	if ( get_option( 'hlm_inner_pages_v3' ) ) {
		return;
	}

	if ( ! function_exists( 'hlm_get_pattern_markup' ) ) {
		update_option( 'hlm_inner_pages_v3', 1, false );
		return;
	}

	$page_patterns = array(
		'about-us'       => 'about-us-page.php',
		'services'       => 'services-page.php',
		'contact-us'     => 'contact-page.php',
		'privacy-policy' => 'privacy-page.php',
	);

	foreach ( $page_patterns as $slug => $pattern_file ) {
		$page = get_page_by_path( $slug );
		if ( ! $page instanceof WP_Post ) {
			continue;
		}

		$content = hlm_get_pattern_markup( $pattern_file );
		if ( ! $content ) {
			continue;
		}

		wp_update_post(
			array(
				'ID'           => $page->ID,
				'post_content' => $content,
			)
		);

		if ( function_exists( 'hlm_kadence_apply_inner_page_meta' ) ) {
			hlm_kadence_apply_inner_page_meta( $page->ID );
		}
	}

	update_option( 'hlm_inner_pages_v3', 1, false );
}
add_action( 'init', 'hlm_migrate_inner_pages_v3', 6 );

/**
 * Refresh About Us page layout (story variant, light timeline).
 */
function hlm_migrate_about_page_v4() {
	if ( get_option( 'hlm_about_page_v4' ) ) {
		return;
	}

	$page = get_page_by_path( 'about-us' );
	if ( $page instanceof WP_Post && function_exists( 'hlm_get_pattern_markup' ) ) {
		$content = hlm_get_pattern_markup( 'about-us-page.php' );
		if ( $content ) {
			wp_update_post(
				array(
					'ID'           => $page->ID,
					'post_content' => $content,
				)
			);
		}
	}

	update_option( 'hlm_about_page_v4', 1, false );
}
add_action( 'init', 'hlm_migrate_about_page_v4', 6 );

/**
 * Refresh home page from the full landing layout pattern.
 */
function hlm_migrate_home_page_v2() {
	if ( get_option( 'hlm_home_page_v2' ) ) {
		return;
	}

	$front_id = (int) get_option( 'page_on_front' );
	if ( ! $front_id ) {
		update_option( 'hlm_home_page_v2', 1, false );
		return;
	}

	if ( function_exists( 'hlm_get_pattern_markup' ) ) {
		$content = hlm_get_pattern_markup( 'home-page.php' );
		if ( $content ) {
			wp_update_post(
				array(
					'ID'           => $front_id,
					'post_content' => $content,
				)
			);
		}
	}

	if ( function_exists( 'hlm_kadence_apply_landing_meta' ) ) {
		hlm_kadence_apply_landing_meta( $front_id );
	}

	update_option( 'hlm_home_page_v2', 1, false );
}
add_action( 'init', 'hlm_migrate_home_page_v2', 6 );

/**
 * Switch home page to light hero variant.
 */
function hlm_migrate_home_page_v3() {
	if ( get_option( 'hlm_home_page_v3' ) ) {
		return;
	}

	$front_id = (int) get_option( 'page_on_front' );
	if ( $front_id && function_exists( 'hlm_get_pattern_markup' ) ) {
		$content = hlm_get_pattern_markup( 'home-page.php' );
		if ( $content ) {
			wp_update_post(
				array(
					'ID'           => $front_id,
					'post_content' => $content,
				)
			);
		}
	}

	update_option( 'hlm_home_page_v3', 1, false );
}
add_action( 'init', 'hlm_migrate_home_page_v3', 6 );

/**
 * Home process section — core Columns inside hlm/process (InnerBlocks pilot).
 */
function hlm_migrate_home_page_v4() {
	if ( get_option( 'hlm_home_page_v4' ) ) {
		return;
	}

	$front_id = (int) get_option( 'page_on_front' );
	if ( $front_id && function_exists( 'hlm_get_pattern_markup' ) ) {
		$content = hlm_get_pattern_markup( 'home-page.php' );
		if ( $content ) {
			wp_update_post(
				array(
					'ID'           => $front_id,
					'post_content' => $content,
				)
			);
		}
	}

	update_option( 'hlm_home_page_v4', 1, false );
}
add_action( 'init', 'hlm_migrate_home_page_v4', 6 );

/**
 * Restore home process section to structured steps layout (fixes broken columns grid).
 */
function hlm_migrate_home_page_v5() {
	if ( get_option( 'hlm_home_page_v5' ) ) {
		return;
	}

	$front_id = (int) get_option( 'page_on_front' );
	if ( $front_id && function_exists( 'hlm_get_pattern_markup' ) ) {
		$content = hlm_get_pattern_markup( 'home-page.php' );
		if ( $content ) {
			wp_update_post(
				array(
					'ID'           => $front_id,
					'post_content' => function_exists( 'hlm_seo_normalize_dashes' )
						? hlm_seo_normalize_dashes( $content )
						: $content,
				)
			);
		}
	}

	update_option( 'hlm_home_page_v5', 1, false );
}
add_action( 'init', 'hlm_migrate_home_page_v5', 6 );

/**
 * Enqueue block styles in the editor.
 */
function hlm_enqueue_gutenberg_editor_styles() {
	wp_enqueue_style(
		'hlm-google-fonts-editor',
		'https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700&family=Inter:wght@400;500;600;700&family=Playfair+Display:ital,wght@0,400;0,500;1,400;1,500&display=swap',
		array(),
		null
	);

	wp_enqueue_style(
		'hlm-tokens-editor',
		HLM_KADENCE_URI . '/assets/hlm/hlm-tokens.css',
		array( 'hlm-google-fonts-editor' ),
		HLM_KADENCE_VERSION
	);

	wp_enqueue_style(
		'hlm-blocks-editor',
		HLM_KADENCE_URI . '/assets/hlm/hlm-blocks.css',
		array( 'hlm-tokens-editor' ),
		HLM_KADENCE_VERSION
	);

	wp_enqueue_style(
		'hlm-blog-editor',
		HLM_KADENCE_URI . '/assets/hlm/hlm-blog.css',
		array( 'hlm-blocks-editor' ),
		HLM_KADENCE_VERSION
	);

	$lead_css = WP_PLUGIN_DIR . '/hlm-lead-capture/assets/hlm-lead-form.css';
	if ( file_exists( $lead_css ) ) {
		wp_enqueue_style(
			'hlm-lead-form-editor',
			plugins_url( 'hlm-lead-capture/assets/hlm-lead-form.css' ),
			array( 'hlm-blocks-editor' ),
			defined( 'HLM_LC_VERSION' ) ? HLM_LC_VERSION : HLM_KADENCE_VERSION
		);
	}
}
add_action( 'enqueue_block_editor_assets', 'hlm_enqueue_gutenberg_editor_styles' );
