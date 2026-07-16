<?php
/**
 * Kadence theme compatibility for HLM landing pages.
 *
 * @package HLM_Kadence
 */

defined( 'ABSPATH' ) || exit;

/**
 * Whether the current singular page uses any HLM block.
 *
 * @param WP_Post|null $post Optional post object.
 * @return bool
 */
function hlm_kadence_has_hlm_blocks( $post = null ) {
	if ( null === $post ) {
		if ( ! is_singular( 'page' ) ) {
			return false;
		}

		$post = get_queried_object();
	}

	if ( ! $post instanceof WP_Post ) {
		return false;
	}

	if ( has_block( 'hlm/hero', $post ) || has_block( 'acf/hlm-hero', $post ) ) {
		return true;
	}

	if ( ! has_blocks( $post->post_content ) ) {
		return false;
	}

	$blocks = parse_blocks( $post->post_content );
	foreach ( $blocks as $block ) {
		if ( empty( $block['blockName'] ) ) {
			continue;
		}
		if ( str_starts_with( $block['blockName'], 'hlm/' ) || str_starts_with( $block['blockName'], 'acf/hlm-' ) ) {
			return true;
		}
	}

	return false;
}

/**
 * Whether the current singular page is a full-width HLM landing page.
 *
 * @return bool
 */
function hlm_kadence_is_landing_page() {
	if ( ! is_singular( 'page' ) ) {
		return false;
	}

	$post = get_queried_object();
	if ( ! $post instanceof WP_Post ) {
		return false;
	}

	return has_block( 'hlm/hero', $post ) || has_block( 'acf/hlm-hero', $post );
}

/**
 * Apply Kadence per-page layout settings for HLM landing pages.
 *
 * @param int $post_id Post ID.
 */
function hlm_kadence_apply_landing_meta( $post_id ) {
	update_post_meta( $post_id, '_kad_post_layout', 'fullwidth' );
	update_post_meta( $post_id, '_kad_post_content_style', 'unboxed' );
	update_post_meta( $post_id, '_kad_post_vertical_padding', 'hide' );
	update_post_meta( $post_id, '_kad_post_title', 'hide' );
	update_post_meta( $post_id, '_kad_post_transparent', 'enable' );
}

/**
 * Apply full-width layout settings for HLM inner pages.
 *
 * @param int $post_id Post ID.
 */
function hlm_kadence_apply_inner_page_meta( $post_id ) {
	update_post_meta( $post_id, '_kad_post_layout', 'fullwidth' );
	update_post_meta( $post_id, '_kad_post_content_style', 'unboxed' );
	update_post_meta( $post_id, '_kad_post_vertical_padding', 'hide' );
	update_post_meta( $post_id, '_kad_post_title', 'hide' );
	update_post_meta( $post_id, '_kad_post_transparent', 'default' );
}

/**
 * Configure the front page and any page that contains HLM hero blocks.
 *
 * @param int $post_id Post ID.
 */
function hlm_kadence_maybe_apply_landing_meta( $post_id ) {
	if ( wp_is_post_revision( $post_id ) || wp_is_post_autosave( $post_id ) ) {
		return;
	}

	if ( 'page' !== get_post_type( $post_id ) ) {
		return;
	}

	if ( has_block( 'hlm/hero', $post_id ) || has_block( 'acf/hlm-hero', $post_id ) ) {
		hlm_kadence_apply_landing_meta( $post_id );
		return;
	}

	if ( hlm_kadence_has_hlm_blocks( get_post( $post_id ) ) ) {
		hlm_kadence_apply_inner_page_meta( $post_id );
	}
}
add_action( 'save_post', 'hlm_kadence_maybe_apply_landing_meta' );

/**
 * Whether the current view is the posts index or a post archive.
 *
 * @return bool
 */
function hlm_kadence_is_blog_view() {
	if ( is_singular( 'post' ) ) {
		return false;
	}

	return is_home() || is_category() || is_tag() || is_author() || is_date() || is_post_type_archive( 'post' );
}

/**
 * Whether the current view should use the HLM theme shell (header, tokens, typography).
 *
 * @return bool
 */
function hlm_kadence_is_theme_shell() {
	if ( hlm_kadence_has_hlm_blocks() ) {
		return true;
	}

	if ( hlm_kadence_is_blog_view() || is_singular( 'post' ) ) {
		return true;
	}

	if ( function_exists( 'hlm_kadence_is_service_detail_page' ) && hlm_kadence_is_service_detail_page() ) {
		return true;
	}

	return false;
}

/**
 * Add body classes for HLM pages and blog views.
 *
 * @param array $classes Body classes.
 * @return array
 */
function hlm_kadence_body_class( $classes ) {
	if ( hlm_kadence_is_theme_shell() ) {
		$classes[] = 'hlm-theme';
	}

	if ( hlm_kadence_is_landing_page() ) {
		$classes[] = 'hlm-landing-page';
	}

	if ( hlm_kadence_is_blog_view() ) {
		$classes[] = 'hlm-blog';
	}

	if ( is_singular( 'post' ) ) {
		$classes[] = 'hlm-single-post';
	}

	if ( function_exists( 'hlm_kadence_is_service_detail_page' ) && hlm_kadence_is_service_detail_page() ) {
		$classes[] = 'hlm-single-service';
	}

	return $classes;
}
add_filter( 'body_class', 'hlm_kadence_body_class' );

/**
 * One-time bootstrap for existing landing pages.
 */
function hlm_kadence_bootstrap_landing_pages() {
	if ( get_option( 'hlm_kadence_landing_meta_v1' ) ) {
		return;
	}

	$front_id = (int) get_option( 'page_on_front' );
	if ( $front_id ) {
		hlm_kadence_apply_landing_meta( $front_id );
	}

	$pages = get_posts(
		array(
			'post_type'      => 'page',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'fields'         => 'ids',
		)
	);

	foreach ( $pages as $page_id ) {
		if ( has_block( 'acf/hlm-hero', $page_id ) ) {
			hlm_kadence_apply_landing_meta( $page_id );
			continue;
		}

		if ( hlm_kadence_has_hlm_blocks( get_post( $page_id ) ) ) {
			hlm_kadence_apply_inner_page_meta( $page_id );
		}
	}

	update_option( 'hlm_kadence_landing_meta_v1', 1, false );
}
add_action( 'init', 'hlm_kadence_bootstrap_landing_pages', 20 );

/**
 * Hide Kadence page titles on existing HLM inner pages.
 */
function hlm_kadence_bootstrap_inner_pages() {
	if ( get_option( 'hlm_kadence_inner_meta_v1' ) ) {
		return;
	}

	$pages = get_posts(
		array(
			'post_type'      => 'page',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'fields'         => 'ids',
		)
	);

	foreach ( $pages as $page_id ) {
		if ( has_block( 'hlm/hero', $page_id ) || has_block( 'acf/hlm-hero', $page_id ) ) {
			continue;
		}

		if ( hlm_kadence_has_hlm_blocks( get_post( $page_id ) ) ) {
			hlm_kadence_apply_inner_page_meta( $page_id );
		}
	}

	update_option( 'hlm_kadence_inner_meta_v1', 1, false );
}
add_action( 'init', 'hlm_kadence_bootstrap_inner_pages', 21 );

/**
 * Remove Kadence footer strip on HLM landing pages.
 *
 * @param string $content Footer HTML content.
 * @return string
 */
function hlm_kadence_footer_html( $content ) {
	if ( hlm_kadence_is_landing_page() ) {
		return '';
	}

	return '&copy; {year} {site-title}. All rights reserved.';
}
add_filter( 'theme_mod_footer_html_content', 'hlm_kadence_footer_html' );

/**
 * Apply Kadence branding defaults once.
 */
function hlm_kadence_setup_branding() {
	if ( get_option( 'hlm_kadence_branding_v4' ) ) {
		return;
	}

	set_theme_mod( 'footer_html_content', '' );

	update_option(
		'blogdescription',
		get_option( 'blogdescription' ) ?: 'Helping colleges find students'
	);

	$hlm_palette = wp_json_encode(
		array(
			'palette'         => array(
				array( 'color' => '#4A90D9', 'slug' => 'palette1', 'name' => 'Sky Blue' ),
				array( 'color' => '#D4AF37', 'slug' => 'palette2', 'name' => 'Gold Accent' ),
				array( 'color' => '#2C2C2C', 'slug' => 'palette3', 'name' => 'Charcoal' ),
				array( 'color' => '#374151', 'slug' => 'palette4', 'name' => 'Strong Text' ),
				array( 'color' => '#6B7280', 'slug' => 'palette5', 'name' => 'Medium Text' ),
				array( 'color' => '#9CA3AF', 'slug' => 'palette6', 'name' => 'Subtle Text' ),
				array( 'color' => '#F5F5F5', 'slug' => 'palette7', 'name' => 'Off-White' ),
				array( 'color' => '#FFFFFF', 'slug' => 'palette8', 'name' => 'White' ),
				array( 'color' => '#FFFFFF', 'slug' => 'palette9', 'name' => 'Surface' ),
				array( 'color' => '#003366', 'slug' => 'palette10', 'name' => 'Academic Blue' ),
				array( 'color' => '#13612e', 'slug' => 'palette11', 'name' => 'Success' ),
				array( 'color' => '#4A90D9', 'slug' => 'palette12', 'name' => 'Info' ),
				array( 'color' => '#b82105', 'slug' => 'palette13', 'name' => 'Alert' ),
				array( 'color' => '#f7630c', 'slug' => 'palette14', 'name' => 'Warning' ),
				array( 'color' => '#D4AF37', 'slug' => 'palette15', 'name' => 'Rating' ),
			),
			'second-palette'  => array(),
			'third-palette'   => array(),
			'active'          => 'palette',
		)
	);

	update_option( 'kadence_global_palette', $hlm_palette, false );

	update_option( 'hlm_kadence_branding_v4', 1, false );
}
add_action( 'after_setup_theme', 'hlm_kadence_setup_branding', 30 );

/**
 * One-time tagline refresh for existing installs.
 */
function hlm_kadence_bootstrap_tagline() {
	if ( get_option( 'hlm_kadence_tagline_v1' ) ) {
		return;
	}

	$current = (string) get_option( 'blogdescription' );
	if ( '' === $current || 'Education Lead Generation' === $current ) {
		update_option( 'blogdescription', 'Helping colleges find students' );
	}

	update_option( 'hlm_kadence_tagline_v1', 1, false );
}
add_action( 'after_setup_theme', 'hlm_kadence_bootstrap_tagline', 31 );

/**
 * Create or update the primary navigation menu.
 *
 * @param bool $force Recreate menu links even if menu already exists.
 */
function hlm_kadence_setup_primary_menu( $force = false ) {
	if ( ! $force && get_option( 'hlm_kadence_menu_v3' ) ) {
		return;
	}

	if ( ! $force && get_option( 'hlm_kadence_menu_v2' ) && ! get_option( 'hlm_kadence_menu_v3' ) ) {
		$force = true;
	}

	$menu_name = 'HLM Primary';
	$menu      = wp_get_nav_menu_object( $menu_name );

	if ( ! $menu ) {
		$menu_id = wp_create_nav_menu( $menu_name );
	} else {
		$menu_id = (int) $menu->term_id;
	}

	if ( is_wp_error( $menu_id ) || ! $menu_id ) {
		return;
	}

	if ( $force ) {
		$existing_items = wp_get_nav_menu_items( $menu_id );
		if ( $existing_items ) {
			foreach ( $existing_items as $item ) {
				wp_delete_post( (int) $item->ID, true );
			}
		}
	}

	$items = array(
		array( 'title' => 'About Us', 'url' => home_url( '/about-us/' ) ),
		array( 'title' => 'Services', 'url' => home_url( '/services/' ) ),
		array( 'title' => 'Affiliations', 'url' => home_url( '/affiliations/' ) ),
		array( 'title' => 'Blog', 'url' => home_url( '/blog/' ) ),
	);

	$existing_titles = array();
	if ( ! $force ) {
		$existing = wp_get_nav_menu_items( $menu_id );
		if ( $existing ) {
			foreach ( $existing as $item ) {
				$existing_titles[] = $item->title;
			}
		}
	}

	foreach ( $items as $index => $item ) {
		if ( ! $force && in_array( $item['title'], $existing_titles, true ) ) {
			continue;
		}

		wp_update_nav_menu_item(
			$menu_id,
			0,
			array(
				'menu-item-title'     => $item['title'],
				'menu-item-url'       => $item['url'],
				'menu-item-status'    => 'publish',
				'menu-item-type'      => 'custom',
				'menu-item-position'  => $index + 1,
				'menu-item-classes'   => $item['classes'] ?? '',
			)
		);
	}

	$locations            = get_theme_mod( 'nav_menu_locations', array() );
	$locations['primary'] = $menu_id;
	$locations['mobile']  = $menu_id;
	set_theme_mod( 'nav_menu_locations', $locations );

	update_option( 'hlm_kadence_menu_v3', 1, false );
}
add_action( 'init', 'hlm_kadence_setup_primary_menu', 25 );

/**
 * One-time rebuild: nav links only (CTA lives in the header button).
 */
function hlm_kadence_bootstrap_header_nav() {
	if ( get_option( 'hlm_kadence_header_nav_v1' ) ) {
		return;
	}

	hlm_kadence_setup_primary_menu( true );
	update_option( 'hlm_kadence_header_nav_v1', 1, false );
}
add_action( 'init', 'hlm_kadence_bootstrap_header_nav', 24 );

/**
 * Replace Kadence header and footer with HLM shell on theme pages.
 */
function hlm_kadence_setup_site_shell() {
	if ( ! hlm_kadence_is_theme_shell() ) {
		return;
	}

	remove_action( 'kadence_header', 'Kadence\header_markup' );
	add_action( 'kadence_header', 'hlm_render_site_header' );

	remove_action( 'kadence_footer', 'Kadence\footer_markup' );
	add_action( 'kadence_footer', 'hlm_render_site_footer' );
}
add_action( 'wp', 'hlm_kadence_setup_site_shell' );
