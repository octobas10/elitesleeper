<?php
/**
 * Meta tags - title, description, OG, Twitter, canonical, robots.
 *
 * @package HLM_SEO
 */

namespace HLM_SEO;

defined( 'ABSPATH' ) || exit;

/**
 * Outputs on-page meta when no dedicated SEO plugin is active.
 */
class Meta_Tags {

	/**
	 * Register hooks.
	 */
	public static function init(): void {
		add_filter( 'pre_get_document_title', array( self::class, 'filter_document_title' ), 20 );
		add_filter( 'wp_robots', array( self::class, 'filter_wp_robots' ), 20 );
		add_action( 'wp_head', array( self::class, 'render_meta_tags' ), 1 );
	}

	/**
	 * Filter document title.
	 *
	 * @param string $title Current title.
	 * @return string
	 */
	public static function filter_document_title( string $title ): string {
		if ( ! Compatibility::hlm_handles_meta() ) {
			return $title;
		}

		$custom = Registry::custom_title();
		return $custom ?: $title;
	}

	/**
	 * Merge index/noindex into WordPress robots API (avoids duplicate meta tags).
	 *
	 * @param array<string, bool|string> $robots Robots directives.
	 * @return array<string, bool|string>
	 */
	public static function filter_wp_robots( array $robots ): array {
		if ( ! Compatibility::hlm_handles_meta() ) {
			return $robots;
		}
		if ( Registry::should_index() ) {
			$robots['index']  = true;
			$robots['follow'] = true;
		} else {
			$robots['noindex']  = true;
			$robots['nofollow'] = true;
		}

		return $robots;
	}

	/**
	 * Render meta description, OG, Twitter, and canonical.
	 */
	public static function render_meta_tags(): void {
		if ( ! Compatibility::hlm_handles_meta() ) {
			return;
		}

		$description = Registry::meta_description();
		$canonical   = self::canonical_url();

		if ( $description ) {
			printf(
				'<meta name="description" content="%s" />' . "\n",
				esc_attr( $description )
			);
		}

		if ( $canonical ) {
			printf(
				'<link rel="canonical" href="%s" />' . "\n",
				esc_url( $canonical )
			);
		}

		self::render_og_tags( $description, $canonical );
		self::render_twitter_tags( $description );
	}

	/**
	 * Get canonical URL for current request.
	 *
	 * @return string
	 */
	private static function canonical_url(): string {
		if ( is_singular() ) {
			return get_permalink();
		}

		if ( is_home() && ! is_front_page() ) {
			return get_permalink( (int) get_option( 'page_for_posts' ) );
		}

		if ( is_category() || is_tag() || is_tax() ) {
			$term = get_queried_object();
			if ( $term && ! is_wp_error( $link = get_term_link( $term ) ) ) {
				return $link;
			}
		}

		return '';
	}

	/**
	 * Render Open Graph tags.
	 *
	 * @param string $description Meta description.
	 * @param string $canonical   Canonical URL.
	 */
	private static function render_og_tags( string $description, string $canonical ): void {
		$title = Registry::custom_title() ?: wp_get_document_title();
		$type  = is_singular( 'post' ) ? 'article' : 'website';
		$url   = $canonical ?: ( is_singular() ? get_permalink() : home_url( '/' ) );
		$image = self::social_image();

		printf( '<meta property="og:locale" content="%s" />' . "\n", esc_attr( get_locale() ) );
		printf( '<meta property="og:site_name" content="%s" />' . "\n", esc_attr( get_bloginfo( 'name' ) ) );
		printf( '<meta property="og:type" content="%s" />' . "\n", esc_attr( $type ) );
		printf( '<meta property="og:title" content="%s" />' . "\n", esc_attr( $title ) );
		printf( '<meta property="og:url" content="%s" />' . "\n", esc_url( $url ) );

		if ( $description ) {
			printf( '<meta property="og:description" content="%s" />' . "\n", esc_attr( $description ) );
		}
		if ( $image ) {
			printf( '<meta property="og:image" content="%s" />' . "\n", esc_url( $image ) );
		}
	}

	/**
	 * Render Twitter Card tags.
	 *
	 * @param string $description Meta description.
	 */
	private static function render_twitter_tags( string $description ): void {
		$title = Registry::custom_title() ?: wp_get_document_title();
		$image = self::social_image();

		echo '<meta name="twitter:card" content="summary_large_image" />' . "\n";
		printf( '<meta name="twitter:title" content="%s" />' . "\n", esc_attr( $title ) );

		if ( $description ) {
			printf( '<meta name="twitter:description" content="%s" />' . "\n", esc_attr( $description ) );
		}
		if ( $image ) {
			printf( '<meta name="twitter:image" content="%s" />' . "\n", esc_url( $image ) );
		}
	}

	/**
	 * Resolve social share image.
	 *
	 * @return string
	 */
	private static function social_image(): string {
		if ( is_singular() && has_post_thumbnail() ) {
			$image = get_the_post_thumbnail_url( null, 'large' );
			if ( $image ) {
				return $image;
			}
		}

		$org = hlm_seo_organization_data();
		return $org['logo'] ?? '';
	}
}
