<?php
/**
 * HLM Kadence theme bridge - extends universal hlm-seo plugin with site-specific config.
 *
 * @package HLM_Kadence
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register HLM-specific SEO data with the universal plugin via filters.
 */
function hlm_kadence_seo_bridge_init(): void {
	if ( ! class_exists( 'HLM_SEO\Plugin' ) ) {
		return;
	}

	add_filter( 'hlm_seo_page_registry', 'hlm_kadence_seo_page_registry' );
	add_filter( 'hlm_seo_organization_data', 'hlm_kadence_seo_organization_data' );
	add_filter( 'hlm_seo_topic_clusters', 'hlm_kadence_seo_topic_clusters' );
	add_filter( 'hlm_seo_internal_link_map', 'hlm_kadence_seo_internal_link_map' );
	add_filter( 'hlm_seo_outbound_authority', 'hlm_kadence_seo_outbound_authority' );
	add_filter( 'hlm_seo_cluster_post_content', 'hlm_kadence_seo_cluster_post_content' );
	add_filter( 'hlm_seo_page_faqs', 'hlm_kadence_seo_page_faqs' );
}
add_action( 'after_setup_theme', 'hlm_kadence_seo_bridge_init', 20 );

/**
 * Move SEO breadcrumbs from the header strip into HLM page heroes.
 */
function hlm_kadence_relocate_breadcrumbs(): void {
	if ( ! class_exists( 'HLM_SEO\Breadcrumbs' ) || ! hlm_kadence_is_theme_shell() ) {
		return;
	}

	remove_action( 'kadence_before_content', array( 'HLM_SEO\Breadcrumbs', 'maybe_render_kadence' ), 5 );
}
add_action( 'wp', 'hlm_kadence_relocate_breadcrumbs', 20 );

/**
 * Render breadcrumbs inside the page hero (on-dark styling).
 */
function hlm_kadence_render_hero_breadcrumbs(): void {
	if ( ! class_exists( 'HLM_SEO\Breadcrumbs' ) || ! defined( 'HLM_SEO_DIR' ) ) {
		return;
	}

	$trail = \HLM_SEO\Breadcrumbs::get_trail();

	// Current page title is already the hero headline on single posts and service detail pages.
	if ( ( is_singular( 'post' ) || ( function_exists( 'hlm_kadence_is_service_detail_page' ) && hlm_kadence_is_service_detail_page() ) ) && count( $trail ) > 1 ) {
		array_pop( $trail );
	}

	if ( count( $trail ) < 2 ) {
		return;
	}

	load_template( HLM_SEO_DIR . 'templates/breadcrumbs.php', false, array( 'trail' => $trail ) );
}

/**
 * Format SEO anchor text for on-page display (preserve acronyms).
 *
 * @param string $label Raw anchor label.
 * @return string
 */
function hlm_kadence_format_seo_link_label( string $label ): string {
	$label = trim( wp_strip_all_tags( $label ) );
	if ( '' === $label ) {
		return '';
	}

	$words = preg_split( '/\s+/', $label ) ?: array();
	$words = array_map(
		static function ( string $word ): string {
			if ( preg_match( '/^[A-Z0-9]{2,}$/', $word ) ) {
				return $word;
			}

			return ucfirst( strtolower( $word ) );
		},
		$words
	);

	return implode( ' ', $words );
}

/**
 * Defer flat SEO link injection - theme places links in layout regions.
 *
 * @param bool     $defer Whether to defer injection.
 * @param WP_Post  $post  Current page.
 * @return bool
 */
function hlm_kadence_defer_seo_page_links( bool $defer, WP_Post $post ): bool {
	if ( hlm_kadence_is_theme_shell() && 'page' === $post->post_type ) {
		return true;
	}

	return $defer;
}
add_filter( 'hlm_seo_defer_page_internal_links', 'hlm_kadence_defer_seo_page_links', 10, 2 );

/**
 * Defer default FAQ append - theme places FAQs before the CTA band.
 *
 * @param bool     $defer Whether to defer injection.
 * @param WP_Post  $post  Current page.
 * @return bool
 */
function hlm_kadence_defer_seo_page_faqs( bool $defer, WP_Post $post ): bool {
	if ( hlm_kadence_is_theme_shell() && 'page' === $post->post_type ) {
		return true;
	}

	return $defer;
}
add_filter( 'hlm_seo_defer_page_faqs', 'hlm_kadence_defer_seo_page_faqs', 10, 2 );

/**
 * Place SEO FAQ block before the page CTA on HLM pages.
 *
 * @param string $content Page content.
 * @return string
 */
function hlm_kadence_place_seo_page_faqs( string $content ): string {
	if ( ! is_singular( 'page' ) || ! in_the_loop() || ! is_main_query() || ! hlm_kadence_is_theme_shell() ) {
		return $content;
	}

	if ( ! class_exists( 'HLM_SEO\Lead_Gen' ) || ! function_exists( 'hlm_seo_get_current_faqs' ) ) {
		return $content;
	}

	$faqs = hlm_seo_get_current_faqs();
	if ( empty( $faqs ) ) {
		return $content;
	}

	$block = \HLM_SEO\Lead_Gen::render_page_faqs( $faqs );
	if ( '' === $block ) {
		return $content;
	}

	$updated = hlm_kadence_insert_before_last_block( $content, $block, 'hlm-cta' );

	return $updated ?? ( $content . $block );
}
add_filter( 'the_content', 'hlm_kadence_place_seo_page_faqs', 16 );

/**
 * Render the primary above-the-fold SEO link inside page heroes.
 */
function hlm_kadence_render_seo_hero_links(): void {
	if ( ! is_singular( 'page' ) || ! class_exists( 'HLM_SEO\Internal_Links' ) ) {
		return;
	}

	$post = get_queried_object();
	if ( ! $post instanceof WP_Post ) {
		return;
	}

	$groups = \HLM_SEO\Internal_Links::get_page_link_groups( $post );
	if ( empty( $groups['above'] ) ) {
		return;
	}

	// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in template.
	echo \HLM_SEO\Internal_Links::render_page_links(
		array( $groups['above'][0] ),
		'hero',
		__( 'Key pages', 'hlm-seo' )
	);
}

/**
 * Place SEO internal links in designed layout regions on HLM pages.
 *
 * @param string $content Page content.
 * @return string
 */
function hlm_kadence_place_seo_page_links( string $content ): string {
	if ( ! is_singular( 'page' ) || ! in_the_loop() || ! is_main_query() || ! hlm_kadence_is_theme_shell() ) {
		return $content;
	}

	$post = get_queried_object();
	if ( ! $post instanceof WP_Post || ! class_exists( 'HLM_SEO\Internal_Links' ) ) {
		return $content;
	}

	$is_front = is_front_page();

	$groups        = \HLM_SEO\Internal_Links::get_page_link_groups( $post );
	$has_page_hero = str_contains( $content, 'hlm-page-hero' );
	$has_page_cta  = str_contains( $content, 'hlm-cta' );
	$strip_links   = $is_front ? array() : $groups['body'];

	// Inner pages: hero + designed CTA already cover navigation — skip duplicate SEO bands.
	if ( ! $is_front && $has_page_hero ) {
		$strip_links = array();
	}

	if ( ! $is_front && $has_page_hero && count( $groups['above'] ) > 1 ) {
		$strip_links = array_merge( array_slice( $groups['above'], 1 ), $strip_links );
	} elseif ( ! $is_front && ! $has_page_hero ) {
		$strip_links = array_merge( $groups['above'], $strip_links );
	}

	if ( $strip_links && ! ( ! $is_front && $has_page_hero ) ) {
		$strip = \HLM_SEO\Internal_Links::render_page_links(
			$strip_links,
			'strip',
			__( 'Related pages', 'hlm-seo' )
		);

		$updated = hlm_kadence_insert_after_first_hero( $content, $strip );
		$content = $updated ?? ( $strip . $content );
	}

	if ( ! $is_front && ! empty( $groups['cta'] ) && ! $has_page_cta ) {
		$footer = \HLM_SEO\Internal_Links::render_page_links(
			$groups['cta'],
			'footer',
			__( 'Next steps', 'hlm-seo' )
		);

		$updated = hlm_kadence_insert_before_last_block( $content, $footer, 'hlm-cta' );
		$content = $updated ?? ( $content . $footer );
	}

	return $content;
}
add_filter( 'the_content', 'hlm_kadence_place_seo_page_links', 17 );

/**
 * Insert markup immediately after the first HLM hero section.
 *
 * @param string $content HTML content.
 * @param string $html    Markup to insert.
 * @return string|null
 */
function hlm_kadence_insert_after_first_hero( string $content, string $html ): ?string {
	if ( preg_match( '/(<section[^>]*\bclass="[^"]*\bhlm-(?:page-hero|hero)\b[^"]*"[^>]*>.*?<\/section>\s*)/s', $content, $matches, PREG_OFFSET_CAPTURE ) ) {
		$pos = $matches[0][1] + strlen( $matches[0][0] );

		return substr( $content, 0, $pos ) . $html . substr( $content, $pos );
	}

	return null;
}

/**
 * Insert markup before the first block section matching a class token.
 *
 * @param string $content     HTML content.
 * @param string $html        Markup to insert.
 * @param string $class_token Section class token (e.g. hlm-services).
 * @return string|null
 */
function hlm_kadence_insert_before_first_block( string $content, string $html, string $class_token ): ?string {
	$pattern = '/<section[^>]*\bclass="[^"]*\b' . preg_quote( $class_token, '/' ) . '\b[^"]*"[^>]*>/s';

	if ( ! preg_match( $pattern, $content, $matches, PREG_OFFSET_CAPTURE ) ) {
		return null;
	}

	$pos = $matches[0][1];

	return substr( $content, 0, $pos ) . $html . substr( $content, $pos );
}

/**
 * Insert markup before the last block section matching a class token.
 *
 * @param string $content     HTML content.
 * @param string $html        Markup to insert.
 * @param string $class_token Section class token (e.g. hlm-cta).
 * @return string|null
 */
function hlm_kadence_insert_before_last_block( string $content, string $html, string $class_token ): ?string {
	$pattern = '/<section[^>]*\bclass="[^"]*\b' . preg_quote( $class_token, '/' ) . '\b[^"]*"[^>]*>/s';

	if ( ! preg_match_all( $pattern, $content, $matches, PREG_OFFSET_CAPTURE ) ) {
		return null;
	}

	$last = end( $matches[0] );
	$pos  = $last[1];

	return substr( $content, 0, $pos ) . $html . substr( $content, $pos );
}

/**
 * HLM page SEO registry.
 *
 * @param array<string, array<string, mixed>> $defaults Existing registry.
 * @return array<string, array<string, mixed>>
 */
function hlm_kadence_seo_page_registry( array $defaults ): array {
	return array_merge(
		$defaults,
		array(
			'home' => array(
				'title'           => 'Higher Learning Marketers - Education Lead Generation',
				'description'     => 'Connect with top universities and programs. Find your perfect educational path or partner with us to reach motivated students.',
				'focus_keyword'   => 'education lead generation',
				'schema_type'     => 'WebPage',
				'page_role'       => 'hub',
				'conversion_goal' => 'dual_audience',
				'index'           => true,
				'priority'        => 1.0,
				'changefreq'      => 'weekly',
			),
			'about-us' => array(
				'title'           => 'About Us - Higher Learning Marketers',
				'description'     => 'Meet the team behind Higher Learning Marketers. Over a decade of connecting students with top educational institutions.',
				'focus_keyword'   => 'education marketing company',
				'schema_type'     => 'AboutPage',
				'page_role'       => 'trust',
				'conversion_goal' => 'brand_credibility',
				'index'           => true,
				'priority'        => 0.8,
				'changefreq'      => 'monthly',
			),
			'services' => array(
				'title'           => 'Education Lead Generation Services - Higher Learning Marketers',
				'description'     => 'Premium education lead generation services. From targeted campaigns to full-funnel enrollment marketing for universities and colleges.',
				'focus_keyword'   => 'education lead generation services',
				'schema_type'     => 'Service',
				'page_role'       => 'pillar',
				'conversion_goal' => 'university_inquiry',
				'index'           => true,
				'priority'        => 0.9,
				'changefreq'      => 'monthly',
			),
			'affiliations' => array(
				'title'           => 'Affiliations and Partnerships - Higher Learning Marketers',
				'description'     => 'Strategic education partnerships and affiliations. Higher Learning Marketers works with U.S. universities, colleges, and organizations to connect students with accredited programs.',
				'focus_keyword'   => 'education partnerships',
				'schema_type'     => 'CollectionPage',
				'page_role'       => 'trust',
				'conversion_goal' => 'social_proof',
				'index'           => true,
				'priority'        => 0.7,
				'changefreq'      => 'monthly',
			),
			'contact-us' => array(
				'title'           => 'Contact Us - Higher Learning Marketers',
				'description'     => 'Get in touch with Higher Learning Marketers. Partner with us for qualified education leads or find the right program for your career goals.',
				'focus_keyword'   => 'contact education lead generation',
				'schema_type'     => 'ContactPage',
				'page_role'       => 'conversion',
				'conversion_goal' => 'form_submission',
				'index'           => true,
				'priority'        => 0.8,
				'changefreq'      => 'monthly',
			),
			'blog' => array(
				'title'           => 'Blog - Higher Education Insights | Higher Learning Marketers',
				'description'     => 'Insights from Higher Learning Marketers on education lead generation, student pathways, university partnerships, and enrollment marketing.',
				'focus_keyword'   => 'higher education blog',
				'schema_type'     => 'Blog',
				'page_role'       => 'cluster_hub',
				'conversion_goal' => 'content_to_lead',
				'index'           => true,
				'priority'        => 0.8,
				'changefreq'      => 'weekly',
			),
			'privacy-policy' => array(
				'title'           => 'Privacy Policy - Higher Learning Marketers',
				'description'     => 'How Higher Learning Marketers collects, uses, and protects your data. TCPA consent, opt-out rights, California privacy disclosures, and consent-based lead generation practices.',
				'focus_keyword'   => 'privacy policy',
				'schema_type'     => 'WebPage',
				'page_role'       => 'legal',
				'conversion_goal' => 'compliance',
				'index'           => true,
				'priority'        => 0.3,
				'changefreq'      => 'yearly',
			),
		),
		hlm_kadence_seo_service_page_registry()
	);
}

/**
 * HLM organization schema data.
 *
 * @param array<string, mixed> $defaults Existing org data.
 * @return array<string, mixed>
 */
function hlm_kadence_seo_organization_data( array $defaults ): array {
	$logo = defined( 'HLM_KADENCE_URI' ) ? HLM_KADENCE_URI . '/assets/hlm/logo.svg' : ( $defaults['logo'] ?? '' );

	return array_merge(
		$defaults,
		array(
			'name'        => 'Higher Learning Marketers',
			'legal_name'  => 'Higher Learning Marketers',
			'url'         => home_url( '/' ),
			'logo'        => $logo,
			'description' => 'Premium education lead generation connecting institutions with high-intent prospective students.',
			'email'       => 'support@higherlearningmarketers.com',
			'telephone'   => '+1-718-685-2575',
			'address'     => array(
				'street'  => '138-07 82nd Drive',
				'city'    => 'Briarwood',
				'region'  => 'NY',
				'postal'  => '11435',
				'country' => 'US',
			),
			'area_served' => 'US',
			'knows_about' => array(
				'Education lead generation',
				'University enrollment marketing',
				'Student recruitment',
				'Higher education partnerships',
			),
		)
	);
}

/**
 * HLM topic clusters.
 *
 * @param array<string, array<string, mixed>> $defaults Existing clusters.
 * @return array<string, array<string, mixed>>
 */
function hlm_kadence_seo_topic_clusters( array $defaults ): array {
	return array_merge( $defaults, array(
		'student-pathways' => array(
			'label'       => 'Student Pathways',
			'pillar_slug' => 'blog',
			'pillar_url'  => '/blog/',
			'description' => 'Guides helping prospective students choose programs and apply successfully.',
			'clusters'    => array(
				array(
					'slug'          => 'how-to-choose-the-right-nursing-program',
					'title'         => 'How to Choose the Right Nursing Program',
					'focus_keyword' => 'choose nursing program',
					'intent'        => 'informational',
					'links_to'      => array( 'services', 'contact-us' ),
					'outbound_refs' => array( 'bls_nursing', 'ncsbn' ),
				),
				array(
					'slug'          => 'berkeley-college-application-tips-for-working-adults',
					'title'         => 'Berkeley College Application Tips for Working Adults',
					'focus_keyword' => 'berkeley college application',
					'intent'        => 'informational',
					'links_to'      => array( 'services', 'contact-us', 'affiliations' ),
					'outbound_refs' => array( 'berkeley_college', 'nces_adult' ),
				),
				array(
					'slug'          => 'online-vs-campus-nursing-programs',
					'title'         => 'Online vs Campus Nursing Programs: Which Fits Your Career?',
					'focus_keyword' => 'online vs campus nursing program',
					'intent'        => 'informational',
					'links_to'      => array( 'how-to-choose-the-right-nursing-program', 'contact-us' ),
					'outbound_refs' => array( 'bls_nursing', 'nces_adult' ),
				),
				array(
					'slug'          => '5-fafsa-mistakes-to-avoid',
					'title'         => '5 FAFSA Mistakes to Avoid',
					'focus_keyword' => 'FAFSA mistakes',
					'intent'        => 'informational',
					'links_to'      => array( 'contact-us', 'berkeley-college-application-tips-for-working-adults', 'how-students-find-the-right-program-with-hlm' ),
					'outbound_refs' => array( 'studentaid_fafsa', 'nces_adult' ),
				),
			),
		),
		'university-marketing' => array(
			'label'       => 'University Marketing',
			'pillar_slug' => 'services',
			'pillar_url'  => '/services/',
			'description' => 'Resources for institutions on lead quality, enrollment marketing, and partnerships.',
			'clusters'    => array(
				array(
					'slug'          => 'what-universities-should-know-about-lead-quality',
					'title'         => 'What Universities Should Know About Lead Quality',
					'focus_keyword' => 'education lead quality',
					'intent'        => 'commercial',
					'links_to'      => array( 'services', 'contact-us', 'about-us', 'affiliations' ),
					'outbound_refs' => array( 'edgov_enrollment', 'mckinsey_higher_ed' ),
				),
				array(
					'slug'          => 'enrollment-marketing-roi-for-universities',
					'title'         => 'Enrollment Marketing ROI: What Universities Should Measure',
					'focus_keyword' => 'enrollment marketing ROI',
					'intent'        => 'commercial',
					'links_to'      => array( 'services', 'contact-us', 'what-universities-should-know-about-lead-quality' ),
					'outbound_refs' => array( 'mckinsey_higher_ed', 'edgov_enrollment' ),
				),
			),
		),
		'hlm-lead-generation' => array(
			'label'       => 'Higher Learning Marketers',
			'pillar_slug' => 'blog',
			'pillar_url'  => '/blog/',
			'description' => 'How Higher Learning Marketers connects universities with motivated students through premium education lead generation.',
			'clusters'    => array(
				array(
					'slug'          => 'how-hlm-helps-universities-fill-programs',
					'title'         => 'How Higher Learning Marketers Helps Universities Fill Programs With Qualified Students',
					'focus_keyword' => 'education lead generation for universities',
					'intent'        => 'commercial',
					'links_to'      => array( 'services', 'contact-us', 'affiliations', 'what-universities-should-know-about-lead-quality' ),
					'outbound_refs' => array( 'edgov_enrollment', 'mckinsey_higher_ed' ),
				),
				array(
					'slug'          => 'how-students-find-the-right-program-with-hlm',
					'title'         => 'How Students Find the Right College Program With Higher Learning Marketers',
					'focus_keyword' => 'find the right college program',
					'intent'        => 'informational',
					'links_to'      => array( 'contact-us', 'how-to-choose-the-right-nursing-program', 'services' ),
					'outbound_refs' => array( 'nces_adult', 'edgov_enrollment' ),
				),
				array(
					'slug'          => 'premium-education-lead-generation-hlm',
					'title'         => 'Premium Education Lead Generation: What Higher Learning Marketers Delivers',
					'focus_keyword' => 'premium education lead generation',
					'intent'        => 'commercial',
					'links_to'      => array( 'services', 'contact-us', 'about-us' ),
					'outbound_refs' => array( 'mckinsey_higher_ed' ),
				),
				array(
					'slug'          => 'consent-based-education-leads-hlm',
					'title'         => 'Why Higher Learning Marketers Uses Consent-Based, TCPA-Compliant Lead Generation',
					'focus_keyword' => 'consent-based education leads',
					'intent'        => 'commercial',
					'links_to'      => array( 'services', 'privacy-policy', 'contact-us', 'tcpa-compliant-education-leads' ),
					'outbound_refs' => array( 'edgov_enrollment', 'fcc_tcpa' ),
				),
			),
		),
		'us-university-leads' => array(
			'label'       => 'US University Lead Generation',
			'pillar_slug' => 'services',
			'pillar_url'  => '/services/',
			'description' => 'US-specific, high-intent resources on student lead generation, compliance, and enrollment economics for universities.',
			'clusters'    => array(
				array(
					'slug'          => 'student-lead-generation-universities-usa',
					'title'         => 'Student Lead Generation for Universities in the USA',
					'focus_keyword' => 'student lead generation for universities USA',
					'intent'        => 'commercial',
					'links_to'      => array( 'services', 'contact-us', 'how-hlm-helps-universities-fill-programs', 'what-universities-should-know-about-lead-quality' ),
					'outbound_refs' => array( 'edgov_enrollment', 'mckinsey_higher_ed' ),
				),
				array(
					'slug'          => 'nursing-program-leads-for-colleges',
					'title'         => 'Nursing Program Leads for Colleges: What Enrollment Teams Need',
					'focus_keyword' => 'nursing program leads for colleges',
					'intent'        => 'commercial',
					'links_to'      => array( 'services', 'contact-us', 'how-to-choose-the-right-nursing-program', 'how-hlm-helps-universities-fill-programs' ),
					'outbound_refs' => array( 'bls_nursing', 'ncsbn' ),
				),
				array(
					'slug'          => 'tcpa-compliant-education-leads',
					'title'         => 'TCPA Compliant Education Leads: A Guide for Universities',
					'focus_keyword' => 'TCPA compliant education leads',
					'intent'        => 'commercial',
					'links_to'      => array( 'services', 'privacy-policy', 'contact-us', 'consent-based-education-leads-hlm' ),
					'outbound_refs' => array( 'fcc_tcpa', 'edgov_enrollment' ),
				),
				array(
					'slug'          => 'adult-learner-enrollment-marketing',
					'title'         => 'Adult Learner Enrollment Marketing for US Universities',
					'focus_keyword' => 'adult learner enrollment marketing',
					'intent'        => 'commercial',
					'links_to'      => array( 'services', 'contact-us', 'berkeley-college-application-tips-for-working-adults', 'how-students-find-the-right-program-with-hlm' ),
					'outbound_refs' => array( 'nces_adult', 'mckinsey_higher_ed' ),
				),
				array(
					'slug'          => 'cost-per-enrollment-vs-cost-per-lead',
					'title'         => 'Cost Per Enrollment vs Cost Per Lead: What Universities Should Track',
					'focus_keyword' => 'cost per enrollment vs cost per lead',
					'intent'        => 'commercial',
					'links_to'      => array( 'services', 'contact-us', 'enrollment-marketing-roi-for-universities', 'what-universities-should-know-about-lead-quality' ),
					'outbound_refs' => array( 'mckinsey_higher_ed', 'edgov_enrollment' ),
				),
				array(
					'slug'          => 'how-to-buy-education-leads',
					'title'         => 'How to Buy Education Leads: A University Buyer\'s Guide',
					'focus_keyword' => 'how to buy education leads',
					'intent'        => 'commercial',
					'links_to'      => array( 'services', 'contact-us', 'what-universities-should-know-about-lead-quality', 'tcpa-compliant-education-leads' ),
					'outbound_refs' => array( 'fcc_tcpa', 'mckinsey_higher_ed' ),
				),
			),
		),
	) );
}

/**
 * HLM internal link map.
 *
 * @param array<string, array<int, array<string, string>>> $defaults Existing map.
 * @return array<string, array<int, array<string, string>>>
 */
function hlm_kadence_seo_internal_link_map( array $defaults ): array {
	return array_merge( $defaults, array(
		'home' => array(
			array( 'slug' => 'services', 'anchor' => 'education lead generation services', 'placement' => 'above_fold' ),
			array( 'slug' => 'about-us', 'anchor' => 'about our team', 'placement' => 'body' ),
			array( 'slug' => 'blog', 'anchor' => 'higher education insights', 'placement' => 'body' ),
			array( 'slug' => 'student-lead-generation-universities-usa', 'anchor' => 'student lead generation for U.S. universities', 'placement' => 'body', 'type' => 'post' ),
			array( 'slug' => 'contact-us', 'anchor' => 'get started today', 'placement' => 'cta' ),
		),
		'services' => array(
			array( 'slug' => 'contact-us', 'anchor' => 'request a partnership consultation', 'placement' => 'above_fold' ),
			array( 'slug' => 'what-universities-should-know-about-lead-quality', 'anchor' => 'understanding lead quality', 'placement' => 'body', 'type' => 'post' ),
			array( 'slug' => 'how-hlm-helps-universities-fill-programs', 'anchor' => 'how we fill university programs', 'placement' => 'body', 'type' => 'post' ),
			array( 'slug' => 'student-lead-generation-universities-usa', 'anchor' => 'student lead generation for universities', 'placement' => 'body', 'type' => 'post' ),
			array( 'slug' => 'how-to-buy-education-leads', 'anchor' => 'how to buy education leads', 'placement' => 'body', 'type' => 'post' ),
			array( 'slug' => 'tcpa-compliant-education-leads', 'anchor' => 'TCPA compliant education leads', 'placement' => 'body', 'type' => 'post' ),
			array( 'slug' => 'premium-education-lead-generation-hlm', 'anchor' => 'premium education lead generation', 'placement' => 'body', 'type' => 'post' ),
			array( 'slug' => 'affiliations', 'anchor' => 'our university partnerships', 'placement' => 'body' ),
			array( 'slug' => 'about-us', 'anchor' => 'our track record', 'placement' => 'body' ),
		),
		'contact-us' => array(
			array( 'slug' => 'services', 'anchor' => 'explore our services', 'placement' => 'body' ),
			array( 'slug' => 'privacy-policy', 'anchor' => 'privacy policy', 'placement' => 'form_footer' ),
		),
		'about-us' => array(
			array( 'slug' => 'services', 'anchor' => 'our lead generation services', 'placement' => 'body' ),
			array( 'slug' => 'affiliations', 'anchor' => 'strategic affiliations', 'placement' => 'body' ),
			array( 'slug' => 'contact-us', 'anchor' => 'work with us', 'placement' => 'cta' ),
		),
		'affiliations' => array(
			array( 'slug' => 'contact-us', 'anchor' => 'become an education partner', 'placement' => 'above_fold' ),
			array( 'slug' => 'services', 'anchor' => 'education lead generation services', 'placement' => 'body' ),
			array( 'slug' => 'about-us', 'anchor' => 'about Higher Learning Marketers', 'placement' => 'body' ),
			array( 'slug' => 'how-hlm-helps-universities-fill-programs', 'anchor' => 'how we support university partners', 'placement' => 'body', 'type' => 'post' ),
			array( 'slug' => 'premium-education-lead-generation-hlm', 'anchor' => 'premium education lead generation', 'placement' => 'body', 'type' => 'post' ),
			array( 'slug' => 'contact-us', 'anchor' => 'request a partnership consultation', 'placement' => 'cta' ),
		),
		'blog' => array(
			array( 'slug' => 'services', 'anchor' => 'university lead generation services', 'placement' => 'intro' ),
			array( 'slug' => 'how-hlm-helps-universities-fill-programs', 'anchor' => 'how HLM helps universities', 'placement' => 'body', 'type' => 'post' ),
			array( 'slug' => 'student-lead-generation-universities-usa', 'anchor' => 'student lead generation USA', 'placement' => 'body', 'type' => 'post' ),
			array( 'slug' => 'how-students-find-the-right-program-with-hlm', 'anchor' => 'finding the right program', 'placement' => 'body', 'type' => 'post' ),
			array( 'slug' => '5-fafsa-mistakes-to-avoid', 'anchor' => 'FAFSA mistakes to avoid', 'placement' => 'body', 'type' => 'post' ),
			array( 'slug' => 'contact-us', 'anchor' => 'connect with an advisor', 'placement' => 'cta' ),
		),
		'lead-generation' => array(
			array( 'slug' => 'contact-us', 'anchor' => 'request a lead generation consultation', 'placement' => 'above_fold' ),
			array( 'slug' => 'services', 'anchor' => 'all education lead generation services', 'placement' => 'body' ),
			array( 'slug' => 'student-lead-generation-universities-usa', 'anchor' => 'student lead generation for U.S. universities', 'placement' => 'body', 'type' => 'post' ),
			array( 'slug' => 'what-universities-should-know-about-lead-quality', 'anchor' => 'understanding education lead quality', 'placement' => 'body', 'type' => 'post' ),
			array( 'slug' => 'services/enrollment-funnel', 'anchor' => 'enrollment funnel optimization', 'placement' => 'body' ),
			array( 'slug' => 'contact-us', 'anchor' => 'get started with HLM', 'placement' => 'cta' ),
		),
		'brand-awareness' => array(
			array( 'slug' => 'contact-us', 'anchor' => 'discuss brand awareness campaigns', 'placement' => 'above_fold' ),
			array( 'slug' => 'services', 'anchor' => 'education lead generation services', 'placement' => 'body' ),
			array( 'slug' => 'premium-education-lead-generation-hlm', 'anchor' => 'premium education lead generation', 'placement' => 'body', 'type' => 'post' ),
			array( 'slug' => 'about-us', 'anchor' => 'about Higher Learning Marketers', 'placement' => 'body' ),
			array( 'slug' => 'services/multi-channel-marketing', 'anchor' => 'multi-channel enrollment marketing', 'placement' => 'body' ),
			array( 'slug' => 'contact-us', 'anchor' => 'request a campaign consultation', 'placement' => 'cta' ),
		),
		'multi-channel-marketing' => array(
			array( 'slug' => 'contact-us', 'anchor' => 'plan a multi-channel campaign', 'placement' => 'above_fold' ),
			array( 'slug' => 'services', 'anchor' => 'all enrollment marketing services', 'placement' => 'body' ),
			array( 'slug' => 'how-hlm-helps-universities-fill-programs', 'anchor' => 'how HLM supports university enrollment', 'placement' => 'body', 'type' => 'post' ),
			array( 'slug' => 'services/partner-network', 'anchor' => 'education partner network', 'placement' => 'body' ),
			array( 'slug' => 'services/lead-generation', 'anchor' => 'student lead generation', 'placement' => 'body' ),
			array( 'slug' => 'contact-us', 'anchor' => 'talk to our team', 'placement' => 'cta' ),
		),
		'analytics-reporting' => array(
			array( 'slug' => 'contact-us', 'anchor' => 'request enrollment reporting demo', 'placement' => 'above_fold' ),
			array( 'slug' => 'services', 'anchor' => 'education lead generation services', 'placement' => 'body' ),
			array( 'slug' => 'enrollment-marketing-roi-for-universities', 'anchor' => 'enrollment marketing ROI', 'placement' => 'body', 'type' => 'post' ),
			array( 'slug' => 'what-universities-should-know-about-lead-quality', 'anchor' => 'measuring lead quality', 'placement' => 'body', 'type' => 'post' ),
			array( 'slug' => 'services/lead-generation', 'anchor' => 'student lead generation', 'placement' => 'body' ),
			array( 'slug' => 'contact-us', 'anchor' => 'schedule a consultation', 'placement' => 'cta' ),
		),
		'partner-network' => array(
			array( 'slug' => 'contact-us', 'anchor' => 'join our partner network', 'placement' => 'above_fold' ),
			array( 'slug' => 'services', 'anchor' => 'education lead generation services', 'placement' => 'body' ),
			array( 'slug' => 'affiliations', 'anchor' => 'strategic education partnerships', 'placement' => 'body' ),
			array( 'slug' => 'how-hlm-helps-universities-fill-programs', 'anchor' => 'how we help universities fill programs', 'placement' => 'body', 'type' => 'post' ),
			array( 'slug' => 'services/lead-generation', 'anchor' => 'student lead generation', 'placement' => 'body' ),
			array( 'slug' => 'contact-us', 'anchor' => 'become a network partner', 'placement' => 'cta' ),
		),
		'enrollment-funnel' => array(
			array( 'slug' => 'contact-us', 'anchor' => 'request a funnel audit', 'placement' => 'above_fold' ),
			array( 'slug' => 'services', 'anchor' => 'all enrollment marketing services', 'placement' => 'body' ),
			array( 'slug' => 'how-hlm-helps-universities-fill-programs', 'anchor' => 'how HLM improves enrollment outcomes', 'placement' => 'body', 'type' => 'post' ),
			array( 'slug' => 'services/lead-generation', 'anchor' => 'student lead generation', 'placement' => 'body' ),
			array( 'slug' => 'services/analytics-reporting', 'anchor' => 'enrollment analytics and reporting', 'placement' => 'body' ),
			array( 'slug' => 'contact-us', 'anchor' => 'start optimizing your funnel', 'placement' => 'cta' ),
		),
	) );
}

/**
 * HLM outbound authority links.
 *
 * @param array<string, array<string, string>> $defaults Existing refs.
 * @return array<string, array<string, string>>
 */
function hlm_kadence_seo_outbound_authority( array $defaults ): array {
	return array_merge( $defaults, array(
		'bls_nursing' => array(
			'url'   => 'https://www.bls.gov/ooh/healthcare/registered-nurses.htm',
			'title' => 'U.S. Bureau of Labor Statistics - Registered Nurses',
			'rel'   => 'noopener noreferrer',
		),
		'ncsbn' => array(
			'url'   => 'https://www.ncsbn.org/',
			'title' => 'National Council of State Boards of Nursing',
			'rel'   => 'noopener noreferrer',
		),
		'berkeley_college' => array(
			'url'   => 'https://berkeleycollege.edu/',
			'title' => 'Berkeley College',
			'rel'   => 'noopener noreferrer',
		),
		'nces_adult' => array(
			'url'   => 'https://nces.ed.gov/fastfacts/display.asp?id=89',
			'title' => 'NCES - Adult Learners in Higher Education',
			'rel'   => 'noopener noreferrer',
		),
		'edgov_enrollment' => array(
			'url'   => 'https://www.ed.gov/news/press-releases',
			'title' => 'U.S. Department of Education',
			'rel'   => 'noopener noreferrer',
		),
		'mckinsey_higher_ed' => array(
			'url'   => 'https://www.mckinsey.com/industries/education/our-insights',
			'title' => 'McKinsey - Higher Education Insights',
			'rel'   => 'noopener noreferrer',
		),
		'fcc_tcpa' => array(
			'url'   => 'https://www.fcc.gov/general/telemarketing-and-robocalls',
			'title' => 'FCC - Telemarketing and TCPA Rules',
			'rel'   => 'noopener noreferrer',
		),
		'studentaid_fafsa' => array(
			'url'   => 'https://studentaid.gov/h/apply-for-aid/fafsa',
			'title' => 'Federal Student Aid - FAFSA',
			'rel'   => 'noopener noreferrer',
		),
		'google_seo_guide' => array(
			'url'   => 'https://developers.google.com/search/docs/fundamentals/seo-starter-guide',
			'title' => 'Google Search Central - SEO Starter Guide',
			'rel'   => 'noopener noreferrer',
		),
		'semrush_site_structure' => array(
			'url'   => 'https://www.semrush.com/blog/website-structure/',
			'title' => 'Semrush - Website Structure for SEO',
			'rel'   => 'noopener noreferrer',
		),
	) );
}

/**
 * HLM cluster post bootstrap content.
 *
 * @param array<string, array<string, string>> $defaults Existing content.
 * @return array<string, array<string, string>>
 */
function hlm_kadence_seo_cluster_post_content( array $defaults ): array {
	if ( function_exists( 'hlm_kadence_seo_blog_posts_content' ) ) {
		return array_merge( $defaults, hlm_kadence_seo_blog_posts_content() );
	}

	return $defaults;
}

/**
 * HLM page FAQ registry for lead-gen and services pages.
 *
 * @param array<string, array<int, array<string, string>>> $defaults Existing FAQs.
 * @return array<string, array<int, array<string, string>>>
 */
function hlm_kadence_seo_page_faqs( array $defaults ): array {
	return array_merge(
		$defaults,
		array(
			'home' => array(
				array(
					'question' => 'What does Higher Learning Marketers do?',
					'answer'   => 'We provide education lead generation for universities and colleges across the United States, connecting enrollment teams with consent-based student inquiries while helping prospective students find accredited programs.',
				),
				array(
					'question' => 'Do you work with universities in the United States only?',
					'answer'   => 'Our primary focus is the U.S. higher education market - nursing, business, and career-focused programs for traditional and adult learners. Our organization schema and campaigns are built for U.S. institutions.',
				),
				array(
					'question' => 'How can students find programs through HLM?',
					'answer'   => 'Students submit an inquiry through our contact form or blog resources. An advisor reviews program interest and connects you with accredited partner institutions - there is no obligation to enroll.',
				),
				array(
					'question' => 'Are your leads TCPA compliant?',
					'answer'   => 'Yes. Every inquiry includes documented opt-in consent before any institution contacts a student. See our Privacy Policy and consent-based lead generation resources for full details.',
				),
			),
			'services' => array(
				array(
					'question' => 'What types of institutions do you work with?',
					'answer'   => 'We partner with universities, colleges, and accredited program providers across the United States seeking consent-based, high-intent student inquiries for nursing, business, and career-focused pathways.',
				),
				array(
					'question' => 'How do you define a quality education lead?',
					'answer'   => 'Quality leads include verified contact details, explicit opt-in consent, program-interest alignment, geographic fit, and readiness indicators such as preferred start date and education history.',
				),
				array(
					'question' => 'Can you support both undergraduate and adult learner campaigns?',
					'answer'   => 'Yes. Our funnels are segmented by audience intent, allowing separate messaging and qualification flows for traditional students and working adults across the U.S.',
				),
				array(
					'question' => 'How is HLM different from buying shared lead lists?',
					'answer'   => 'We deliver first-party, consent-based inquiries matched to your programs - not recycled lists sold to multiple competitors. Institutions typically see higher contact rates and better cost-per-start outcomes.',
				),
				array(
					'question' => 'What programs do you generate leads for?',
					'answer'   => 'Nursing pathways (RN, BSN, MSN), business degrees, and career-focused programs for adult learners are our core specialties. We customize campaigns by program, geography, and enrollment goals.',
				),
				array(
					'question' => 'How do you report campaign performance?',
					'answer'   => 'Partners receive real-time lead delivery plus reporting on inquiry volume, source attribution, and funnel metrics. We align conversations around cost per lead and cost per enrollment - not vanity volume.',
				),
			),
			'contact-us' => array(
				array(
					'question' => 'How quickly will someone respond to my inquiry?',
					'answer'   => 'Our team responds to partnership and student inquiries within one business day during standard U.S. operating hours.',
				),
				array(
					'question' => 'Is my information kept private?',
					'answer'   => 'Yes. We follow TCPA and GDPR-aligned practices. See our Privacy Policy for full details on data collection, sharing, and your rights.',
				),
				array(
					'question' => 'I represent a university. How do we get started?',
					'answer'   => 'Submit the contact form with your institution name, target programs, and enrollment goals. We will schedule a consultation to review geography, volume, and partnership fit.',
				),
				array(
					'question' => 'Can I request education leads for a specific program?',
					'answer'   => 'Yes. University partners specify program mix - nursing, business, adult learner, or other pathways - and we tailor campaigns and qualification forms to match.',
				),
				array(
					'question' => 'I am a student. Will multiple schools contact me?',
					'answer'   => 'You control the conversation. We match you with institutions aligned to your stated interests. You choose which schools to engage - there is no obligation.',
				),
			),
			'about-us' => array(
				array(
					'question' => 'How long has Higher Learning Marketers been in business?',
					'answer'   => 'Higher Learning Marketers was founded in 2014 and has spent over a decade connecting U.S. students with accredited institutions through consent-based enrollment marketing.',
				),
				array(
					'question' => 'Where is Higher Learning Marketers located?',
					'answer'   => 'We are headquartered in Briarwood, New York, and serve universities and colleges across the United States.',
				),
				array(
					'question' => 'Is HLM TCPA compliant?',
					'answer'   => 'Yes. Consent documentation, transparent form disclosures, and TCPA-aligned outreach practices are core to how we generate and deliver education leads.',
				),
				array(
					'question' => 'What makes HLM an education marketing company universities trust?',
					'answer'   => 'We prioritize lead quality over raw volume, document consent for every inquiry, and align campaigns with enrollment KPIs - helping partners fill programs with students who chose to be contacted.',
				),
			),
			'affiliations' => array(
				array(
					'question' => 'What types of education partnerships does HLM offer?',
					'answer'   => 'We partner with universities, colleges, community organizations, and education platforms seeking to reach prospective students in the United States through consent-based lead generation and enrollment marketing support.',
				),
				array(
					'question' => 'How do strategic affiliations work with Higher Learning Marketers?',
					'answer'   => 'Affiliation partners collaborate with HLM on student outreach, campaign distribution, and program promotion. We align goals, compliance requirements, and reporting so partnerships support measurable enrollment outcomes.',
				),
				array(
					'question' => 'Do you partner with institutions only in the United States?',
					'answer'   => 'Our primary enrollment marketing focus is U.S. universities and colleges. Our network reaches students across many regions, but campaigns and partnerships are built around U.S. higher education programs and compliance standards.',
				),
				array(
					'question' => 'How can my organization become an affiliate partner?',
					'answer'   => 'Submit the contact form on our website with your organization name, audience, and partnership goals. Our team will schedule a consultation to review fit, program alignment, and next steps.',
				),
				array(
					'question' => 'What is the YGO affiliation?',
					'answer'   => 'Higher Learning Marketers partners with YGO to connect young people and community audiences with accredited education pathways. The affiliation combines community reach with HLM lead generation infrastructure and consent-based inquiry capture.',
				),
			),
			'lead-generation' => array(
				array(
					'question' => 'What is student lead generation for universities?',
					'answer'   => 'Student lead generation connects U.S. universities and colleges with prospective students who have explicitly requested program information. Higher Learning Marketers delivers verified, consent-based inquiries matched to your programs - not shared contact lists.',
				),
				array(
					'question' => 'Are HLM education leads TCPA compliant?',
					'answer'   => 'Yes. Every inquiry includes documented opt-in consent before your admissions team initiates contact. Our forms clearly disclose who will reach out and how information will be used.',
				),
				array(
					'question' => 'How quickly are leads delivered to our CRM?',
					'answer'   => 'Qualified inquiries are delivered in real time to your CRM or admissions inbox so counselors can respond while student intent is highest.',
				),
				array(
					'question' => 'What programs do you generate leads for?',
					'answer'   => 'We specialize in nursing pathways, business degrees, and career-focused programs for adult learners across the United States. Campaigns are customized by program, geography, and enrollment goals.',
				),
			),
			'brand-awareness' => array(
				array(
					'question' => 'How does education brand awareness support enrollment?',
					'answer'   => 'Brand awareness keeps your institution visible while students research options. Strong visibility improves inquiry quality and conversion when students are ready to request information from programs they already recognize.',
				),
				array(
					'question' => 'Which channels do you use for U.S. institutions?',
					'answer'   => 'We combine display, social, retargeting, and program-specific landing pages with geo and demographic segmentation tailored to U.S. higher education audiences.',
				),
				array(
					'question' => 'Can brand campaigns integrate with lead generation?',
					'answer'   => 'Yes. Awareness and lead generation work best together - visibility builds familiarity, and consent-based inquiry capture converts interest into actionable enrollment opportunities.',
				),
				array(
					'question' => 'How do you measure brand awareness results?',
					'answer'   => 'Reporting includes reach, frequency, engagement, and downstream inquiry volume so enrollment leaders can connect visibility spend to measurable funnel outcomes.',
				),
			),
			'multi-channel-marketing' => array(
				array(
					'question' => 'What channels are included in multi-channel enrollment marketing?',
					'answer'   => 'Campaigns coordinate paid search, social, email nurture, and partner network distribution under one strategy - so each touchpoint reinforces the next for U.S. university prospects.',
				),
				array(
					'question' => 'How does multi-channel marketing improve cost per enrollment?',
					'answer'   => 'Unified attribution shows which channels drive qualified inquiries and starts. That visibility helps enrollment leaders shift budget toward sources with stronger downstream conversion.',
				),
				array(
					'question' => 'Can you coordinate with our existing vendors?',
					'answer'   => 'Yes. We can lead the full plan or integrate with in-house teams and existing agencies while maintaining consistent messaging, compliance, and reporting standards.',
				),
				array(
					'question' => 'Do you support U.S. university campaigns only?',
					'answer'   => 'Our enrollment marketing focus is U.S. universities and colleges. Campaigns are built around U.S. program requirements, compliance standards, and student decision journeys.',
				),
			),
			'analytics-reporting' => array(
				array(
					'question' => 'What enrollment metrics do you track?',
					'answer'   => 'Dashboards cover inquiry volume, source attribution, cost per inquiry, and downstream enrollment outcomes so marketing and admissions leaders share one view of performance.',
				),
				array(
					'question' => 'Do you report cost per enrollment?',
					'answer'   => 'Yes. We align reporting conversations around cost per lead and cost per enrollment - not vanity volume - so budget decisions reflect seated students.',
				),
				array(
					'question' => 'How often do optimization reviews happen?',
					'answer'   => 'Partners receive weekly optimization reviews plus executive-ready summary reports for leadership and board-level enrollment conversations.',
				),
				array(
					'question' => 'Can reporting integrate with our CRM?',
					'answer'   => 'Lead delivery and performance data are designed to flow into your CRM or admissions workflow so counselors and analysts work from the same real-time information.',
				),
			),
			'partner-network' => array(
				array(
					'question' => 'How large is the HLM education partner network?',
					'answer'   => 'Higher Learning Marketers distributes campaigns across 560+ education-focused websites and platforms that reach students comparing schools and exploring career pathways.',
				),
				array(
					'question' => 'Are partner placements compliance-reviewed?',
					'answer'   => 'Yes. Every partner placement is reviewed for consent language, disclosure standards, and TCPA-aligned inquiry capture before campaigns go live.',
				),
				array(
					'question' => 'Can we target specific programs and geographies?',
					'answer'   => 'Campaigns support program-level targeting, exclusivity options, and geographic segmentation so inquiries align with enrollment priorities at your institution.',
				),
				array(
					'question' => 'How does the partner network relate to lead generation?',
					'answer'   => 'The partner network extends reach beyond owned channels. Inquiries captured across network properties are verified, consented, and delivered through the same lead generation infrastructure as direct campaigns.',
				),
			),
			'enrollment-funnel' => array(
				array(
					'question' => 'What is enrollment funnel optimization?',
					'answer'   => 'Funnel optimization improves the path from first click to submitted application by auditing landing pages, forms, messaging, and follow-up sequences that affect conversion.',
				),
				array(
					'question' => 'Do you optimize landing pages and inquiry forms?',
					'answer'   => 'Yes. We run funnel audits, A/B tests on headlines and CTAs, mobile-first landing page improvements, and conversion rate optimization sprints with before/after benchmarks.',
				),
				array(
					'question' => 'Can CRO improve results without increasing ad spend?',
					'answer'   => 'Often yes. Higher conversion on existing traffic lowers cost per inquiry and cost per enrollment - especially valuable before peak enrollment seasons.',
				),
				array(
					'question' => 'Who benefits most from funnel optimization?',
					'answer'   => 'Teams generating traffic but struggling to convert inquiries into applications, or institutions preparing for enrollment peaks that need more starts from the same media budget.',
				),
			),
		)
	);
}

/**
 * Keyword-rich intro copy placed after page heroes (Phase 2 on-page SEO).
 *
 * @return array<string, string>
 */
function hlm_kadence_seo_page_intros(): array {
	return array(
		'home'       => '<p>Higher Learning Marketers connects <strong>universities and colleges across the United States</strong> with motivated prospective students through premium <strong>education lead generation</strong>. Enrollment teams partner with us for TCPA-compliant, consent-based inquiries in nursing, business, and adult-learner pathways - while students use our platform to explore accredited programs matched to their career goals.</p>',
		'services'   => '<p>Our <strong>education lead generation services</strong> help U.S. enrollment teams fill priority programs with students who have explicitly requested information. Higher Learning Marketers delivers verified, consent-based inquiries - not shared lists - with program-level targeting, real-time CRM delivery, and reporting aligned to <strong>cost per enrollment</strong> rather than raw lead volume.</p>',
		'contact-us' => '<p>Use this page to <strong>partner with an education lead generation company</strong> serving universities and colleges in the United States, or to connect with accredited programs as a prospective student. University enrollment leaders can request a consultation to discuss nursing, business, and adult-learner campaigns. Students and working adults can submit an inquiry to compare pathways with an advisor.</p>',
		'about-us'   => '<p>Higher Learning Marketers is an <strong>education marketing company</strong> based in New York, specializing in consent-based student recruitment for U.S. higher education. Since 2014 we have helped universities improve enrollment outcomes through TCPA-compliant lead generation, program-matched inquiries, and transparent data practices that protect both institutions and students.</p>',
		'affiliations' => '<p>Our <strong>education partnerships</strong> connect accredited universities, colleges, and community organizations with motivated students across the United States. Higher Learning Marketers combines strategic <strong>affiliations</strong> - including community-focused partners like YGO - with TCPA-compliant lead generation so institutions expand reach through trusted, consent-based enrollment marketing rather than generic list buying.</p>',
		'lead-generation' => '<p>Our <strong>student lead generation</strong> service delivers TCPA-compliant, consent-based inquiries to U.S. university and college admissions teams. Higher Learning Marketers connects enrollment leaders with students who have explicitly requested information about nursing, business, and adult-learner programs - with real-time CRM delivery and quality filters that protect <strong>cost per enrollment</strong> outcomes.</p>',
		'brand-awareness' => '<p><strong>Education brand awareness</strong> campaigns keep your U.S. institution visible across display, social, and retargeting channels while students research degree options. Higher Learning Marketers combines creative, media, and landing experiences so your programs stay top of mind before prospects are ready to request information.</p>',
		'multi-channel-marketing' => '<p><strong>Multi-channel enrollment marketing</strong> coordinates search, social, email, and partner placements for U.S. universities under one strategy. Higher Learning Marketers aligns messaging, compliance, and attribution so each touchpoint moves prospective students closer to application - not isolated campaigns with disconnected reporting.</p>',
		'analytics-reporting' => '<p><strong>Enrollment marketing analytics</strong> from Higher Learning Marketers tie campaign spend to inquiry volume, source attribution, and downstream starts. U.S. enrollment leaders use our dashboards and weekly reviews to track <strong>cost per lead</strong> and <strong>cost per enrollment</strong> with transparent ROI reporting.</p>',
		'partner-network' => '<p>Our <strong>education partner network</strong> places your programs on 560+ education-focused websites where U.S. students compare schools and request information. Higher Learning Marketers combines network reach with compliance-reviewed placements and consent-based <strong>lead generation</strong> infrastructure.</p>',
		'enrollment-funnel' => '<p><strong>Enrollment funnel optimization</strong> improves conversion from first click to submitted application for U.S. universities. Higher Learning Marketers audits landing pages, forms, and follow-up sequences - then runs A/B tests and CRO sprints that raise inquiry-to-application rates without always increasing media spend.</p>',
	);
}

/**
 * Insert keyword-rich intro sections after the first hero on money pages.
 *
 * @param string $content Page content.
 * @return string
 */
function hlm_kadence_place_seo_page_intros( string $content ): string {
	if ( ! is_singular( 'page' ) || ! in_the_loop() || ! is_main_query() || ! hlm_kadence_is_theme_shell() ) {
		return $content;
	}

	$post = get_queried_object();
	if ( ! $post instanceof WP_Post ) {
		return $content;
	}

	$intros = hlm_kadence_seo_page_intros();
	if ( empty( $intros[ $post->post_name ] ) ) {
		return $content;
	}

	$block = sprintf(
		'<section class="hlm-seo-intro hlm-seo-intro--compact hlm-section hlm-bg-cream" aria-label="%s"><div class="hlm-container"><div class="hlm-seo-intro__card"><div class="hlm-seo-intro__body hlm-body-lg">%s</div></div></div></section>',
		esc_attr__( 'Overview', 'hlm-seo' ),
		wp_kses_post( function_exists( 'hlm_seo_normalize_dashes' ) ? hlm_seo_normalize_dashes( $intros[ $post->post_name ] ) : $intros[ $post->post_name ] )
	);

	if ( 'services' === $post->post_name && str_contains( $content, 'hlm-services' ) ) {
		$updated = hlm_kadence_insert_before_first_block( $content, $block, 'hlm-services' );
	} elseif ( 'contact-us' === $post->post_name && str_contains( $content, 'hlm-contact' ) ) {
		$updated = hlm_kadence_insert_before_first_block( $content, $block, 'hlm-contact' );
	} else {
		$updated = hlm_kadence_insert_after_first_hero( $content, $block );
	}

	return $updated ?? ( $block . $content );
}
add_filter( 'the_content', 'hlm_kadence_place_seo_page_intros', 14 );

/**
 * Refresh money pages with Phase 2 on-page SEO copy from block patterns.
 */
function hlm_kadence_seo_phase2_refresh_pages(): void {
	if ( get_option( 'hlm_seo_phase2_pages' ) || ! function_exists( 'hlm_get_pattern_markup' ) ) {
		return;
	}

	$pages = array(
		array( 'slug' => 'home', 'pattern' => 'home-page.php', 'is_front' => true ),
		array( 'slug' => 'services', 'pattern' => 'services-page.php' ),
		array( 'slug' => 'contact-us', 'pattern' => 'contact-page.php' ),
		array( 'slug' => 'about-us', 'pattern' => 'about-us-page.php' ),
	);

	foreach ( $pages as $page ) {
		$content = hlm_get_pattern_markup( $page['pattern'] );
		if ( '' === $content ) {
			continue;
		}

		$page_id = 0;
		if ( ! empty( $page['is_front'] ) ) {
			$page_id = (int) get_option( 'page_on_front' );
		}
		if ( ! $page_id ) {
			$existing = get_page_by_path( $page['slug'] );
			if ( $existing instanceof WP_Post ) {
				$page_id = (int) $existing->ID;
			}
		}

		if ( $page_id > 0 ) {
			wp_update_post(
				array(
					'ID'           => $page_id,
					'post_content' => $content,
				)
			);
		}
	}

	update_option( 'hlm_seo_phase2_pages', 1, false );
}
add_action( 'init', 'hlm_kadence_seo_phase2_refresh_pages', 35 );

/**
 * Trigger SEO bootstrap after HLM pages are created.
 */
function hlm_kadence_seo_bootstrap_after_pages(): void {
	if ( ! class_exists( 'HLM_SEO\Content_Bootstrap' ) ) {
		return;
	}

	$pages = array(
		array( 'slug' => 'home' ),
		array( 'slug' => 'about-us' ),
		array( 'slug' => 'services' ),
		array( 'slug' => 'affiliations' ),
		array( 'slug' => 'contact-us' ),
		array( 'slug' => 'blog' ),
		array( 'slug' => 'privacy-policy' ),
	);

	do_action( 'hlm_seo_bootstrap_pages', $pages );
	do_action( 'hlm_seo_bootstrap_posts' );
}
add_action( 'hlm_kadence_pages_ready', 'hlm_kadence_seo_bootstrap_after_pages' );

/**
 * One-time SEO bootstrap for existing installs.
 */
function hlm_kadence_seo_bootstrap_existing(): void {
	if ( get_option( 'hlm_seo_bootstrapped_v2' ) || ! class_exists( 'HLM_SEO\Content_Bootstrap' ) ) {
		return;
	}

	hlm_kadence_seo_bootstrap_after_pages();
	update_option( 'hlm_seo_bootstrapped_v2', 1, false );
	delete_option( 'hlm_seo_bootstrapped_v1' );
}
add_action( 'init', 'hlm_kadence_seo_bootstrap_existing', 30 );

/**
 * Bootstrap four HLM business blog posts and remove old generic SEO posts.
 */
function hlm_kadence_seo_bootstrap_posts_v4(): void {
	if ( get_option( 'hlm_seo_posts_v4' ) || ! class_exists( 'HLM_SEO\Content_Bootstrap' ) ) {
		return;
	}

	$retire = array(
		'education-lead-generation-seo-guide',
		'topic-clusters-for-higher-education-seo',
		'landing-page-seo-for-education-leads',
		'internal-linking-strategies-for-university-seo',
	);

	foreach ( $retire as $slug ) {
		$post = get_page_by_path( $slug, OBJECT, 'post' );
		if ( $post instanceof WP_Post ) {
			wp_trash_post( $post->ID );
		}
	}

	do_action( 'hlm_seo_bootstrap_posts' );
	update_option( 'hlm_seo_posts_v4', 1, false );
}
add_action( 'init', 'hlm_kadence_seo_bootstrap_posts_v4', 32 );

/**
 * Refresh HLM blog post content and SEO meta.
 */
function hlm_kadence_seo_bootstrap_posts_v5(): void {
	if ( get_option( 'hlm_seo_posts_v5' ) || ! class_exists( 'HLM_SEO\Content_Bootstrap' ) ) {
		return;
	}

	do_action( 'hlm_seo_bootstrap_posts' );
	update_option( 'hlm_seo_posts_v5', 1, false );
}
add_action( 'init', 'hlm_kadence_seo_bootstrap_posts_v5', 33 );

/**
 * Expand blog posts to full length, add Tier 3 cluster posts, and set featured images.
 */
function hlm_kadence_seo_bootstrap_posts_v6(): void {
	if ( get_option( 'hlm_seo_posts_v6' ) || ! class_exists( 'HLM_SEO\Content_Bootstrap' ) ) {
		return;
	}

	do_action( 'hlm_seo_bootstrap_posts' );
	update_option( 'hlm_seo_posts_v6', 1, false );
}
add_action( 'init', 'hlm_kadence_seo_bootstrap_posts_v6', 34 );

/**
 * Add FAFSA student guide blog post.
 */
function hlm_kadence_seo_bootstrap_posts_v7(): void {
	if ( get_option( 'hlm_seo_posts_v7' ) || ! class_exists( 'HLM_SEO\Content_Bootstrap' ) ) {
		return;
	}

	do_action( 'hlm_seo_bootstrap_posts' );
	update_option( 'hlm_seo_posts_v7', 1, false );
}
add_action( 'init', 'hlm_kadence_seo_bootstrap_posts_v7', 36 );

/**
 * Re-bootstrap FAFSA post with native Gutenberg block markup.
 */
function hlm_kadence_seo_bootstrap_posts_v8(): void {
	if ( get_option( 'hlm_seo_posts_v8' ) || ! class_exists( 'HLM_SEO\Content_Bootstrap' ) ) {
		return;
	}

	do_action( 'hlm_seo_bootstrap_posts' );
	update_option( 'hlm_seo_posts_v8', 1, false );
}
add_action( 'init', 'hlm_kadence_seo_bootstrap_posts_v8', 37 );

/**
 * Re-bootstrap nursing program post with native Gutenberg block markup.
 */
function hlm_kadence_seo_bootstrap_posts_v9(): void {
	if ( get_option( 'hlm_seo_posts_v9' ) || ! class_exists( 'HLM_SEO\Content_Bootstrap' ) ) {
		return;
	}

	do_action( 'hlm_seo_bootstrap_posts' );
	update_option( 'hlm_seo_posts_v9', 1, false );
}
add_action( 'init', 'hlm_kadence_seo_bootstrap_posts_v9', 38 );

/**
 * Re-bootstrap FAFSA post with flat block structure and semantic mistake fields.
 */
function hlm_kadence_seo_bootstrap_posts_v10(): void {
	if ( get_option( 'hlm_seo_posts_v10' ) || ! class_exists( 'HLM_SEO\Content_Bootstrap' ) ) {
		return;
	}

	do_action( 'hlm_seo_bootstrap_posts' );
	update_option( 'hlm_seo_posts_v10', 1, false );
}
add_action( 'init', 'hlm_kadence_seo_bootstrap_posts_v10', 39 );

/**
 * Re-bootstrap posts after fixing heading tag markup (<h2> not <2>).
 */
function hlm_kadence_seo_bootstrap_posts_v11(): void {
	if ( get_option( 'hlm_seo_posts_v11' ) || ! class_exists( 'HLM_SEO\Content_Bootstrap' ) ) {
		return;
	}

	do_action( 'hlm_seo_bootstrap_posts' );
	update_option( 'hlm_seo_posts_v11', 1, false );
}
add_action( 'init', 'hlm_kadence_seo_bootstrap_posts_v11', 40 );

/**
 * Re-bootstrap posts after callout attribute serialization fix.
 */
function hlm_kadence_seo_bootstrap_posts_v12(): void {
	if ( get_option( 'hlm_seo_posts_v12' ) || ! class_exists( 'HLM_SEO\Content_Bootstrap' ) ) {
		return;
	}

	do_action( 'hlm_seo_bootstrap_posts' );
	update_option( 'hlm_seo_posts_v12', 1, false );
}
add_action( 'init', 'hlm_kadence_seo_bootstrap_posts_v12', 41 );

/**
 * Re-bootstrap Berkeley College application post with native Gutenberg blocks.
 */
function hlm_kadence_seo_bootstrap_posts_v13(): void {
	if ( get_option( 'hlm_seo_posts_v13' ) || ! class_exists( 'HLM_SEO\Content_Bootstrap' ) ) {
		return;
	}

	do_action( 'hlm_seo_bootstrap_posts' );
	update_option( 'hlm_seo_posts_v13', 1, false );
}
add_action( 'init', 'hlm_kadence_seo_bootstrap_posts_v13', 42 );

/**
 * Re-bootstrap all remaining cluster posts converted to Gutenberg blocks.
 */
function hlm_kadence_seo_bootstrap_posts_v14(): void {
	if ( get_option( 'hlm_seo_posts_v14' ) || ! class_exists( 'HLM_SEO\Content_Bootstrap' ) ) {
		return;
	}

	do_action( 'hlm_seo_bootstrap_posts' );
	update_option( 'hlm_seo_posts_v14', 1, false );
}
add_action( 'init', 'hlm_kadence_seo_bootstrap_posts_v14', 42 );

/**
 * Refresh About Us leadership team and Privacy Policy (June 2026).
 */
function hlm_kadence_seo_refresh_about_privacy(): void {
	if ( get_option( 'hlm_seo_about_privacy_v1' ) || ! function_exists( 'hlm_get_pattern_markup' ) ) {
		return;
	}

	$updates = array(
		array( 'slug' => 'about-us', 'pattern' => 'about-us-page.php' ),
		array( 'slug' => 'privacy-policy', 'pattern' => 'privacy-page.php' ),
	);

	foreach ( $updates as $page ) {
		$content = hlm_get_pattern_markup( $page['pattern'] );
		if ( '' === $content ) {
			continue;
		}

		$existing = get_page_by_path( $page['slug'] );
		if ( $existing instanceof WP_Post ) {
			wp_update_post(
				array(
					'ID'           => (int) $existing->ID,
					'post_content' => $content,
				)
			);
		}
	}

	update_option( 'hlm_seo_about_privacy_v1', 1, false );
}
add_action( 'init', 'hlm_kadence_seo_refresh_about_privacy', 37 );

/**
 * Phase 2 on-page SEO refresh for the Affiliations page.
 */
function hlm_kadence_seo_phase2_affiliations(): void {
	if ( get_option( 'hlm_seo_phase2_affiliations_v1' ) || ! function_exists( 'hlm_get_pattern_markup' ) ) {
		return;
	}

	$content = hlm_get_pattern_markup( 'affiliations-page.php' );
	if ( '' === $content ) {
		return;
	}

	$page = get_page_by_path( 'affiliations' );
	if ( $page instanceof WP_Post ) {
		wp_update_post(
			array(
				'ID'           => (int) $page->ID,
				'post_content' => $content,
			)
		);

		if ( class_exists( 'HLM_SEO\Registry' ) ) {
			HLM_SEO\Registry::apply_page_meta( (int) $page->ID, 'affiliations' );
		}
	}

	update_option( 'hlm_seo_phase2_affiliations_v1', 1, false );
}
add_action( 'init', 'hlm_kadence_seo_phase2_affiliations', 39 );

/**
 * SEO registry entries for child service detail pages.
 *
 * @return array<string, array<string, mixed>>
 */
function hlm_kadence_seo_service_page_registry(): array {
	return array(
		'lead-generation' => array(
			'title'           => 'Student Lead Generation for U.S. Universities - Higher Learning Marketers',
			'description'     => 'TCPA-compliant student lead generation for U.S. universities and colleges. Verified, consent-based inquiries delivered in real time to admissions teams.',
			'focus_keyword'   => 'student lead generation',
			'schema_type'     => 'Service',
			'page_role'       => 'service',
			'conversion_goal' => 'university_inquiry',
			'index'           => true,
			'priority'        => 0.75,
			'changefreq'      => 'monthly',
		),
		'brand-awareness' => array(
			'title'           => 'Education Brand Awareness for U.S. Institutions - Higher Learning Marketers',
			'description'     => 'Education brand awareness campaigns for U.S. universities and colleges. Multi-channel visibility across display, social, and retargeting.',
			'focus_keyword'   => 'education brand awareness',
			'schema_type'     => 'Service',
			'page_role'       => 'service',
			'conversion_goal' => 'university_inquiry',
			'index'           => true,
			'priority'        => 0.7,
			'changefreq'      => 'monthly',
		),
		'multi-channel-marketing' => array(
			'title'           => 'Multi-Channel Enrollment Marketing for Universities - Higher Learning Marketers',
			'description'     => 'Integrated multi-channel enrollment marketing for U.S. universities. Search, social, email, and partner campaigns under one strategy.',
			'focus_keyword'   => 'multi-channel enrollment marketing',
			'schema_type'     => 'Service',
			'page_role'       => 'service',
			'conversion_goal' => 'university_inquiry',
			'index'           => true,
			'priority'        => 0.7,
			'changefreq'      => 'monthly',
		),
		'analytics-reporting' => array(
			'title'           => 'Enrollment Marketing Analytics and Reporting - Higher Learning Marketers',
			'description'     => 'Enrollment marketing analytics and reporting for U.S. universities. Track cost per lead, cost per enrollment, and campaign ROI in real time.',
			'focus_keyword'   => 'enrollment marketing analytics',
			'schema_type'     => 'Service',
			'page_role'       => 'service',
			'conversion_goal' => 'university_inquiry',
			'index'           => true,
			'priority'        => 0.7,
			'changefreq'      => 'monthly',
		),
		'partner-network' => array(
			'title'           => 'Education Partner Network for Student Reach - Higher Learning Marketers',
			'description'     => 'Education partner network with 560+ sites reaching U.S. students. Compliance-reviewed placements and consent-based lead generation.',
			'focus_keyword'   => 'education partner network',
			'schema_type'     => 'Service',
			'page_role'       => 'service',
			'conversion_goal' => 'university_inquiry',
			'index'           => true,
			'priority'        => 0.7,
			'changefreq'      => 'monthly',
		),
		'enrollment-funnel' => array(
			'title'           => 'Enrollment Funnel Optimization for Universities - Higher Learning Marketers',
			'description'     => 'Enrollment funnel optimization for U.S. universities. Landing page, form, and CRO improvements that raise inquiry-to-application rates.',
			'focus_keyword'   => 'enrollment funnel optimization',
			'schema_type'     => 'Service',
			'page_role'       => 'service',
			'conversion_goal' => 'university_inquiry',
			'index'           => true,
			'priority'        => 0.7,
			'changefreq'      => 'monthly',
		),
	);
}

/**
 * Render Phase 2 SEO intro on service detail pages.
 */
function hlm_kadence_render_service_seo_intro(): void {
	if ( ! function_exists( 'hlm_kadence_is_service_detail_page' ) || ! hlm_kadence_is_service_detail_page() ) {
		return;
	}

	$post = get_queried_object();
	if ( ! $post instanceof WP_Post ) {
		return;
	}

	$intros = hlm_kadence_seo_page_intros();
	if ( empty( $intros[ $post->post_name ] ) ) {
		return;
	}

	$body = function_exists( 'hlm_seo_normalize_dashes' ) ? hlm_seo_normalize_dashes( $intros[ $post->post_name ] ) : $intros[ $post->post_name ];

	printf(
		'<section class="hlm-seo-intro hlm-single-service-seo-intro" aria-label="%1$s"><div class="hlm-seo-intro__body hlm-body-lg">%2$s</div></section>',
		esc_attr__( 'Overview', 'hlm-seo' ),
		wp_kses_post( $body )
	);
}

/**
 * Render grouped internal links on service detail pages.
 *
 * @param string $group body|cta.
 */
function hlm_kadence_render_service_seo_links( string $group ): void {
	if ( ! function_exists( 'hlm_kadence_is_service_detail_page' ) || ! hlm_kadence_is_service_detail_page() || ! class_exists( 'HLM_SEO\Internal_Links' ) ) {
		return;
	}

	$post = get_queried_object();
	if ( ! $post instanceof WP_Post ) {
		return;
	}

	$groups = \HLM_SEO\Internal_Links::get_page_link_groups( $post );
	$links  = 'cta' === $group ? ( $groups['cta'] ?? array() ) : ( $groups['body'] ?? array() );

	if ( empty( $links ) ) {
		return;
	}

	// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in template.
	echo \HLM_SEO\Internal_Links::render_page_links(
		$links,
		'cta' === $group ? 'footer' : 'strip',
		'cta' === $group ? __( 'Next steps', 'hlm-seo' ) : __( 'Related pages', 'hlm-seo' )
	);
}

/**
 * Render Phase 2 FAQ block on service detail pages.
 */
function hlm_kadence_render_service_seo_faqs(): void {
	if ( ! function_exists( 'hlm_kadence_is_service_detail_page' ) || ! hlm_kadence_is_service_detail_page() || ! class_exists( 'HLM_SEO\Lead_Gen' ) || ! function_exists( 'hlm_seo_get_current_faqs' ) ) {
		return;
	}

	$faqs = hlm_seo_get_current_faqs();
	if ( empty( $faqs ) ) {
		return;
	}

	$block = \HLM_SEO\Lead_Gen::render_page_faqs( $faqs );
	if ( '' === $block ) {
		return;
	}

	// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in renderer.
	echo $block;
}

/**
 * Phase 2 on-page SEO refresh for service detail pages.
 */
function hlm_kadence_seo_phase2_service_details(): void {
	if ( get_option( 'hlm_seo_phase2_service_details_v1' ) || ! function_exists( 'hlm_service_catalog' ) ) {
		return;
	}

	foreach ( array_keys( hlm_service_catalog() ) as $slug ) {
		$page = get_page_by_path( 'services/' . $slug );
		if ( ! $page instanceof WP_Post ) {
			continue;
		}

		if ( class_exists( 'HLM_SEO\Registry' ) ) {
			HLM_SEO\Registry::apply_page_meta( (int) $page->ID, $slug );
		}
	}

	update_option( 'hlm_seo_phase2_service_details_v1', 1, false );
}
add_action( 'init', 'hlm_kadence_seo_phase2_service_details', 40 );
