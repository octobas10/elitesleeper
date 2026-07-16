<?php
/**
 * One-time site page bootstrap from HLM block patterns.
 *
 * @package HLM_Kadence
 */

defined( 'ABSPATH' ) || exit;

/**
 * Extract block markup from a pattern PHP file.
 *
 * @param string $pattern_file Filename inside /patterns.
 * @return string
 */
function hlm_get_pattern_markup( $pattern_file ) {
	$path = HLM_KADENCE_DIR . '/patterns/' . $pattern_file;

	if ( ! file_exists( $path ) ) {
		return '';
	}

	if ( 'privacy-page.php' === $pattern_file ) {
		if ( ! function_exists( 'hlm_kadence_privacy_page_markup' ) ) {
			require_once HLM_KADENCE_DIR . '/inc/hlm/hlm-privacy-policy.php';
		}

		return hlm_kadence_privacy_page_markup();
	}

	$raw = file_get_contents( $path );

	if ( preg_match( '/\?>\s*(.*)\s*$/s', $raw, $matches ) ) {
		$markup = trim( $matches[1] );

		if ( function_exists( 'hlm_replace_media_tokens' ) ) {
			$markup = hlm_replace_media_tokens( $markup );
		}

		return $markup;
	}

	return '';
}

/**
 * Find a post by exact title (WP 6.2+ replacement for get_page_by_title).
 *
 * @param string $title     Post title.
 * @param string $post_type Post type.
 * @return WP_Post|null
 */
function hlm_get_post_by_title( string $title, string $post_type = 'page' ): ?WP_Post {
	$query = new WP_Query(
		array(
			'post_type'              => $post_type,
			'title'                  => $title,
			'post_status'            => 'any',
			'posts_per_page'         => 1,
			'no_found_rows'          => true,
			'ignore_sticky_posts'    => true,
			'update_post_meta_cache' => false,
			'update_post_term_cache' => false,
		)
	);

	if ( ! $query->have_posts() ) {
		return null;
	}

	return $query->posts[0];
}

/**
 * Create or update a published page.
 *
 * @param array $config Page configuration.
 * @return int|false Page ID.
 */
function hlm_upsert_page( array $config ) {
	$content = $config['content'] ?? '';

	if ( ! empty( $config['pattern'] ) ) {
		$content = hlm_get_pattern_markup( $config['pattern'] );
	}

	if ( '' === $content && empty( $config['allow_empty'] ) ) {
		return false;
	}

	$page_id = 0;

	if ( ! empty( $config['is_front'] ) ) {
		$front_id = (int) get_option( 'page_on_front' );
		if ( $front_id && get_post( $front_id ) ) {
			$page_id = $front_id;
		}
	}

	if ( ! $page_id && ! empty( $config['slug'] ) ) {
		$lookup_path = $config['slug'];
		if ( ! empty( $config['parent'] ) ) {
			$parent = get_post( (int) $config['parent'] );
			if ( $parent instanceof WP_Post && $parent->post_name ) {
				$lookup_path = $parent->post_name . '/' . $config['slug'];
			}
		}

		$existing = get_page_by_path( $lookup_path );
		if ( $existing instanceof WP_Post ) {
			$page_id = (int) $existing->ID;
		}
	}

	if ( ! $page_id && ! empty( $config['title'] ) ) {
		$by_title = hlm_get_post_by_title( $config['title'], 'page' );
		if ( $by_title instanceof WP_Post ) {
			$page_id = (int) $by_title->ID;
		}
	}

	$postarr = array(
		'post_type'    => 'page',
		'post_status'  => 'publish',
		'post_title'   => $config['title'],
		'post_name'    => $config['slug'],
		'post_content' => $content,
	);

	if ( ! empty( $config['parent'] ) ) {
		$postarr['post_parent'] = (int) $config['parent'];
	}

	if ( $page_id ) {
		$postarr['ID'] = $page_id;
		$result        = wp_update_post( $postarr, true );
	} else {
		$result = wp_insert_post( $postarr, true );
	}

	if ( is_wp_error( $result ) ) {
		return false;
	}

	$page_id = (int) $result;

	if ( ! empty( $config['is_front'] ) ) {
		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', $page_id );

		if ( function_exists( 'hlm_kadence_apply_landing_meta' ) ) {
			hlm_kadence_apply_landing_meta( $page_id );
		}
	}

	return $page_id;
}

/**
 * Create sample blog posts for the blog page.
 */
function hlm_setup_sample_posts() {
	if ( get_option( 'hlm_sample_posts_v1' ) ) {
		return;
	}

	$posts = array(
		array(
			'title'   => 'How to Choose the Right Nursing Program',
			'excerpt' => 'A practical guide for students comparing RN, BSN, and MSN pathways across campus and online options.',
		),
		array(
			'title'   => 'What Universities Should Know About Lead Quality',
			'excerpt' => 'Why pre-qualified, consent-based inquiries outperform raw volume in enrollment marketing.',
		),
		array(
			'title'   => 'Berkeley College Application Tips for Working Adults',
			'excerpt' => 'How to prepare a strong application when balancing work, family, and career-focused education.',
		),
	);

	foreach ( $posts as $post ) {
		if ( hlm_get_post_by_title( $post['title'], 'post' ) ) {
			continue;
		}

		wp_insert_post(
			array(
				'post_type'    => 'post',
				'post_status'  => 'publish',
				'post_title'   => $post['title'],
				'post_excerpt' => $post['excerpt'],
				'post_content' => '<p>' . esc_html( $post['excerpt'] ) . '</p>',
			)
		);
	}

	update_option( 'hlm_sample_posts_v1', 1, false );
}

/**
 * Create all HLM marketing pages from patterns.
 */
function hlm_setup_site_pages() {
	if ( get_option( 'hlm_site_pages_v2' ) ) {
		return;
	}

	$pages = array(
		array(
			'title'    => 'Home',
			'slug'     => 'home',
			'pattern'  => 'home-page.php',
			'is_front' => true,
		),
		array(
			'title'   => 'About Us',
			'slug'    => 'about-us',
			'pattern' => 'about-us-page.php',
		),
		array(
			'title'   => 'Services',
			'slug'    => 'services',
			'pattern' => 'services-page.php',
		),
		array(
			'title'   => 'Affiliations',
			'slug'    => 'affiliations',
			'pattern' => 'affiliations-page.php',
		),
		array(
			'title'   => 'Contact Us',
			'slug'    => 'contact-us',
			'pattern' => 'contact-page.php',
		),
		array(
			'title'        => 'Blog',
			'slug'         => 'blog',
			'content'      => '',
			'allow_empty'  => true,
			'is_posts_page' => true,
		),
		array(
			'title'   => 'Privacy Policy',
			'slug'    => 'privacy-policy',
			'pattern' => 'privacy-page.php',
		),
	);

	foreach ( $pages as $page ) {
		$page_id = hlm_upsert_page( $page );

		if ( $page_id && ! empty( $page['is_posts_page'] ) ) {
			update_option( 'page_for_posts', $page_id );
		}

		if ( $page_id && function_exists( 'hlm_kadence_apply_inner_page_meta' ) ) {
			if ( ! empty( $page['is_front'] ) ) {
				hlm_kadence_apply_landing_meta( $page_id );
			} else {
				hlm_kadence_apply_inner_page_meta( $page_id );
			}
		}
	}

	hlm_setup_sample_posts();

	if ( function_exists( 'hlm_kadence_setup_primary_menu' ) ) {
		hlm_kadence_setup_primary_menu( true );
	}

	update_option( 'hlm_site_pages_v2', 1, false );

	do_action( 'hlm_kadence_pages_ready' );
}
add_action( 'init', 'hlm_setup_site_pages', 15 );

/**
 * Re-sync marketing page content from updated block patterns (one-time).
 */
function hlm_resync_pattern_content_v3() {
	if ( get_option( 'hlm_pattern_resync_v3' ) ) {
		return;
	}

	$pages = array(
		array(
			'title'    => 'Home',
			'slug'     => 'home',
			'pattern'  => 'home-page.php',
			'is_front' => true,
		),
		array(
			'title'   => 'About Us',
			'slug'    => 'about-us',
			'pattern' => 'about-us-page.php',
		),
		array(
			'title'   => 'Services',
			'slug'    => 'services',
			'pattern' => 'services-page.php',
		),
		array(
			'title'   => 'Affiliations',
			'slug'    => 'affiliations',
			'pattern' => 'affiliations-page.php',
		),
		array(
			'title'   => 'Contact Us',
			'slug'    => 'contact-us',
			'pattern' => 'contact-page.php',
		),
	);

	foreach ( $pages as $page ) {
		$page_id = hlm_upsert_page( $page );

		if ( $page_id && ! empty( $page['is_front'] ) && function_exists( 'hlm_kadence_apply_landing_meta' ) ) {
			hlm_kadence_apply_landing_meta( $page_id );
		} elseif ( $page_id && function_exists( 'hlm_kadence_apply_inner_page_meta' ) ) {
			hlm_kadence_apply_inner_page_meta( $page_id );
		}
	}

	update_option( 'hlm_pattern_resync_v3', 1, false );
}
add_action( 'init', 'hlm_resync_pattern_content_v3', 14 );

/**
 * Re-sync pages with hero media + design updates (one-time).
 */
function hlm_resync_pattern_content_v4() {
	if ( get_option( 'hlm_pattern_resync_v4' ) ) {
		return;
	}

	$pages = array(
		array(
			'title'    => 'Home',
			'slug'     => 'home',
			'pattern'  => 'home-page.php',
			'is_front' => true,
		),
	);

	foreach ( $pages as $page ) {
		$page_id = hlm_upsert_page( $page );

		if ( $page_id && function_exists( 'hlm_kadence_apply_landing_meta' ) ) {
			hlm_kadence_apply_landing_meta( $page_id );
		}
	}

	update_option( 'hlm_pattern_resync_v4', 1, false );
}
add_action( 'init', 'hlm_resync_pattern_content_v4', 13 );

/**
 * Re-sync Home page with bundled hero demo video (one-time).
 */
function hlm_resync_pattern_content_v5() {
	if ( get_option( 'hlm_pattern_resync_v5' ) ) {
		return;
	}

	$pages = array(
		array(
			'title'    => 'Home',
			'slug'     => 'home',
			'pattern'  => 'home-page.php',
			'is_front' => true,
		),
	);

	foreach ( $pages as $page ) {
		$page_id = hlm_upsert_page( $page );

		if ( $page_id && function_exists( 'hlm_kadence_apply_landing_meta' ) ) {
			hlm_kadence_apply_landing_meta( $page_id );
		}
	}

	update_option( 'hlm_pattern_resync_v5', 1, false );
}
add_action( 'init', 'hlm_resync_pattern_content_v5', 12 );

/**
 * Re-sync all marketing pages with curated photography (one-time).
 */
function hlm_resync_pattern_content_v6() {
	if ( get_option( 'hlm_pattern_resync_v6' ) ) {
		return;
	}

	$pages = array(
		array(
			'title'    => 'Home',
			'slug'     => 'home',
			'pattern'  => 'home-page.php',
			'is_front' => true,
		),
		array(
			'title'   => 'About Us',
			'slug'    => 'about-us',
			'pattern' => 'about-us-page.php',
		),
		array(
			'title'   => 'Services',
			'slug'    => 'services',
			'pattern' => 'services-page.php',
		),
		array(
			'title'   => 'Affiliations',
			'slug'    => 'affiliations',
			'pattern' => 'affiliations-page.php',
		),
		array(
			'title'   => 'Contact Us',
			'slug'    => 'contact-us',
			'pattern' => 'contact-page.php',
		),
	);

	foreach ( $pages as $page ) {
		$page_id = hlm_upsert_page( $page );

		if ( $page_id && ! empty( $page['is_front'] ) && function_exists( 'hlm_kadence_apply_landing_meta' ) ) {
			hlm_kadence_apply_landing_meta( $page_id );
		} elseif ( $page_id && function_exists( 'hlm_kadence_apply_inner_page_meta' ) ) {
			hlm_kadence_apply_inner_page_meta( $page_id );
		}
	}

	update_option( 'hlm_pattern_resync_v6', 1, false );
}
add_action( 'init', 'hlm_resync_pattern_content_v6', 11 );

/**
 * Re-sync Home hero background options (image + overlay defaults).
 */
function hlm_resync_pattern_content_v7() {
	if ( get_option( 'hlm_pattern_resync_v7' ) ) {
		return;
	}

	$page_id = hlm_upsert_page(
		array(
			'title'    => 'Home',
			'slug'     => 'home',
			'pattern'  => 'home-page.php',
			'is_front' => true,
		)
	);

	if ( $page_id && function_exists( 'hlm_kadence_apply_landing_meta' ) ) {
		hlm_kadence_apply_landing_meta( $page_id );
	}

	update_option( 'hlm_pattern_resync_v7', 1, false );
}
add_action( 'init', 'hlm_resync_pattern_content_v7', 10 );

/**
 * Re-sync Home CTA soft variant (one-time).
 */
function hlm_resync_pattern_content_v8() {
	if ( get_option( 'hlm_pattern_resync_v8' ) ) {
		return;
	}

	$page_id = hlm_upsert_page(
		array(
			'title'    => 'Home',
			'slug'     => 'home',
			'pattern'  => 'home-page.php',
			'is_front' => true,
		)
	);

	if ( $page_id && function_exists( 'hlm_kadence_apply_landing_meta' ) ) {
		hlm_kadence_apply_landing_meta( $page_id );
	}

	update_option( 'hlm_pattern_resync_v8', 1, false );
}
add_action( 'init', 'hlm_resync_pattern_content_v8', 9 );

/**
 * Re-sync inner pages after mockup alignment pass (one-time).
 */
function hlm_resync_pattern_content_v9() {
	if ( get_option( 'hlm_pattern_resync_v9' ) ) {
		return;
	}

	$pages = array(
		array(
			'title'   => 'Contact Us',
			'slug'    => 'contact-us',
			'pattern' => 'contact-page.php',
		),
		array(
			'title'   => 'Services',
			'slug'    => 'services',
			'pattern' => 'services-page.php',
		),
		array(
			'title'   => 'About Us',
			'slug'    => 'about-us',
			'pattern' => 'about-us-page.php',
		),
		array(
			'title'   => 'Affiliations',
			'slug'    => 'affiliations',
			'pattern' => 'affiliations-page.php',
		),
	);

	foreach ( $pages as $page ) {
		$page_id = hlm_upsert_page( $page );

		if ( $page_id && function_exists( 'hlm_kadence_apply_inner_page_meta' ) ) {
			hlm_kadence_apply_inner_page_meta( $page_id );
		}
	}

	update_option( 'hlm_pattern_resync_v9', 1, false );
}
add_action( 'init', 'hlm_resync_pattern_content_v9', 8 );

/**
 * Re-sync Services page using HLM design-system blocks (one-time).
 */
function hlm_resync_pattern_content_v11() {
	if ( get_option( 'hlm_pattern_resync_v11' ) ) {
		return;
	}

	$page_id = hlm_upsert_page(
		array(
			'title'   => 'Services',
			'slug'    => 'services',
			'pattern' => 'services-page.php',
		)
	);

	if ( $page_id && function_exists( 'hlm_kadence_apply_inner_page_meta' ) ) {
		hlm_kadence_apply_inner_page_meta( $page_id );
	}

	update_option( 'hlm_pattern_resync_v11', 1, false );
}
add_action( 'init', 'hlm_resync_pattern_content_v11', 6 );

/**
 * Re-sync all inner pages after ecosystem UX/layout pass (one-time).
 */
function hlm_resync_pattern_content_v12() {
	if ( get_option( 'hlm_pattern_resync_v12' ) ) {
		return;
	}

	$pages = array(
		array(
			'title'   => 'Contact Us',
			'slug'    => 'contact-us',
			'pattern' => 'contact-page.php',
		),
		array(
			'title'   => 'Services',
			'slug'    => 'services',
			'pattern' => 'services-page.php',
		),
		array(
			'title'   => 'About Us',
			'slug'    => 'about-us',
			'pattern' => 'about-us-page.php',
		),
		array(
			'title'   => 'Affiliations',
			'slug'    => 'affiliations',
			'pattern' => 'affiliations-page.php',
		),
	);

	foreach ( $pages as $page ) {
		$page_id = hlm_upsert_page( $page );

		if ( $page_id && function_exists( 'hlm_kadence_apply_inner_page_meta' ) ) {
			hlm_kadence_apply_inner_page_meta( $page_id );
		}
	}

	update_option( 'hlm_pattern_resync_v12', 1, false );
}
add_action( 'init', 'hlm_resync_pattern_content_v12', 5 );

/**
 * Re-sync Contact Us page — remove duplicate home hero, use inquiry layout (one-time).
 */
function hlm_resync_pattern_content_v13() {
	if ( get_option( 'hlm_pattern_resync_v13' ) ) {
		return;
	}

	$page_id = hlm_upsert_page(
		array(
			'title'   => 'Contact Us',
			'slug'    => 'contact-us',
			'pattern' => 'contact-page.php',
		)
	);

	if ( $page_id && function_exists( 'hlm_kadence_apply_inner_page_meta' ) ) {
		hlm_kadence_apply_inner_page_meta( $page_id );
	}

	update_option( 'hlm_pattern_resync_v13', 1, false );
}
add_action( 'init', 'hlm_resync_pattern_content_v13', 4 );

/**
 * Re-sync all marketing pages after surface/contrast system pass (one-time).
 */
function hlm_resync_pattern_content_v14() {
	if ( get_option( 'hlm_pattern_resync_v14' ) ) {
		return;
	}

	$pages = array(
		array(
			'title'    => 'Home',
			'slug'     => 'home',
			'pattern'  => 'home-page.php',
			'is_front' => true,
		),
		array(
			'title'   => 'Contact Us',
			'slug'    => 'contact-us',
			'pattern' => 'contact-page.php',
		),
		array(
			'title'   => 'Services',
			'slug'    => 'services',
			'pattern' => 'services-page.php',
		),
		array(
			'title'   => 'About Us',
			'slug'    => 'about-us',
			'pattern' => 'about-us-page.php',
		),
		array(
			'title'   => 'Affiliations',
			'slug'    => 'affiliations',
			'pattern' => 'affiliations-page.php',
		),
	);

	foreach ( $pages as $page ) {
		$page_id = hlm_upsert_page( $page );

		if ( ! $page_id ) {
			continue;
		}

		if ( ! empty( $page['is_front'] ) && function_exists( 'hlm_kadence_apply_landing_meta' ) ) {
			hlm_kadence_apply_landing_meta( $page_id );
		} elseif ( function_exists( 'hlm_kadence_apply_inner_page_meta' ) ) {
			hlm_kadence_apply_inner_page_meta( $page_id );
		}
	}

	update_option( 'hlm_pattern_resync_v14', 1, false );
}
add_action( 'init', 'hlm_resync_pattern_content_v14', 3 );

/**
 * Re-sync Affiliations + Contact after SEO/layout cleanup (one-time).
 */
function hlm_resync_pattern_content_v15() {
	if ( get_option( 'hlm_pattern_resync_v15' ) ) {
		return;
	}

	$pages = array(
		array(
			'title'   => 'Affiliations',
			'slug'    => 'affiliations',
			'pattern' => 'affiliations-page.php',
		),
		array(
			'title'   => 'Contact Us',
			'slug'    => 'contact-us',
			'pattern' => 'contact-page.php',
		),
	);

	foreach ( $pages as $page ) {
		$page_id = hlm_upsert_page( $page );

		if ( $page_id && function_exists( 'hlm_kadence_apply_inner_page_meta' ) ) {
			hlm_kadence_apply_inner_page_meta( $page_id );
		}
	}

	update_option( 'hlm_pattern_resync_v15', 1, false );
}
add_action( 'init', 'hlm_resync_pattern_content_v15', 2 );

/**
 * Re-sync About Us leadership team layout (one-time).
 */
function hlm_resync_pattern_content_v16() {
	if ( get_option( 'hlm_pattern_resync_v16' ) ) {
		return;
	}

	$page_id = hlm_upsert_page(
		array(
			'title'   => 'About Us',
			'slug'    => 'about-us',
			'pattern' => 'about-us-page.php',
		)
	);

	if ( $page_id && function_exists( 'hlm_kadence_apply_inner_page_meta' ) ) {
		hlm_kadence_apply_inner_page_meta( $page_id );
	}

	update_option( 'hlm_pattern_resync_v16', 1, false );
}
add_action( 'init', 'hlm_resync_pattern_content_v16', 1 );

/**
 * Re-sync About leadership profiles (shorter copy, no stock photos).
 */
function hlm_resync_pattern_content_v17() {
	if ( get_option( 'hlm_pattern_resync_v17' ) ) {
		return;
	}

	$page_id = hlm_upsert_page(
		array(
			'title'   => 'About Us',
			'slug'    => 'about-us',
			'pattern' => 'about-us-page.php',
		)
	);

	if ( $page_id && function_exists( 'hlm_kadence_apply_inner_page_meta' ) ) {
		hlm_kadence_apply_inner_page_meta( $page_id );
	}

	update_option( 'hlm_pattern_resync_v17', 1, false );
}
add_action( 'init', 'hlm_resync_pattern_content_v17', 0 );

/**
 * Re-sync Home + Services process step CTAs (one-time).
 */
function hlm_resync_pattern_content_v18() {
	if ( get_option( 'hlm_pattern_resync_v18' ) ) {
		return;
	}

	$pages = array(
		array(
			'title'    => 'Home',
			'slug'     => 'home',
			'pattern'  => 'home-page.php',
			'is_front' => true,
		),
		array(
			'title'   => 'Services',
			'slug'    => 'services',
			'pattern' => 'services-page.php',
		),
	);

	foreach ( $pages as $page ) {
		$page_id = hlm_upsert_page( $page );

		if ( $page_id && ! empty( $page['is_front'] ) ) {
			update_option( 'page_on_front', $page_id );
			update_option( 'show_on_front', 'page' );
			if ( function_exists( 'hlm_kadence_apply_landing_meta' ) ) {
				hlm_kadence_apply_landing_meta( $page_id );
			}
		} elseif ( $page_id && function_exists( 'hlm_kadence_apply_inner_page_meta' ) ) {
			hlm_kadence_apply_inner_page_meta( $page_id );
		}
	}

	update_option( 'hlm_pattern_resync_v18', 1, false );
}
add_action( 'init', 'hlm_resync_pattern_content_v18', 0 );

/**
 * WP-CLI: wp hlm setup-pages
 *
 * @param array $args       Positional args.
 * @param array $assoc_args Associative args.
 */
function hlm_cli_setup_pages( $args, $assoc_args ) {
	delete_option( 'hlm_site_pages_v2' );
	delete_option( 'hlm_sample_posts_v1' );
	hlm_setup_site_pages();

	WP_CLI::success( 'HLM pages created/updated: Home, About Us, Services, Affiliations, Contact Us, Blog, Privacy Policy.' );
}

if ( defined( 'WP_CLI' ) && WP_CLI ) {
	WP_CLI::add_command( 'hlm setup-pages', 'hlm_cli_setup_pages' );
}
