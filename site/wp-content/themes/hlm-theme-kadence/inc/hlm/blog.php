<?php
/**
 * Blog archive and single post integration with the HLM design system.
 *
 * @package HLM_Kadence
 */

defined( 'ABSPATH' ) || exit;

/**
 * Default blog hero copy.
 *
 * @return array{overline:string,headline:string,description:string}
 */
function hlm_blog_default_hero() {
	return array(
		'overline'    => '',
		'headline'    => __( 'U.S. Enrollment Marketing & Student Guides', 'hlm-kadence' ),
		'description' => __( 'Resources for universities seeking qualified student leads and for prospective students exploring nursing, business, and career programs across the United States.', 'hlm-kadence' ),
	);
}

/**
 * Resolve hero copy for the current blog view.
 *
 * @return array{overline:string,headline:string,description:string}
 */
function hlm_blog_hero_args() {
	$defaults = hlm_blog_default_hero();

	if ( is_category() ) {
		$term = get_queried_object();
		return array(
			'overline'    => __( 'Category', 'hlm-kadence' ),
			'headline'    => single_cat_title( '', false ),
			'description' => term_description( $term->term_id ) ? wp_strip_all_tags( term_description( $term->term_id ) ) : $defaults['description'],
		);
	}

	if ( is_tag() ) {
		return array(
			'overline'    => __( 'Topic', 'hlm-kadence' ),
			'headline'    => single_tag_title( '', false ),
			'description' => $defaults['description'],
		);
	}

	if ( is_author() ) {
		return array(
			'overline'    => __( 'Author', 'hlm-kadence' ),
			'headline'    => get_the_author(),
			'description' => get_the_author_meta( 'description' ) ?: $defaults['description'],
		);
	}

	if ( is_date() ) {
		return array(
			'overline'    => __( 'Archive', 'hlm-kadence' ),
			'headline'    => wp_strip_all_tags( get_the_archive_title() ),
			'description' => $defaults['description'],
		);
	}

	return $defaults;
}

/**
 * Resolve hero overline for a single post (cluster label or WP category).
 *
 * @param WP_Post|null $post Post object.
 * @return string
 */
function hlm_blog_post_overline( $post = null ) {
	if ( null === $post ) {
		$post = get_queried_object();
	}

	if ( ! $post instanceof WP_Post ) {
		return __( 'Insights', 'hlm-kadence' );
	}

	if ( function_exists( 'hlm_seo_get_post_cluster' ) && function_exists( 'hlm_seo_topic_clusters' ) ) {
		$membership = hlm_seo_get_post_cluster( $post->post_name );
		if ( $membership && ! empty( $membership['cluster_id'] ) ) {
			$groups = hlm_seo_topic_clusters();
			if ( ! empty( $groups[ $membership['cluster_id'] ]['label'] ) ) {
				return $groups[ $membership['cluster_id'] ]['label'];
			}
		}
	}

	$categories = get_the_category( $post->ID );
	foreach ( $categories as $category ) {
		if ( 'uncategorized' !== $category->slug ) {
			return $category->name;
		}
	}

	return __( 'Insights', 'hlm-kadence' );
}

/**
 * Use Blog in breadcrumbs for single posts (not pillar pages like Services).
 *
 * @param array<int, array<string, string>> $trail Breadcrumb items.
 * @return array<int, array<string, string>>
 */
function hlm_blog_breadcrumb_trail( array $trail ): array {
	if ( ! is_singular( 'post' ) ) {
		return $trail;
	}

	$trail = array(
		array(
			'label' => __( 'Home', 'hlm-kadence' ),
			'url'   => home_url( '/' ),
		),
	);

	$blog_id = (int) get_option( 'page_for_posts' );
	if ( $blog_id ) {
		$trail[] = array(
			'label' => get_the_title( $blog_id ),
			'url'   => get_permalink( $blog_id ),
		);
	}

	$trail[] = array(
		'label' => get_the_title(),
		'url'   => '',
	);

	return $trail;
}
add_filter( 'hlm_seo_breadcrumb_trail', 'hlm_blog_breadcrumb_trail' );

/**
 * Register blog-specific Kadence hooks.
 */
function hlm_blog_register_hooks() {
	if ( hlm_kadence_is_blog_view() ) {
		remove_action( 'kadence_hero_header', 'Kadence\hero_title' );
		add_action( 'kadence_hero_header', 'hlm_blog_render_archive_hero' );

		remove_action( 'kadence_loop_entry', 'Kadence\loop_entry' );
		add_action( 'kadence_loop_entry', 'hlm_blog_render_loop_entry' );

		add_action( 'kadence_before_main_content', 'hlm_blog_archive_open', 5 );
		add_action( 'kadence_after_main_content', 'hlm_blog_archive_close', 15 );
		add_action( 'kadence_after_main_content', 'hlm_blog_render_archive_cta', 20 );

		add_filter( 'get_archive_container_classes', 'hlm_blog_archive_container_classes' );
	}

	if ( is_singular( 'post' ) ) {
		remove_action( 'kadence_hero_header', 'Kadence\hero_title' );
		add_action( 'kadence_hero_header', 'hlm_blog_render_single_hero' );

		if ( class_exists( 'HLM_SEO\Internal_Links' ) ) {
			remove_action( 'kadence_single_after_content', array( 'HLM_SEO\Internal_Links', 'render_related_cluster' ) );
		}

		add_action( 'kadence_before_main_content', 'hlm_blog_single_open', 5 );
		add_action( 'kadence_single_content', 'hlm_blog_single_main_open', 5 );
		add_action( 'kadence_single_after_content', 'hlm_blog_single_finish_layout', 50 );
		add_action( 'kadence_after_main_content', 'hlm_blog_single_close', 15 );
		add_action( 'kadence_after_main_content', 'hlm_blog_render_single_cta', 20 );
	}
}
add_action( 'wp', 'hlm_blog_register_hooks' );

/**
 * Defer in-content cluster links - sidebar renders them instead.
 *
 * @param bool     $defer Whether to defer injection.
 * @param WP_Post  $post  Current post.
 * @return bool
 */
function hlm_blog_defer_cluster_links( bool $defer, WP_Post $post ): bool {
	if ( hlm_kadence_is_theme_shell() && 'post' === $post->post_type ) {
		return true;
	}

	return $defer;
}
add_filter( 'hlm_seo_defer_cluster_links', 'hlm_blog_defer_cluster_links', 10, 2 );

/**
 * Hide Kadence archive/single titles - HLM renders its own hero.
 *
 * @param array $layout Kadence layout config.
 * @return array
 */
function hlm_blog_post_layout( $layout ) {
	if ( hlm_kadence_is_blog_view() ) {
		$layout['title']      = 'hide';
		$layout['sidebar']    = 'disable';
		$layout['layout']     = 'fullwidth';
		$layout['comments']   = 'hide';
		$layout['navigation'] = 'hide';
	}

	if ( is_singular( 'post' ) ) {
		$layout['title']      = 'hide';
		$layout['sidebar']    = 'disable';
		$layout['layout']     = 'fullwidth';
		$layout['comments']   = 'hide';
		$layout['navigation'] = 'hide';
	}

	return $layout;
}
add_filter( 'kadence_post_layout', 'hlm_blog_post_layout' );

/**
 * Render the blog archive hero.
 */
function hlm_blog_render_archive_hero() {
	$hero = hlm_blog_hero_args();

	hlm_render_page_hero(
		array(
			'id'                 => 'blog-archive-hero',
			'overline'           => $hero['overline'],
			'headline'           => $hero['headline'],
			'description'        => $hero['description'],
			'media_image_url'    => '',
			'media_show_overlay' => false,
		)
	);
}

/**
 * Render the single post hero.
 */
function hlm_blog_render_single_hero() {
	$meta = sprintf(
		/* translators: %s: post date */
		__( 'Published %s', 'hlm-kadence' ),
		get_the_date()
	);

	hlm_render_page_hero(
		array(
			'id'             => 'blog-single-hero',
			'overline'       => hlm_blog_post_overline(),
			'headline'       => get_the_title(),
			'description'    => $meta,
			'headline_class' => 'hlm-h1 hlm-h1--wide',
		)
	);
}

/**
 * Open the blog archive content section.
 */
function hlm_blog_archive_open() {
	echo '<section class="hlm-blog-archive hlm-section hlm-bg-cream"><div class="hlm-container">';
}

/**
 * Close the blog archive content section.
 */
function hlm_blog_archive_close() {
	echo '</div></section>';
}

/**
 * Open the single post section shell (before Kadence content-wrap).
 */
function hlm_blog_single_open() {
	echo '<section class="hlm-single-post-wrap hlm-section hlm-bg-cream">';
	echo '<div class="hlm-container hlm-single-post-container">';
}

/**
 * Open the two-column grid and main column (wraps the article).
 */
function hlm_blog_single_main_open() {
	echo '<div class="hlm-single-post-layout">';
	echo '<div class="hlm-single-post-main">';
}

/**
 * Close main column, render sidebar + related, close grid.
 */
function hlm_blog_single_finish_layout() {
	echo '</div><!-- .hlm-single-post-main -->';

	get_template_part( 'template-parts/hlm/single-post-sidebar' );

	echo '</div><!-- .hlm-single-post-layout -->';

	hlm_blog_render_single_related();
}

/**
 * Close the single post section wrapper.
 */
function hlm_blog_single_close() {
	echo '</div></section>';
}

/**
 * Render related cluster posts as HLM blog cards.
 */
function hlm_blog_render_single_related() {
	if ( ! class_exists( 'HLM_SEO\Internal_Links' ) ) {
		return;
	}

	$data = \HLM_SEO\Internal_Links::get_related_cluster_data();
	if ( empty( $data['related'] ) ) {
		return;
	}

	$headline = $data['cluster_label']
		? sprintf(
			/* translators: %s: topic cluster label */
			__( 'More in %s', 'hlm-kadence' ),
			$data['cluster_label']
		)
		: __( 'Related Articles', 'hlm-kadence' );
	?>
	<section class="hlm-single-post-related" aria-labelledby="hlm-single-post-related-title">
		<div class="hlm-section-header">
			<div class="hlm-overline"><?php esc_html_e( 'Keep Reading', 'hlm-kadence' ); ?></div>
			<h2 id="hlm-single-post-related-title" class="hlm-h2"><?php echo esc_html( $headline ); ?></h2>
		</div>
		<div class="hlm-single-post-related__grid">
			<?php
			$index = 0;
			foreach ( array_slice( $data['related'], 0, 3 ) as $related_post ) {
				++$index;
				hlm_render_blog_card_for_post( $related_post, array( 'index' => $index ) );
			}
			?>
		</div>
	</section>
	<?php
}

/**
 * Render a blog card inside the Kadence archive loop.
 */
function hlm_blog_render_loop_entry() {
	static $index = 0;
	++$index;

	echo '<li class="entry-list-item hlm-blog-grid__item">';
	hlm_render_blog_card( array( 'index' => $index ) );
	echo '</li>';
}

/**
 * Archive grid classes.
 *
 * @param array $classes Existing classes.
 * @return array
 */
function hlm_blog_archive_container_classes( $classes ) {
	$classes[] = 'hlm-blog-grid';

	return $classes;
}

/**
 * CTA after blog archive listing.
 */
function hlm_blog_render_archive_cta() {
	if ( ! is_home() && ! is_post_type_archive( 'post' ) ) {
		return;
	}

	hlm_render_cta_band(
		array(
			'id'             => 'blog-archive-cta',
			'overline'       => __( 'Get Started', 'hlm-kadence' ),
			'headline'       => __( 'Ready to Connect With HLM?', 'hlm-kadence' ),
			'description'    => __( 'Whether you are exploring programs or growing enrollment, our team can help you take the next step.', 'hlm-kadence' ),
			'primary_text'   => __( 'Contact Us', 'hlm-kadence' ),
			'primary_url'    => home_url( '/contact-us/#form' ),
			'secondary_text' => __( 'View Our Services', 'hlm-kadence' ),
			'secondary_url'  => home_url( '/services/' ),
			'variant'        => 'soft',
		)
	);
}

/**
 * CTA after single blog post.
 */
function hlm_blog_render_single_cta() {
	$post    = get_queried_object();
	$is_b2b  = false;

	if ( $post instanceof WP_Post && function_exists( 'hlm_seo_get_post_cluster' ) ) {
		$cluster = hlm_seo_get_post_cluster( $post->post_name );
		$is_b2b  = $cluster && 'commercial' === ( $cluster['intent'] ?? '' );
	}

	if ( $is_b2b ) {
		hlm_render_cta_band(
			array(
				'id'             => 'blog-single-cta',
				'overline'       => __( 'Partner With HLM', 'hlm-kadence' ),
				'headline'       => __( 'Need Consent-Based Student Leads?', 'hlm-kadence' ),
				'description'    => __( 'Talk to our team about university lead generation, program targeting, and enrollment goals for institutions across the United States.', 'hlm-kadence' ),
				'primary_text'   => __( 'Explore Services', 'hlm-kadence' ),
				'primary_url'    => home_url( '/services/' ),
				'secondary_text' => __( 'Request a Consultation', 'hlm-kadence' ),
				'secondary_url'  => home_url( '/contact-us/#form' ),
				'variant'        => 'soft',
			)
		);
		return;
	}

	hlm_render_cta_band(
		array(
			'id'             => 'blog-single-cta',
			'overline'       => __( 'Next Step', 'hlm-kadence' ),
			'headline'       => __( 'Find the Right Program for You', 'hlm-kadence' ),
			'description'    => __( 'Connect with an advisor to compare accredited nursing, business, and career programs - or browse more articles on our blog.', 'hlm-kadence' ),
			'primary_text'   => __( 'Connect With an Advisor', 'hlm-kadence' ),
			'primary_url'    => home_url( '/contact-us/#form' ),
			'secondary_text' => __( 'Back to Blog', 'hlm-kadence' ),
			'secondary_url'  => home_url( '/blog/' ),
			'variant'        => 'soft',
		)
	);
}

/**
 * Use /blog/post-slug/ permalinks so posts live under the blog archive path.
 */
function hlm_blog_setup_permalinks() {
	if ( get_option( 'hlm_blog_permalinks_v1' ) ) {
		return;
	}

	$structure = '/blog/%postname%/';
	if ( get_option( 'permalink_structure' ) !== $structure ) {
		update_option( 'permalink_structure', $structure );
		flush_rewrite_rules();
	}

	update_option( 'hlm_blog_permalinks_v1', 1, false );
}
add_action( 'init', 'hlm_blog_setup_permalinks', 21 );

/**
 * Configure the posts page and archive layout once.
 */
function hlm_blog_bootstrap_layout() {
	if ( get_option( 'hlm_blog_layout_v3' ) ) {
		return;
	}

	$posts_page_id = (int) get_option( 'page_for_posts' );
	if ( $posts_page_id ) {
		hlm_kadence_apply_inner_page_meta( $posts_page_id );
		update_post_meta( $posts_page_id, '_kad_post_title', 'hide' );
	}

	set_theme_mod( 'post_archive_layout', 'fullwidth' );
	set_theme_mod( 'post_archive_sidebar_id', 'none' );
	set_theme_mod( 'post_archive_title', false );
	set_theme_mod( 'post_archive_home_title', false );
	set_theme_mod( 'post_archive_title_layout', 'above' );
	set_theme_mod( 'post_archive_columns', '3' );
	set_theme_mod( 'post_archive_item_image_placement', 'above' );
	set_theme_mod( 'post_archive_item_title', true );
	set_theme_mod( 'post_archive_item_meta', true );
	set_theme_mod( 'post_archive_item_excerpt', true );
	set_theme_mod( 'post_archive_item_readmore', true );

	set_theme_mod( 'post_layout', 'fullwidth' );
	set_theme_mod( 'post_sidebar_id', 'none' );
	set_theme_mod( 'post_title', false );
	set_theme_mod( 'post_related', false );
	set_theme_mod( 'post_author_box', false );
	set_theme_mod( 'post_tags', false );
	set_theme_mod( 'post_navigation', false );
	set_theme_mod( 'post_comments', false );

	update_option( 'hlm_blog_layout_v3', 1, false );
}
add_action( 'init', 'hlm_blog_bootstrap_layout', 22 );

/**
 * Assign WP categories to cluster posts from SEO topic groups.
 */
function hlm_blog_assign_cluster_categories() {
	if ( get_option( 'hlm_post_categories_v1' ) || ! function_exists( 'hlm_seo_topic_clusters' ) ) {
		return;
	}

	foreach ( hlm_seo_topic_clusters() as $cluster ) {
		if ( empty( $cluster['clusters'] ) || empty( $cluster['label'] ) ) {
			continue;
		}

		$term = term_exists( $cluster['label'], 'category' );
		if ( ! $term ) {
			$term = wp_insert_term( $cluster['label'], 'category' );
		}

		if ( is_wp_error( $term ) ) {
			continue;
		}

		$term_id = (int) ( is_array( $term ) ? $term['term_id'] : $term );

		foreach ( $cluster['clusters'] as $item ) {
			$slug = $item['slug'] ?? '';
			if ( ! $slug ) {
				continue;
			}

			$post = get_page_by_path( $slug, OBJECT, 'post' );
			if ( ! $post instanceof WP_Post ) {
				continue;
			}

			wp_set_post_categories( (int) $post->ID, array( $term_id ), false );
		}
	}

	update_option( 'hlm_post_categories_v1', 1, false );
}
add_action( 'init', 'hlm_blog_assign_cluster_categories', 38 );

/**
 * Refresh branded featured images after SVG artwork updates.
 */
function hlm_blog_refresh_featured_images_v2() {
	if ( get_option( 'hlm_blog_featured_v2' ) || ! function_exists( 'hlm_kadence_seo_blog_posts_content' ) ) {
		return;
	}

	delete_option( 'hlm_seo_featured_attachments' );

	$posts = hlm_kadence_seo_blog_posts_content();
	foreach ( array_keys( $posts ) as $slug ) {
		$post = get_page_by_path( $slug, OBJECT, 'post' );
		if ( $post instanceof WP_Post ) {
			delete_post_thumbnail( (int) $post->ID );
		}
	}

	if ( class_exists( 'HLM_SEO\Content_Bootstrap' ) ) {
		do_action( 'hlm_seo_bootstrap_posts' );
	}

	update_option( 'hlm_blog_featured_v2', 1, false );
}
add_action( 'init', 'hlm_blog_refresh_featured_images_v2', 39 );
