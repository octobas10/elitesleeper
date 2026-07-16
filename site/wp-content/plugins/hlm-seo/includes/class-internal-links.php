<?php
/**
 * Internal linking engine - pillar/cluster, contextual links, related posts.
 *
 * @package HLM_SEO
 */

namespace HLM_SEO;

defined( 'ABSPATH' ) || exit;

/**
 * Backlinko-style contextual internal linking and cluster recirculation.
 */
class Internal_Links {

	/**
	 * Register hooks.
	 */
	public static function init(): void {
		add_filter( 'the_content', array( self::class, 'inject_page_internal_links' ), 15 );
		add_filter( 'the_content', array( self::class, 'inject_cluster_links' ), 20 );
		add_filter( 'the_content', array( self::class, 'append_outbound_refs' ), 25 );
		add_action( 'hlm_seo_after_post_content', array( self::class, 'render_related_cluster' ) );
		add_action( 'kadence_single_after_content', array( self::class, 'render_related_cluster' ) );
	}

	/**
	 * Inject hub-to-spoke internal links on pages from the link map.
	 *
	 * @param string $content Post content.
	 * @return string
	 */
	public static function inject_page_internal_links( string $content ): string {
		if ( ! is_singular( 'page' ) || ! in_the_loop() || ! is_main_query() ) {
			return $content;
		}

		$post = get_queried_object();
		if ( ! $post instanceof \WP_Post ) {
			return $content;
		}

		/**
		 * Allow themes to place internal links in custom layout regions.
		 *
		 * @param bool     $defer Defer default prepend/append injection.
		 * @param \WP_Post $post  Current page.
		 */
		if ( apply_filters( 'hlm_seo_defer_page_internal_links', false, $post ) ) {
			return $content;
		}

		$groups = self::get_page_link_groups( $post );
		if ( empty( $groups['above'] ) && empty( $groups['body'] ) && empty( $groups['cta'] ) ) {
			return $content;
		}

		$output = '';
		if ( ! empty( $groups['above'] ) ) {
			$output .= self::render_page_links_markup( $groups['above'], 'top', __( 'Key pages', 'hlm-seo' ) );
		}
		if ( ! empty( $groups['body'] ) ) {
			$output .= self::render_page_links_markup( $groups['body'], 'body', __( 'Related pages', 'hlm-seo' ) );
		}

		$footer = '';
		if ( ! empty( $groups['cta'] ) ) {
			$footer = self::render_page_links_markup( $groups['cta'], 'cta', __( 'Next steps', 'hlm-seo' ) );
		}

		return $output . $content . $footer;
	}

	/**
	 * Resolve grouped internal links for a page.
	 *
	 * @param \WP_Post|null $post Page object.
	 * @return array{above: array<int, array<string, string>>, body: array<int, array<string, string>>, cta: array<int, array<string, string>>}
	 */
	public static function get_page_link_groups( ?\WP_Post $post = null ): array {
		$groups = array(
			'above' => array(),
			'body'  => array(),
			'cta'   => array(),
		);

		if ( ! $post instanceof \WP_Post ) {
			return $groups;
		}

		$map = hlm_seo_internal_link_map();
		if ( empty( $map[ $post->post_name ] ) ) {
			return $groups;
		}

		foreach ( $map[ $post->post_name ] as $link ) {
			$url = self::resolve_link_url( $link['slug'] ?? '' );
			if ( ! $url ) {
				continue;
			}

			$item = array(
				'url'   => $url,
				'label' => $link['anchor'] ?? self::resolve_link_label( $link['slug'] ?? '' ),
			);

			$place = $link['placement'] ?? 'body';
			if ( 'above_fold' === $place || 'intro' === $place ) {
				$groups['above'][] = $item;
			} elseif ( 'cta' === $place || 'form_footer' === $place ) {
				$groups['cta'][] = $item;
			} else {
				$groups['body'][] = $item;
			}
		}

		return $groups;
	}

	/**
	 * Render grouped page links for a layout variant.
	 *
	 * @param array<int, array<string, string>> $links     Link items.
	 * @param string                            $variant   hero|strip|footer|top|body|cta.
	 * @param string                            $label     Accessible label.
	 * @return string
	 */
	public static function render_page_links( array $links, string $variant, string $label = '' ): string {
		if ( empty( $links ) ) {
			return '';
		}

		ob_start();
		load_template(
			HLM_SEO_DIR . 'templates/page-links.php',
			false,
			array(
				'links'   => $links,
				'variant' => $variant,
				'label'   => $label,
			)
		);

		return (string) ob_get_clean();
	}

	/**
	 * Legacy flat markup for non-deferred injection.
	 *
	 * @param array<int, array<string, string>> $links Link items.
	 * @param string                            $slot  top|body|cta.
	 * @param string                            $label Accessible label.
	 * @return string
	 */
	private static function render_page_links_markup( array $links, string $slot, string $label ): string {
		$html_links = array();
		foreach ( $links as $link ) {
			$html_links[] = sprintf(
				'<a href="%s">%s</a>',
				esc_url( $link['url'] ),
				esc_html( $link['label'] )
			);
		}

		return sprintf(
			'<nav class="hlm-seo-page-links hlm-seo-page-links--%1$s" aria-label="%2$s"><p>%3$s</p></nav>',
			esc_attr( $slot ),
			esc_attr( $label ),
			implode( ' · ', $html_links )
		);
	}

	/**
	 * Inject pillar and sibling links near top of cluster posts.
	 *
	 * @param string $content Post content.
	 * @return string
	 */
	public static function inject_cluster_links( string $content ): string {
		if ( ! is_singular( 'post' ) || ! in_the_loop() || ! is_main_query() ) {
			return $content;
		}

		$post = get_queried_object();
		if ( ! $post instanceof \WP_Post ) {
			return $content;
		}

		/**
		 * Allow themes to render cluster links in a sidebar or custom region.
		 *
		 * @param bool     $defer Defer default content injection.
		 * @param \WP_Post $post  Current post.
		 */
		if ( apply_filters( 'hlm_seo_defer_cluster_links', false, $post ) ) {
			return $content;
		}

		$items = self::get_cluster_link_items( $post );
		if ( empty( $items ) ) {
			return $content;
		}

		return self::render_cluster_nav_markup( $items ) . $content;
	}

	/**
	 * Append authoritative outbound references to cluster posts.
	 *
	 * @param string $content Post content.
	 * @return string
	 */
	public static function append_outbound_refs( string $content ): string {
		if ( ! is_singular( 'post' ) || ! in_the_loop() || ! is_main_query() ) {
			return $content;
		}

		$post = get_queried_object();
		if ( ! $post instanceof \WP_Post ) {
			return $content;
		}

		$cluster = hlm_seo_get_post_cluster( $post->post_name );
		if ( empty( $cluster['outbound_refs'] ) ) {
			return $content;
		}

		return $content . hlm_seo_render_outbound_refs( $cluster['outbound_refs'] );
	}

	/**
	 * Render related cluster posts after content.
	 */
	public static function render_related_cluster(): void {
		$data = self::get_related_cluster_data();
		if ( empty( $data['related'] ) ) {
			return;
		}

		load_template(
			HLM_SEO_DIR . 'templates/related-cluster.php',
			false,
			array(
				'cluster_label' => $data['cluster_label'],
				'related'       => $data['related'],
			)
		);
	}

	/**
	 * Resolve related cluster posts for the current single post.
	 *
	 * @param \WP_Post|null $post Post object.
	 * @return array{cluster_label: string, related: array<int, \WP_Post>}
	 */
	public static function get_related_cluster_data( ?\WP_Post $post = null ): array {
		$data = array(
			'cluster_label' => '',
			'related'       => array(),
		);

		if ( ! $post instanceof \WP_Post ) {
			if ( ! is_singular( 'post' ) ) {
				return $data;
			}

			$post = get_queried_object();
		}

		if ( ! $post instanceof \WP_Post ) {
			return $data;
		}

		$cluster = hlm_seo_get_post_cluster( $post->post_name );
		if ( ! $cluster || empty( $cluster['cluster_id'] ) ) {
			return $data;
		}

		$clusters = hlm_seo_topic_clusters();
		$group    = $clusters[ $cluster['cluster_id'] ] ?? null;
		if ( empty( $group['clusters'] ) ) {
			return $data;
		}

		$data['cluster_label'] = $group['label'] ?? '';

		foreach ( $group['clusters'] as $item ) {
			if ( ( $item['slug'] ?? '' ) === $post->post_name ) {
				continue;
			}

			$related_post = get_page_by_path( $item['slug'], OBJECT, 'post' );
			if ( $related_post ) {
				$data['related'][] = $related_post;
			}
		}

		return $data;
	}

	/**
	 * Resolve sidebar cluster link items for a post.
	 *
	 * @param \WP_Post|null $post Post object.
	 * @return array<int, array{url: string, label: string, featured?: bool}>
	 */
	public static function get_cluster_link_items( ?\WP_Post $post = null ): array {
		if ( ! $post instanceof \WP_Post ) {
			if ( ! is_singular( 'post' ) ) {
				return array();
			}

			$post = get_queried_object();
		}

		if ( ! $post instanceof \WP_Post ) {
			return array();
		}

		$cluster = hlm_seo_get_post_cluster( $post->post_name );
		if ( ! $cluster ) {
			return array();
		}

		$items = array();

		if ( ! empty( $cluster['pillar_slug'] ) ) {
			$pillar = get_page_by_path( $cluster['pillar_slug'] );
			if ( $pillar ) {
				$items[] = array(
					'url'      => get_permalink( $pillar ),
					'label'    => sprintf(
						/* translators: %s: pillar page title */
						__( 'Back to %s', 'hlm-seo' ),
						get_the_title( $pillar )
					),
					'featured' => true,
				);
			}
		}

		if ( ! empty( $cluster['links_to'] ) ) {
			foreach ( $cluster['links_to'] as $target ) {
				if ( is_string( $target ) && $target === ( $cluster['slug'] ?? '' ) ) {
					continue;
				}

				$url = self::resolve_link_url( is_string( $target ) ? $target : '' );
				if ( ! $url ) {
					continue;
				}

				$items[] = array(
					'url'   => $url,
					'label' => self::resolve_link_label( $target ),
				);
			}
		}

		return $items;
	}

	/**
	 * Render cluster navigation markup.
	 *
	 * @param array<int, array{url: string, label: string, featured?: bool}> $items Link items.
	 * @return string
	 */
	public static function render_cluster_nav_markup( array $items ): string {
		if ( empty( $items ) ) {
			return '';
		}

		$links = array();
		foreach ( $items as $item ) {
			$class   = ! empty( $item['featured'] ) ? ' class="hlm-seo-cluster-nav__link--featured"' : '';
			$links[] = sprintf(
				'<a href="%s"%s>%s</a>',
				esc_url( $item['url'] ),
				$class,
				esc_html( $item['label'] )
			);
		}

		return sprintf(
			'<nav class="hlm-seo-cluster-nav" aria-label="%1$s"><div class="hlm-seo-cluster-nav__links">%2$s</div></nav>',
			esc_attr__( 'Related topics', 'hlm-seo' ),
			implode( '', $links )
		);
	}

	/**
	 * Resolve internal link URL from slug.
	 *
	 * @param string $slug Page or post slug.
	 * @return string
	 */
	public static function resolve_link_url( string $slug ): string {
		if ( ! $slug ) {
			return '';
		}

		$page = get_page_by_path( $slug );
		if ( $page ) {
			return get_permalink( $page );
		}

		$post = get_page_by_path( $slug, OBJECT, 'post' );
		if ( $post ) {
			return get_permalink( $post );
		}

		return '';
	}

	/**
	 * Resolve link label from slug or map entry.
	 *
	 * @param string|array<string, string> $target Link target.
	 * @return string
	 */
	private static function resolve_link_label( $target ): string {
		if ( is_array( $target ) ) {
			return $target['anchor'] ?? '';
		}

		$page = get_page_by_path( $target );
		if ( $page ) {
			return get_the_title( $page );
		}

		$post = get_page_by_path( $target, OBJECT, 'post' );
		if ( $post ) {
			return get_the_title( $post );
		}

		return ucwords( str_replace( '-', ' ', $target ) );
	}
}
