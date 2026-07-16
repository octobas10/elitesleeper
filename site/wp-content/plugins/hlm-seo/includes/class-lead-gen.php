<?php
/**
 * Lead-generation page structure - sales-fuel conversion architecture.
 *
 * @package HLM_SEO
 */

namespace HLM_SEO;

defined( 'ABSPATH' ) || exit;

/**
 * Conversion-focused page structure helpers and CTA injection.
 */
class Lead_Gen {

	/**
	 * Register hooks.
	 */
	public static function init(): void {
		add_filter( 'body_class', array( self::class, 'conversion_body_classes' ) );
		add_action( 'wp_footer', array( self::class, 'render_conversion_cta' ), 15 );
		add_filter( 'the_content', array( self::class, 'append_page_faqs' ), 35 );
		add_filter( 'the_content', array( self::class, 'append_conversion_block' ), 30 );
	}

	/**
	 * Add conversion-role body classes for CSS targeting.
	 *
	 * @param array<int, string> $classes Body classes.
	 * @return array<int, string>
	 */
	public static function conversion_body_classes( array $classes ): array {
		if ( ! is_singular() ) {
			return $classes;
		}

		$post = get_queried_object();
		if ( ! $post instanceof \WP_Post ) {
			return $classes;
		}

		$config = Registry::for_post( $post );
		if ( ! empty( $config['page_role'] ) ) {
			$classes[] = 'hlm-seo-role-' . sanitize_html_class( $config['page_role'] );
		}
		if ( ! empty( $config['conversion_goal'] ) ) {
			$classes[] = 'hlm-seo-goal-' . sanitize_html_class( $config['conversion_goal'] );
		}

		return $classes;
	}

	/**
	 * Append conversion CTA block to cluster posts (informational → commercial bridge).
	 *
	 * @param string $content Post content.
	 * @return string
	 */
	public static function append_conversion_block( string $content ): string {
		if ( ! is_singular( 'post' ) || ! in_the_loop() || ! is_main_query() ) {
			return $content;
		}

		$post = get_queried_object();
		if ( ! $post instanceof \WP_Post ) {
			return $content;
		}

		$cluster = hlm_seo_get_post_cluster( $post->post_name );
		if ( ! $cluster ) {
			return $content;
		}

		$intent  = $cluster['intent'] ?? 'informational';
		$is_b2b  = 'commercial' === $intent;
		$cta_url = $is_b2b
			? ( Internal_Links::resolve_link_url( 'services' ) ?: home_url( '/services/' ) )
			: ( Internal_Links::resolve_link_url( 'contact-us' ) ?: home_url( '/contact-us/' ) );

		/**
		 * Filter conversion CTA URL for cluster posts.
		 *
		 * @param string  $cta_url CTA URL.
		 * @param WP_Post $post    Current post.
		 * @param string  $intent  Cluster intent (informational|commercial).
		 */
		$cta_url = apply_filters( 'hlm_seo_conversion_cta_url', $cta_url, $post, $intent );

		if ( $is_b2b ) {
			$heading = __( 'Ready to partner with Higher Learning Marketers?', 'hlm-seo' );
			$copy    = __( 'Explore consent-based education lead generation for universities and colleges across the United States.', 'hlm-seo' );
			$label   = __( 'View Lead Generation Services', 'hlm-seo' );
		} else {
			$heading = __( 'Ready to take the next step?', 'hlm-seo' );
			$copy    = __( 'Connect with an advisor to compare accredited programs and find the right pathway for your goals.', 'hlm-seo' );
			$label   = __( 'Connect With an Advisor', 'hlm-seo' );
		}

		$block = sprintf(
			'<aside class="hlm-seo-conversion" aria-label="%s"><h2>%s</h2><p>%s</p><a class="hlm-seo-conversion__cta" href="%s">%s</a></aside>',
			esc_attr__( 'Get help', 'hlm-seo' ),
			esc_html( $heading ),
			esc_html( $copy ),
			esc_url( $cta_url ),
			esc_html( $label )
		);

		return $content . $block;
	}

	/**
	 * Sticky footer CTA on conversion pages.
	 */
	public static function render_conversion_cta(): void {
		if ( ! is_page() ) {
			return;
		}

		$post = get_queried_object();
		if ( ! $post instanceof \WP_Post ) {
			return;
		}

		$config = Registry::for_post( $post );
		if ( ( $config['page_role'] ?? '' ) !== 'conversion' ) {
			return;
		}

		load_template( HLM_SEO_DIR . 'templates/conversion-cta.php', false, array( 'config' => $config ) );
	}

	/**
	 * Append visible FAQ block on pages with FAQ registry entries.
	 *
	 * @param string $content Post content.
	 * @return string
	 */
	public static function append_page_faqs( string $content ): string {
		if ( ! is_singular( 'page' ) || ! in_the_loop() || ! is_main_query() ) {
			return $content;
		}

		$post = get_queried_object();
		if ( ! $post instanceof \WP_Post ) {
			return $content;
		}

		/**
		 * Allow themes to place FAQ blocks in custom layout regions.
		 *
		 * @param bool     $defer Defer default append injection.
		 * @param \WP_Post $post  Current page.
		 */
		if ( apply_filters( 'hlm_seo_defer_page_faqs', false, $post ) ) {
			return $content;
		}

		$block = self::render_page_faqs();
		if ( '' === $block ) {
			return $content;
		}

		return $content . $block;
	}

	/**
	 * Render FAQ accordion block for the current or supplied items.
	 *
	 * @param array<int, array<string, string>>|null $faqs FAQ items.
	 * @return string
	 */
	public static function render_page_faqs( ?array $faqs = null ): string {
		if ( null === $faqs ) {
			$faqs = hlm_seo_get_current_faqs();
		}

		if ( empty( $faqs ) ) {
			return '';
		}

		ob_start();
		load_template(
			HLM_SEO_DIR . 'templates/faq-block.php',
			false,
			array( 'faqs' => $faqs )
		);

		return (string) ob_get_clean();
	}
}
