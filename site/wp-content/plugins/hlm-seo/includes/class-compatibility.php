<?php
/**
 * SEO plugin compatibility layer.
 *
 * @package HLM_SEO
 */

namespace HLM_SEO;

defined( 'ABSPATH' ) || exit;

/**
 * Detect active third-party SEO plugins and delegate output accordingly.
 */
class Compatibility {

	/**
	 * Active SEO plugin slug or empty when HLM handles output.
	 *
	 * @return string
	 */
	public static function active_plugin(): string {
		if ( defined( 'SLIM_SEO_VER' ) || defined( 'SLIM_SEO_VERSION' ) ) {
			return 'slim-seo';
		}
		if ( defined( 'WPSEO_VERSION' ) ) {
			return 'yoast';
		}
		if ( defined( 'RANK_MATH_VERSION' ) ) {
			return 'rank-math';
		}
		if ( defined( 'SEOPRESS_VERSION' ) ) {
			return 'seopress';
		}
		if ( defined( 'AIOSEO_VERSION' ) ) {
			return 'aioseo';
		}

		return '';
	}

	/**
	 * Whether HLM should output meta tags.
	 *
	 * @return bool
	 */
	public static function hlm_handles_meta(): bool {
		return (bool) apply_filters( 'hlm_seo_handles_meta', '' === self::active_plugin() );
	}

	/**
	 * Whether HLM should output JSON-LD schema.
	 *
	 * @return bool
	 */
	public static function hlm_handles_schema(): bool {
		return (bool) apply_filters( 'hlm_seo_handles_schema', '' === self::active_plugin() );
	}

	/**
	 * Whether HLM should manage robots.txt rules.
	 *
	 * @return bool
	 */
	public static function hlm_handles_robots(): bool {
		return (bool) apply_filters( 'hlm_seo_handles_robots', '' === self::active_plugin() );
	}

	/**
	 * Register Slim SEO integration filters when that plugin is active.
	 */
	public static function register_delegation_hooks(): void {
		if ( 'slim-seo' !== self::active_plugin() ) {
			return;
		}

		add_filter( 'slim_seo_meta_title', array( Registry::class, 'filter_slim_meta_title' ), 10, 2 );
		add_filter( 'slim_seo_meta_description', array( Registry::class, 'filter_slim_meta_description' ), 10, 2 );
		add_filter( 'slim_seo_post_content', array( Content_Parser::class, 'filter_slim_post_content' ), 10, 2 );
		add_filter( 'slim_seo_schema_webpage', array( Schema::class, 'filter_slim_webpage_schema' ) );
		add_filter( 'slim_seo_schema_organization', array( Schema::class, 'filter_slim_organization_schema' ) );
		add_filter( 'slim_seo_schema_article', array( Schema::class, 'filter_slim_article_schema' ) );
	}
}
