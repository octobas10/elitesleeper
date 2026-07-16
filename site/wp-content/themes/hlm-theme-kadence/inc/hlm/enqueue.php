<?php
/**
 * HLM asset enqueueing.
 *
 * @package HLM_Kadence
 */

defined( 'ABSPATH' ) || exit;

/**
 * Resolve stylesheet dependencies after WordPress global styles.
 *
 * @return array<int, string>
 */
function hlm_kadence_token_style_deps() {
	$deps = array();

	if ( wp_style_is( 'global-styles', 'registered' ) ) {
		$deps[] = 'global-styles';
	}

	return $deps;
}

/**
 * Enqueue front-end assets for HLM blocks.
 */
function hlm_kadence_enqueue_assets() {
	wp_enqueue_style(
		'hlm-google-fonts',
		'https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700&family=Inter:wght@400;500;600;700&family=Playfair+Display:ital,wght@0,400;0,500;1,400;1,500&display=swap',
		array(),
		null
	);

	wp_enqueue_style(
		'hlm-tokens',
		HLM_KADENCE_URI . '/assets/hlm/hlm-tokens.css',
		hlm_kadence_token_style_deps(),
		HLM_KADENCE_VERSION
	);

	wp_enqueue_style(
		'hlm-blocks',
		HLM_KADENCE_URI . '/assets/hlm/hlm-blocks.css',
		array( 'hlm-google-fonts', 'hlm-tokens' ),
		HLM_KADENCE_VERSION
	);

	wp_enqueue_style(
		'hlm-blocks-theme',
		HLM_KADENCE_URI . '/assets/hlm/hlm-blocks-theme.css',
		array( 'hlm-blocks' ),
		HLM_KADENCE_VERSION
	);

	wp_enqueue_style(
		'hlm-motion',
		HLM_KADENCE_URI . '/assets/hlm/hlm-motion.css',
		array( 'hlm-blocks-theme' ),
		HLM_KADENCE_VERSION
	);

	wp_enqueue_style(
		'hlm-theme-ui',
		HLM_KADENCE_URI . '/assets/hlm/hlm-theme-ui.css',
		array( 'hlm-tokens' ),
		HLM_KADENCE_VERSION
	);

	wp_enqueue_style(
		'hlm-kadence',
		HLM_KADENCE_URI . '/assets/hlm/hlm-kadence.css',
		array( 'hlm-motion', 'hlm-theme-ui' ),
		HLM_KADENCE_VERSION
	);

	wp_enqueue_style(
		'hlm-header',
		HLM_KADENCE_URI . '/assets/hlm/hlm-header.css',
		array( 'hlm-kadence' ),
		HLM_KADENCE_VERSION
	);

	wp_enqueue_style(
		'hlm-page-polish',
		HLM_KADENCE_URI . '/assets/hlm/hlm-page-polish.css',
		array( 'hlm-header' ),
		HLM_KADENCE_VERSION
	);

	wp_enqueue_style(
		'hlm-blog',
		HLM_KADENCE_URI . '/assets/hlm/hlm-blog.css',
		array( 'hlm-page-polish' ),
		HLM_KADENCE_VERSION
	);

	wp_enqueue_style(
		'hlm-surfaces',
		HLM_KADENCE_URI . '/assets/hlm/hlm-surfaces.css',
		array( 'hlm-blog' ),
		HLM_KADENCE_VERSION
	);

	wp_enqueue_script(
		'hlm-animations',
		HLM_KADENCE_URI . '/assets/hlm/hlm-animations.js',
		array(),
		HLM_KADENCE_VERSION,
		true
	);
}
add_action( 'wp_enqueue_scripts', 'hlm_kadence_enqueue_assets', 20 );

/**
 * Enqueue block editor assets.
 */
function hlm_kadence_enqueue_block_editor_assets() {
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
		'hlm-editor',
		HLM_KADENCE_URI . '/assets/hlm/editor.css',
		array( 'hlm-tokens-editor' ),
		HLM_KADENCE_VERSION
	);

	wp_enqueue_style(
		'hlm-blocks-theme-editor',
		HLM_KADENCE_URI . '/assets/hlm/hlm-blocks-theme.css',
		array( 'hlm-editor' ),
		HLM_KADENCE_VERSION
	);

	wp_enqueue_style(
		'hlm-motion-editor',
		HLM_KADENCE_URI . '/assets/hlm/hlm-motion.css',
		array( 'hlm-blocks-theme-editor' ),
		HLM_KADENCE_VERSION
	);

	wp_enqueue_style(
		'hlm-surfaces-editor',
		HLM_KADENCE_URI . '/assets/hlm/hlm-surfaces.css',
		array( 'hlm-motion-editor' ),
		HLM_KADENCE_VERSION
	);
}
add_action( 'enqueue_block_editor_assets', 'hlm_kadence_enqueue_block_editor_assets' );
