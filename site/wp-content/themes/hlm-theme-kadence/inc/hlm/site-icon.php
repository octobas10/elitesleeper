<?php
/**
 * HLM site icon (favicon) bootstrap.
 *
 * @package HLM_Kadence
 */

defined( 'ABSPATH' ) || exit;

/** Attachment meta key marking the bundled HLM site icon. */
define( 'HLM_SITE_ICON_META_KEY', '_hlm_bundled_site_icon' );

/**
 * Theme-relative path to the 512px site icon source file.
 *
 * @return string
 */
function hlm_kadence_site_icon_source_path() {
	return get_theme_file_path( 'assets/hlm/favicon/site-icon-512.png' );
}

/**
 * Import the bundled PNG and set WordPress site_icon when none is configured.
 *
 * @return int|false Attachment ID on success, false otherwise.
 */
function hlm_kadence_import_site_icon() {
	$source = hlm_kadence_site_icon_source_path();

	if ( ! is_readable( $source ) ) {
		return false;
	}

	$existing = get_posts(
		array(
			'post_type'              => 'attachment',
			'post_status'            => 'inherit',
			'posts_per_page'         => 1,
			'fields'                 => 'ids',
			'no_found_rows'          => true,
			'update_post_meta_cache' => false,
			'update_post_term_cache' => false,
			'meta_key'               => HLM_SITE_ICON_META_KEY,
			'meta_value'             => '1',
		)
	);

	if ( ! empty( $existing ) ) {
		$attach_id = (int) $existing[0];
		update_option( 'site_icon', $attach_id );

		return $attach_id;
	}

	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/image.php';
	require_once ABSPATH . 'wp-admin/includes/media.php';

	$upload = wp_upload_bits( 'hlm-site-icon.png', null, file_get_contents( $source ) );

	if ( ! empty( $upload['error'] ) ) {
		return false;
	}

	$filetype = wp_check_filetype( basename( $upload['file'] ), null );

	$attach_id = wp_insert_attachment(
		array(
			'post_mime_type' => $filetype['type'] ?: 'image/png',
			'post_title'     => 'HLM Site Icon',
			'post_content'   => '',
			'post_status'    => 'inherit',
		),
		$upload['file']
	);

	if ( is_wp_error( $attach_id ) || ! $attach_id ) {
		return false;
	}

	$metadata = wp_generate_attachment_metadata( $attach_id, $upload['file'] );
	wp_update_attachment_metadata( $attach_id, $metadata );
	update_post_meta( $attach_id, '_wp_attachment_image_alt', 'Higher Learning Marketers' );
	update_post_meta( $attach_id, HLM_SITE_ICON_META_KEY, '1' );
	update_option( 'site_icon', $attach_id );

	return $attach_id;
}

/**
 * Ensure a site icon exists after theme setup.
 */
function hlm_kadence_maybe_setup_site_icon() {
	if ( (int) get_option( 'site_icon' ) > 0 ) {
		return;
	}

	hlm_kadence_import_site_icon();
}
add_action( 'after_setup_theme', 'hlm_kadence_maybe_setup_site_icon', 30 );

/**
 * Output SVG favicon links until WordPress site_icon is configured.
 */
function hlm_kadence_fallback_favicon_links() {
	if ( has_site_icon() ) {
		return;
	}

	$svg = get_theme_file_uri( 'assets/hlm/favicon/site-icon.svg' );
	$png = get_theme_file_uri( 'assets/hlm/favicon/favicon-32.png' );

	if ( ! $svg || ! $png ) {
		return;
	}

	printf(
		'<link rel="icon" href="%1$s" type="image/svg+xml" />' . "\n",
		esc_url( $svg )
	);
	printf(
		'<link rel="icon" href="%1$s" sizes="32x32" type="image/png" />' . "\n",
		esc_url( $png )
	);
	printf(
		'<link rel="apple-touch-icon" href="%1$s" />' . "\n",
		esc_url( get_theme_file_uri( 'assets/hlm/favicon/site-icon-192.png' ) )
	);
}
add_action( 'wp_head', 'hlm_kadence_fallback_favicon_links', 1 );
