<?php
/**
 * HLM block integration for the Kadence-based active theme.
 *
 * @package HLM_Kadence
 */

defined( 'ABSPATH' ) || exit;

define( 'HLM_KADENCE_VERSION', '1.2.5' );
define( 'HLM_KADENCE_DIR', get_template_directory() );
define( 'HLM_KADENCE_URI', get_template_directory_uri() );

require_once HLM_KADENCE_DIR . '/inc/hlm/icons.php';
require_once HLM_KADENCE_DIR . '/inc/hlm/media.php';
require_once HLM_KADENCE_DIR . '/inc/hlm/media-catalog.php';
require_once HLM_KADENCE_DIR . '/inc/hlm/theme-json.php';
require_once HLM_KADENCE_DIR . '/inc/hlm/theme-switcher.php';
require_once HLM_KADENCE_DIR . '/inc/hlm/enqueue.php';
require_once HLM_KADENCE_DIR . '/inc/hlm/gutenberg-blocks.php';
require_once HLM_KADENCE_DIR . '/inc/hlm/patterns.php';
require_once HLM_KADENCE_DIR . '/inc/hlm/setup-pages.php';
require_once HLM_KADENCE_DIR . '/inc/hlm/kadence-compat.php';
require_once HLM_KADENCE_DIR . '/inc/hlm/blog.php';
require_once HLM_KADENCE_DIR . '/inc/hlm/services.php';
require_once HLM_KADENCE_DIR . '/inc/hlm/site-header.php';
require_once HLM_KADENCE_DIR . '/inc/hlm/site-footer.php';
require_once HLM_KADENCE_DIR . '/inc/hlm/site-icon.php';
require_once HLM_KADENCE_DIR . '/inc/hlm/hlm-privacy-policy.php';
require_once HLM_KADENCE_DIR . '/inc/hlm/blog-article-markup.php';
require_once HLM_KADENCE_DIR . '/inc/hlm/service-detail-content.php';
require_once HLM_KADENCE_DIR . '/inc/hlm/blog-article-builders.php';
require_once HLM_KADENCE_DIR . '/inc/hlm/articles/fafsa-mistakes-content.php';
require_once HLM_KADENCE_DIR . '/inc/hlm/articles/nursing-program-content.php';
require_once HLM_KADENCE_DIR . '/inc/hlm/articles/berkeley-college-content.php';
require_once HLM_KADENCE_DIR . '/inc/hlm/articles/online-vs-campus-nursing-content.php';
require_once HLM_KADENCE_DIR . '/inc/hlm/block-content-audit.php';
require_once HLM_KADENCE_DIR . '/inc/hlm/hlm-seo-blog-content.php';
require_once HLM_KADENCE_DIR . '/inc/hlm/hlm-seo-bridge.php';
require_once HLM_KADENCE_DIR . '/inc/hlm/migrate-normalize-dashes.php';

/**
 * Theme setup for HLM blocks and editor styles.
 */
function hlm_kadence_setup() {
	add_theme_support( 'custom-logo', array(
		'height'      => 42,
		'width'       => 200,
		'flex-height' => true,
		'flex-width'  => true,
	) );
	add_theme_support( 'post-thumbnails' );
	add_image_size( 'hlm-blog-card', 768, 432, true );
	add_image_size( 'hlm-blog-og', 1200, 630, true );
	add_theme_support( 'wp-block-styles' );
	add_theme_support( 'editor-styles' );
	add_editor_style( 'assets/hlm/hlm-blocks.css' );
	add_editor_style( 'assets/hlm/editor.css' );
}
add_action( 'after_setup_theme', 'hlm_kadence_setup' );

/**
 * Allow SVG uploads for site logos and partner branding assets.
 *
 * @param array $mimes Allowed upload mime types.
 * @return array
 */
function hlm_kadence_upload_mimes( $mimes ) {
	$mimes['svg']  = 'image/svg+xml';
	$mimes['svgz'] = 'image/svg+xml';

	return $mimes;
}
add_filter( 'upload_mimes', 'hlm_kadence_upload_mimes' );

/**
 * Fix SVG mime detection for the media library.
 *
 * @param array  $data     File data.
 * @param string $file     Full path to the file.
 * @param string $filename File name.
 * @param array  $mimes    Allowed mime types.
 * @return array
 */
function hlm_kadence_fix_svg_mime_type( $data, $file, $filename, $mimes ) {
	if ( ! empty( $data['ext'] ) && ! empty( $data['type'] ) ) {
		return $data;
	}

	$checked = wp_check_filetype( $filename, $mimes );
	if ( empty( $checked['ext'] ) || 'svg' !== $checked['ext'] ) {
		return $data;
	}

	return array(
		'ext'             => 'svg',
		'type'            => 'image/svg+xml',
		'proper_filename' => $data['proper_filename'] ?? false,
	);
}
add_filter( 'wp_check_filetype_and_ext', 'hlm_kadence_fix_svg_mime_type', 10, 4 );
