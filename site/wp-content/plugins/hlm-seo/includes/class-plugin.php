<?php
/**
 * Plugin bootstrap.
 *
 * @package HLM_SEO
 */

namespace HLM_SEO;

defined( 'ABSPATH' ) || exit;

/**
 * Main plugin orchestrator.
 */
final class Plugin {

	/**
	 * Singleton instance.
	 *
	 * @var self|null
	 */
	private static ?self $instance = null;

	/**
	 * Get singleton.
	 *
	 * @return self
	 */
	public static function instance(): self {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Constructor.
	 */
	private function __construct() {
		$this->load_dependencies();
		$this->init_components();
	}

	/**
	 * Load class files.
	 */
	private function load_dependencies(): void {
		require_once HLM_SEO_DIR . 'includes/helpers.php';
		require_once HLM_SEO_DIR . 'includes/class-compatibility.php';
		require_once HLM_SEO_DIR . 'includes/config/page-registry.php';
		require_once HLM_SEO_DIR . 'includes/config/topic-clusters.php';
		require_once HLM_SEO_DIR . 'includes/config/outbound-authority.php';
		require_once HLM_SEO_DIR . 'includes/config/faq-registry.php';
		require_once HLM_SEO_DIR . 'includes/class-registry.php';
		require_once HLM_SEO_DIR . 'includes/class-meta-tags.php';
		require_once HLM_SEO_DIR . 'includes/class-schema.php';
		require_once HLM_SEO_DIR . 'includes/class-sitemap.php';
		require_once HLM_SEO_DIR . 'includes/class-robots.php';
		require_once HLM_SEO_DIR . 'includes/class-breadcrumbs.php';
		require_once HLM_SEO_DIR . 'includes/class-internal-links.php';
		require_once HLM_SEO_DIR . 'includes/class-lead-gen.php';
		require_once HLM_SEO_DIR . 'includes/class-content-bootstrap.php';
		require_once HLM_SEO_DIR . 'includes/class-content-parser.php';
	}

	/**
	 * Initialise all SEO components.
	 */
	private function init_components(): void {
		add_action( 'plugins_loaded', array( Compatibility::class, 'register_delegation_hooks' ), 20 );

		Meta_Tags::init();
		Schema::init();
		add_action( 'wp_head', array( Schema::class, 'render_faq_json_ld' ), 6 );
		Sitemap::init();
		Robots::init();
		Breadcrumbs::init();
		Internal_Links::init();
		Lead_Gen::init();
		Content_Bootstrap::init();

		add_filter( 'hlm_seo_cluster_post_content', 'hlm_seo_normalize_cluster_post_dashes', 999 );
		add_filter( 'hlm_seo_page_faqs', 'hlm_seo_normalize_page_faq_dashes', 999 );

		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_assets' ) );
	}

	/**
	 * Enqueue front-end SEO assets.
	 */
	public function enqueue_assets(): void {
		wp_enqueue_style(
			'hlm-seo',
			HLM_SEO_URL . 'assets/hlm-seo.css',
			array(),
			HLM_SEO_VERSION
		);
	}
}
