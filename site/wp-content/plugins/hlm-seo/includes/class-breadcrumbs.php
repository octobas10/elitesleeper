<?php
/**
 * Theme-agnostic breadcrumb trail.
 *
 * @package HLM_SEO
 */

namespace HLM_SEO;

defined( 'ABSPATH' ) || exit;

/**
 * BreadcrumbList renderer and trail builder.
 */
class Breadcrumbs {

	/**
	 * Register hooks.
	 */
	public static function init(): void {
		add_action( 'hlm_seo_breadcrumbs', array( self::class, 'render' ) );
		add_action( 'kadence_before_content', array( self::class, 'maybe_render_kadence' ), 5 );
		add_filter( 'the_content', array( self::class, 'prepend_breadcrumbs_to_content' ), 5 );
	}

	/**
	 * Render breadcrumbs when theme fires custom hook.
	 */
	public static function render(): void {
		$trail = self::get_trail();
		if ( count( $trail ) < 2 ) {
			return;
		}

		load_template( HLM_SEO_DIR . 'templates/breadcrumbs.php', false, array( 'trail' => $trail ) );
	}

	/**
	 * Kadence-compatible breadcrumb injection.
	 */
	public static function maybe_render_kadence(): void {
		if ( is_front_page() ) {
			return;
		}

		self::render();
	}

	/**
	 * Fallback breadcrumb injection for themes without hooks.
	 *
	 * @param string $content Post content.
	 * @return string
	 */
	public static function prepend_breadcrumbs_to_content( string $content ): string {
		if ( ! is_singular( 'post' ) || ! in_the_loop() || ! is_main_query() ) {
			return $content;
		}

		if ( did_action( 'hlm_seo_breadcrumbs' ) || did_action( 'kadence_before_content' ) ) {
			return $content;
		}

		ob_start();
		self::render();
		$crumbs = ob_get_clean();

		return $crumbs . $content;
	}

	/**
	 * Build breadcrumb trail for current view.
	 *
	 * @return array<int, array<string, string>>
	 */
	public static function get_trail(): array {
		$trail = array(
			array(
				'label' => __( 'Home', 'hlm-seo' ),
				'url'   => home_url( '/' ),
			),
		);

		if ( is_singular( 'post' ) ) {
			$post    = get_queried_object();
			$cluster = $post instanceof \WP_Post ? hlm_seo_get_post_cluster( $post->post_name ) : null;

			if ( $cluster && ! empty( $cluster['pillar_slug'] ) ) {
				$pillar = get_page_by_path( $cluster['pillar_slug'] );
				if ( $pillar ) {
					$trail[] = array(
						'label' => get_the_title( $pillar ),
						'url'   => get_permalink( $pillar ),
					);
				}
			} else {
				$blog_id = (int) get_option( 'page_for_posts' );
				if ( $blog_id ) {
					$trail[] = array(
						'label' => get_the_title( $blog_id ),
						'url'   => get_permalink( $blog_id ),
					);
				}
			}

			$trail[] = array(
				'label' => get_the_title(),
				'url'   => '',
			);
		} elseif ( is_page() ) {
			$trail[] = array(
				'label' => get_the_title(),
				'url'   => '',
			);
		} elseif ( is_home() && ! is_front_page() ) {
			$trail[] = array(
				'label' => get_the_title( (int) get_option( 'page_for_posts' ) ),
				'url'   => '',
			);
		}

		/**
		 * Filter breadcrumb trail.
		 *
		 * @param array<int, array<string, string>> $trail Breadcrumb items.
		 */
		return apply_filters( 'hlm_seo_breadcrumb_trail', $trail );
	}
}
