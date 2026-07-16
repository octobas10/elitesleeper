<?php
/**
 * robots.txt virtual rules.
 *
 * @package HLM_SEO
 */

namespace HLM_SEO;

defined( 'ABSPATH' ) || exit;

/**
 * Adds crawl directives when no SEO plugin manages robots.txt.
 */
class Robots {

	/**
	 * Register hooks.
	 */
	public static function init(): void {
		add_filter( 'robots_txt', array( self::class, 'filter_robots_txt' ), 10, 2 );
	}

	/**
	 * Append HLM robots rules.
	 *
	 * @param string $output Existing robots.txt output.
	 * @param bool   $public Whether site is public.
	 * @return string
	 */
	public static function filter_robots_txt( string $output, bool $public ): string {
		if ( ! Compatibility::hlm_handles_robots() ) {
			return $output;
		}

		unset( $public );

		$rules = array(
			'',
			'# HLM SEO Ecosystem',
			'User-agent: *',
			'Disallow: /wp-admin/',
			'Allow: /wp-admin/admin-ajax.php',
			'Disallow: /?s=',
			'Disallow: /search/',
		);

		/**
		 * Filter additional robots.txt rules.
		 *
		 * @param array<int, string> $rules Robots rules lines.
		 */
		$rules = apply_filters( 'hlm_seo_robots_rules', $rules );

		return trim( $output ) . "\n" . implode( "\n", $rules ) . "\n";
	}
}
