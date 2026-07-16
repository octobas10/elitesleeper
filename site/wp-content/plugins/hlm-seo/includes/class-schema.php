<?php
/**
 * JSON-LD structured data output.
 *
 * @package HLM_SEO
 */

namespace HLM_SEO;

defined( 'ABSPATH' ) || exit;

/**
 * Schema.org markup for Organization, WebSite, WebPage, Article, BreadcrumbList.
 */
class Schema {

	/**
	 * Register hooks.
	 */
	public static function init(): void {
		add_action( 'wp_head', array( self::class, 'render_json_ld' ), 5 );
	}

	/**
	 * Output JSON-LD graph in head.
	 */
	public static function render_json_ld(): void {
		if ( ! Compatibility::hlm_handles_schema() ) {
			return;
		}

		$graph = array_filter(
			array(
				self::organization_schema(),
				self::website_schema(),
				self::webpage_schema(),
				self::article_schema(),
				self::breadcrumb_schema(),
				self::service_schema(),
			)
		);

		if ( empty( $graph ) ) {
			return;
		}

		$payload = array(
			'@context' => 'https://schema.org',
			'@graph'   => array_values( $graph ),
		);

		echo '<script type="application/ld+json">' . wp_json_encode( $payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>' . "\n";
	}

	/**
	 * Organization + LocalBusiness schema.
	 *
	 * @return array<string, mixed>|null
	 */
	public static function organization_schema(): ?array {
		$org = hlm_seo_organization_data();
		if ( empty( $org['name'] ) ) {
			return null;
		}

		$schema = array(
			'@type'       => array( 'Organization', 'LocalBusiness' ),
			'@id'         => home_url( '/#organization' ),
			'name'        => $org['name'],
			'url'         => $org['url'] ?? home_url( '/' ),
			'description' => $org['description'] ?? '',
		);

		if ( ! empty( $org['logo'] ) ) {
			$schema['logo'] = array(
				'@type' => 'ImageObject',
				'url'   => $org['logo'],
			);
		}
		if ( ! empty( $org['email'] ) ) {
			$schema['email'] = $org['email'];
		}
		if ( ! empty( $org['telephone'] ) ) {
			$schema['telephone'] = $org['telephone'];
		}
		if ( ! empty( $org['address']['street'] ) ) {
			$schema['address'] = array(
				'@type'           => 'PostalAddress',
				'streetAddress'   => $org['address']['street'],
				'addressLocality' => $org['address']['city'] ?? '',
				'addressRegion'   => $org['address']['region'] ?? '',
				'postalCode'      => $org['address']['postal'] ?? '',
				'addressCountry'  => $org['address']['country'] ?? '',
			);
		}
		if ( ! empty( $org['same_as'] ) ) {
			$schema['sameAs'] = $org['same_as'];
		}
		if ( ! empty( $org['knows_about'] ) ) {
			$schema['knowsAbout'] = $org['knows_about'];
		}

		return $schema;
	}

	/**
	 * WebSite schema with SearchAction.
	 *
	 * @return array<string, mixed>|null
	 */
	public static function website_schema(): ?array {
		return array(
			'@type'           => 'WebSite',
			'@id'             => home_url( '/#website' ),
			'url'             => home_url( '/' ),
			'name'            => get_bloginfo( 'name' ),
			'description'     => get_bloginfo( 'description' ),
			'publisher'       => array( '@id' => home_url( '/#organization' ) ),
			'potentialAction' => array(
				'@type'       => 'SearchAction',
				'target'      => home_url( '/?s={search_term_string}' ),
				'query-input' => 'required name=search_term_string',
			),
		);
	}

	/**
	 * WebPage schema for current view.
	 *
	 * @return array<string, mixed>|null
	 */
	public static function webpage_schema(): ?array {
		if ( ! is_singular() && ! ( is_home() && ! is_front_page() ) ) {
			return null;
		}

		$config = Registry::for_current();
		$url    = is_singular() ? get_permalink() : get_permalink( (int) get_option( 'page_for_posts' ) );

		return array(
			'@type'       => $config['schema_type'] ?? 'WebPage',
			'@id'         => $url . '#webpage',
			'url'         => $url,
			'name'        => Registry::custom_title() ?: wp_get_document_title(),
			'description' => Registry::meta_description(),
			'isPartOf'    => array( '@id' => home_url( '/#website' ) ),
			'inLanguage'  => get_bloginfo( 'language' ),
		);
	}

	/**
	 * Article schema for blog posts.
	 *
	 * @return array<string, mixed>|null
	 */
	public static function article_schema(): ?array {
		if ( ! is_singular( 'post' ) ) {
			return null;
		}

		$post = get_queried_object();
		if ( ! $post instanceof \WP_Post ) {
			return null;
		}

		$schema = array(
			'@type'            => 'Article',
			'@id'              => get_permalink( $post ) . '#article',
			'headline'         => get_the_title( $post ),
			'description'      => Registry::meta_description(),
			'datePublished'    => get_the_date( 'c', $post ),
			'dateModified'     => get_the_modified_date( 'c', $post ),
			'author'           => array(
				'@type' => 'Organization',
				'name'  => get_bloginfo( 'name' ),
			),
			'publisher'        => array( '@id' => home_url( '/#organization' ) ),
			'mainEntityOfPage' => array( '@id' => get_permalink( $post ) . '#webpage' ),
			'inLanguage'       => get_bloginfo( 'language' ),
		);

		if ( has_post_thumbnail( $post ) ) {
			$schema['image'] = get_the_post_thumbnail_url( $post, 'large' );
		}

		return $schema;
	}

	/**
	 * BreadcrumbList schema.
	 *
	 * @return array<string, mixed>|null
	 */
	public static function breadcrumb_schema(): ?array {
		$crumbs = Breadcrumbs::get_trail();
		if ( count( $crumbs ) < 2 ) {
			return null;
		}

		$items = array();
		foreach ( $crumbs as $index => $crumb ) {
			$items[] = array(
				'@type'    => 'ListItem',
				'position' => $index + 1,
				'name'     => $crumb['label'],
				'item'     => $crumb['url'] ?: null,
			);
		}

		return array(
			'@type'           => 'BreadcrumbList',
			'@id'             => ( is_singular() ? get_permalink() : home_url( '/' ) ) . '#breadcrumb',
			'itemListElement' => $items,
		);
	}

	/**
	 * Service schema for services pillar pages.
	 *
	 * @return array<string, mixed>|null
	 */
	public static function service_schema(): ?array {
		if ( ! is_singular( 'page' ) ) {
			return null;
		}

		$post = get_queried_object();
		if ( ! $post instanceof \WP_Post ) {
			return null;
		}

		$config = Registry::for_post( $post );
		if ( ( $config['schema_type'] ?? '' ) !== 'Service' ) {
			return null;
		}

		$org = hlm_seo_organization_data();

		return array(
			'@type'       => 'Service',
			'@id'         => get_permalink( $post ) . '#service',
			'name'        => Registry::custom_title() ?: wp_get_document_title(),
			'description' => Registry::meta_description(),
			'provider'    => array( '@id' => home_url( '/#organization' ) ),
			'areaServed'  => $org['area_served'] ?? '',
			'url'         => get_permalink( $post ),
		);
	}

	/**
	 * Slim SEO organization schema filter.
	 *
	 * @param array<string, mixed> $schema Existing schema.
	 * @return array<string, mixed>
	 */
	public static function filter_slim_organization_schema( array $schema ): array {
		$custom = self::organization_schema();
		return $custom ? array_merge( $schema, $custom ) : $schema;
	}

	/**
	 * Slim SEO webpage schema filter.
	 *
	 * Slim SEO passes only the schema array - resolve post from current query.
	 *
	 * @param array<string, mixed> $schema Existing schema.
	 * @return array<string, mixed>
	 */
	public static function filter_slim_webpage_schema( array $schema ): array {
		$post_id = get_queried_object_id();
		$post    = $post_id ? get_post( $post_id ) : null;

		if ( ! $post instanceof \WP_Post ) {
			return $schema;
		}

		$config = Registry::for_post( $post );
		if ( ! empty( $config['schema_type'] ) ) {
			$schema['@type'] = $config['schema_type'];
		}

		return $schema;
	}

	/**
	 * Slim SEO article schema filter - accurate word count from rendered blocks.
	 *
	 * @param array<string, mixed> $schema Existing schema.
	 * @return array<string, mixed>
	 */
	public static function filter_slim_article_schema( array $schema ): array {
		$post_id = get_queried_object_id();
		$post    = $post_id ? get_post( $post_id ) : null;

		if ( ! $post instanceof \WP_Post || 'post' !== $post->post_type ) {
			return $schema;
		}

		$plain = hlm_seo_get_plain_text( $post->post_content, $post );
		if ( '' !== $plain ) {
			$schema['wordCount'] = str_word_count( $plain );
		}

		if ( empty( $schema['description'] ) ) {
			$schema['description'] = Registry::meta_description();
		}

		return $schema;
	}

	/**
	 * FAQPage schema for pages with FAQ registry entries.
	 *
	 * @return array<string, mixed>|null
	 */
	public static function faq_schema(): ?array {
		$faqs = hlm_seo_get_current_faqs();
		if ( empty( $faqs ) ) {
			return null;
		}

		$entities = array();
		foreach ( $faqs as $faq ) {
			if ( empty( $faq['question'] ) || empty( $faq['answer'] ) ) {
				continue;
			}
			$entities[] = array(
				'@type'          => 'Question',
				'name'           => $faq['question'],
				'acceptedAnswer' => array(
					'@type' => 'Answer',
					'text'  => $faq['answer'],
				),
			);
		}

		if ( empty( $entities ) ) {
			return null;
		}

		return array(
			'@type'      => 'FAQPage',
			'@id'        => ( is_singular() ? get_permalink() : home_url( '/' ) ) . '#faq',
			'mainEntity' => $entities,
		);
	}

	/**
	 * Output FAQ JSON-LD (runs even when Slim SEO handles other schema).
	 */
	public static function render_faq_json_ld(): void {
		$schema = self::faq_schema();
		if ( ! $schema ) {
			return;
		}

		$payload = array(
			'@context' => 'https://schema.org',
			'@graph'   => array( $schema ),
		);

		echo '<script type="application/ld+json">' . wp_json_encode( $payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>' . "\n";
	}
}
