<?php
/**
 * Service detail pages and services grid integration.
 *
 * @package HLM_Kadence
 */

defined( 'ABSPATH' ) || exit;

/**
 * Canonical service catalog keyed by slug.
 *
 * @return array<string, array<string, mixed>>
 */
function hlm_service_catalog() {
	static $catalog = null;

	if ( null !== $catalog ) {
		return $catalog;
	}

	$catalog = array(
		'lead-generation'         => array(
			'title'    => 'Lead Generation',
			'headline' => 'Student Lead Generation for U.S. Universities',
			'icon'     => 'users',
			'excerpt'  => 'TCPA-compliant, consent-based student inquiries delivered in real time to U.S. university and college admissions teams.',
			'tags'    => 'TCPA Compliant, Real-time, Custom Filtering, Scalable',
			'intro'   => 'Our lead generation programs connect your admissions team with students who have already expressed interest in programs like yours. Every inquiry is verified, consent-based, and delivered in real time so your counselors can respond while intent is highest.',
			'highlights' => array(
				'Custom audience targeting by program, geography, and degree level',
				'TCPA-compliant consent capture on every lead',
				'Real-time delivery to your CRM or admissions inbox',
				'Quality filters and duplicate suppression',
				'Weekly volume and conversion reporting',
			),
			'audience' => 'Ideal for universities and colleges that need predictable inquiry volume, faster counselor follow-up, and transparent lead quality-not just raw form fills.',
			'metrics'  => array(
				array( 'value' => 'Real-time', 'label' => 'Lead delivery to your CRM' ),
				array( 'value' => 'TCPA', 'label' => 'Compliant consent on every lead' ),
				array( 'value' => 'Weekly', 'label' => 'Volume and quality reporting' ),
			),
		),
		'brand-awareness'           => array(
			'title'    => 'Brand Awareness',
			'headline' => 'Education Brand Awareness for U.S. Institutions',
			'icon'     => 'globe',
			'excerpt'  => 'Multi-channel campaigns that keep your U.S. institution visible to students actively researching degree and certificate options.',
			'tags'    => 'Display Ads, Landing Pages, Retargeting, Segmentation',
			'intro'   => 'Brand awareness campaigns keep your institution visible across the channels students use when they start exploring schools. We combine creative, media, and landing experiences so your programs stay top of mind before students are ready to apply.',
			'highlights' => array(
				'Audience research and competitive positioning',
				'Display, social, and retargeting creative',
				'Program-specific landing pages',
				'Geo and demographic segmentation',
				'Reach, frequency, and engagement reporting',
			),
			'audience' => 'Best for institutions launching new programs, entering new markets, or rebuilding visibility in competitive categories where name recognition drives inquiry quality.',
		),
		'multi-channel-marketing'   => array(
			'title'    => 'Multi-Channel Marketing',
			'headline' => 'Multi-Channel Enrollment Marketing for Universities',
			'icon'     => 'megaphone',
			'excerpt'  => 'Integrated enrollment campaigns across search, social, email, and partner networks for U.S. universities and colleges.',
			'tags'    => 'SEO, Social, Email, PPC',
			'intro'   => 'Students rarely convert on a single touchpoint. Our multi-channel approach coordinates search, social, email, and partner placements so each interaction reinforces the next-and moves prospects closer to enrollment.',
			'highlights' => array(
				'Cross-channel campaign strategy and calendar',
				'Paid search and social management',
				'Email nurture sequences for inquiry follow-up',
				'Partner network distribution',
				'Unified reporting across channels',
			),
			'audience' => 'Designed for enrollment teams that want one coordinated plan instead of disconnected vendors-and need clear attribution across the full student journey.',
		),
		'analytics-reporting'       => array(
			'title'    => 'Analytics & Reporting',
			'headline' => 'Enrollment Marketing Analytics and Reporting',
			'icon'     => 'chart',
			'excerpt'  => 'Real-time dashboards and enrollment reporting so U.S. marketing and admissions leaders track cost per lead and cost per start.',
			'tags'    => 'Dashboards, Cost Tracking, Attribution, Reviews',
			'intro'   => 'Enrollment marketing only improves when you can see what is working. We provide dashboards and reporting cadences that tie spend, inquiries, and downstream enrollment outcomes back to the campaigns that drove them.',
			'highlights' => array(
				'Live performance dashboards with source-level drill-down',
				'Cost-per-inquiry and cost-per-enrollment tracking',
				'Multi-touch source and channel attribution',
				'Weekly optimization reviews with your team',
				'Executive-ready summary reports',
			),
			'audience' => 'Perfect for marketing and enrollment leaders who need transparent ROI, faster budget decisions, and a single source of truth for campaign performance.',
			'metrics'  => array(
				array( 'value' => 'Live', 'label' => 'Performance dashboards' ),
				array( 'value' => 'CPI / CPE', 'label' => 'Cost tracking built in' ),
				array( 'value' => 'Weekly', 'label' => 'Optimization reviews' ),
			),
		),
		'partner-network'           => array(
			'title'    => 'Partner Network',
			'headline' => 'Education Partner Network for Student Reach',
			'icon'     => 'network',
			'excerpt'  => 'Access to 560+ education-focused websites and platforms that reach U.S. students at every stage of their decision journey.',
			'tags'     => '560+ Sites, U.S. Focus, Targeting, Exclusive',
			'intro'   => 'Our partner network extends your reach beyond owned channels. We place your programs on education-focused sites where students compare schools, explore career paths, and request information from trusted sources.',
			'highlights' => array(
				'Placement across 560+ education properties',
				'Program-level targeting and exclusivity options',
				'International reach in 164 countries',
				'Compliance review for every partner placement',
				'Network-level performance reporting',
			),
			'audience' => 'Ideal when you need scale quickly, want incremental volume without increasing internal workload, or are expanding into new student segments and geographies.',
			'metrics'  => array(
				array( 'value' => '560+', 'label' => 'Education partner sites' ),
				array( 'value' => '164', 'label' => 'Countries reached' ),
				array( 'value' => '100%', 'label' => 'Compliance-reviewed placements' ),
			),
		),
		'enrollment-funnel'         => array(
			'title'    => 'Enrollment Funnel',
			'headline' => 'Enrollment Funnel Optimization for Universities',
			'icon'     => 'target',
			'excerpt'  => 'End-to-end funnel optimization from first impression to application submitted for U.S. enrollment teams.',
			'tags'    => 'Landing Pages, A/B Testing, Forms, CRO',
			'intro'   => 'Every step between first click and submitted application affects enrollment. We audit and optimize landing pages, forms, messaging, and follow-up sequences so more interested students complete the actions that matter.',
			'highlights' => array(
				'Funnel audit across ads, landing pages, and forms',
				'A/B testing on headlines, CTAs, and form fields',
				'Mobile-first landing page improvements',
				'Conversion rate optimization sprints',
				'Before/after performance benchmarks',
			),
			'audience' => 'Best for teams generating traffic but struggling to convert inquiries into applications-or anyone preparing for a peak enrollment season who needs higher conversion without increasing media spend.',
		),
	);

	foreach ( $catalog as $slug => $entry ) {
		$catalog[ $slug ]['content'] = hlm_services_build_detail_block_content( $entry );
	}

	return $catalog;
}

/**
 * Services parent page ID.
 *
 * @return int
 */
function hlm_services_parent_page_id() {
	static $page_id = null;

	if ( null !== $page_id ) {
		return $page_id;
	}

	$page = get_page_by_path( 'services' );
	$page_id = ( $page instanceof WP_Post ) ? (int) $page->ID : 0;

	return $page_id;
}

/**
 * Whether the current view is a child service detail page.
 *
 * @param WP_Post|null $post Optional post object.
 * @return bool
 */
function hlm_kadence_is_service_detail_page( $post = null ) {
	if ( null === $post ) {
		if ( ! is_singular( 'page' ) ) {
			return false;
		}

		$post = get_queried_object();
	}

	if ( ! $post instanceof WP_Post ) {
		return false;
	}

	$parent_id = hlm_services_parent_page_id();

	return $parent_id > 0 && (int) $post->post_parent === $parent_id;
}

/**
 * Resolve a public URL for a service slug or title.
 *
 * @param string $slug_or_title Service slug or title.
 * @return string
 */
function hlm_service_url( $slug_or_title ) {
	$slug_or_title = trim( (string) $slug_or_title );

	if ( '' === $slug_or_title ) {
		return home_url( '/services/' );
	}

	if ( str_contains( $slug_or_title, '://' ) ) {
		return esc_url( $slug_or_title );
	}

	if ( str_starts_with( $slug_or_title, '/' ) ) {
		return home_url( $slug_or_title );
	}

	$slug = sanitize_title( $slug_or_title );

	return home_url( '/services/' . $slug . '/' );
}

/**
 * Resolve catalog entry for the current or given service page.
 *
 * @param WP_Post|null $post Optional post object.
 * @return array|null
 */
function hlm_service_catalog_entry( $post = null ) {
	if ( null === $post ) {
		$post = get_queried_object();
	}

	if ( ! $post instanceof WP_Post ) {
		return null;
	}

	$catalog = hlm_service_catalog();

	return $catalog[ $post->post_name ] ?? null;
}

/**
 * Breadcrumb trail for service detail pages.
 *
 * @param array<int, array<string, string>> $trail Breadcrumb items.
 * @return array<int, array<string, string>>
 */
function hlm_services_breadcrumb_trail( array $trail ): array {
	if ( ! hlm_kadence_is_service_detail_page() ) {
		return $trail;
	}

	$parent_id = hlm_services_parent_page_id();

	$trail = array(
		array(
			'label' => __( 'Home', 'hlm-kadence' ),
			'url'   => home_url( '/' ),
		),
	);

	if ( $parent_id ) {
		$trail[] = array(
			'label' => get_the_title( $parent_id ),
			'url'   => get_permalink( $parent_id ),
		);
	}

	$trail[] = array(
		'label' => get_the_title(),
		'url'   => '',
	);

	return $trail;
}
add_filter( 'hlm_seo_breadcrumb_trail', 'hlm_services_breadcrumb_trail' );

/**
 * Enqueue service detail styles.
 */
function hlm_services_enqueue_assets() {
	if ( ! hlm_kadence_is_service_detail_page() ) {
		return;
	}

	wp_enqueue_style(
		'hlm-services',
		HLM_KADENCE_URI . '/assets/hlm/hlm-services.css',
		array( 'hlm-blog' ),
		HLM_KADENCE_VERSION
	);
}
add_action( 'wp_enqueue_scripts', 'hlm_services_enqueue_assets', 20 );

/**
 * Register service detail Kadence hooks.
 */
function hlm_services_register_hooks() {
	if ( ! hlm_kadence_is_service_detail_page() ) {
		return;
	}

	remove_action( 'kadence_hero_header', 'Kadence\hero_title' );
	add_action( 'kadence_hero_header', 'hlm_services_render_single_hero' );

	add_action( 'kadence_before_main_content', 'hlm_services_single_open', 5 );
	add_action( 'kadence_single_content', 'hlm_services_single_main_open', 5 );
	add_action( 'kadence_single_after_content', 'hlm_services_single_finish_layout', 50 );
	add_action( 'kadence_after_main_content', 'hlm_services_single_close', 15 );
	add_action( 'kadence_after_main_content', 'hlm_services_render_single_cta', 20 );
}
add_action( 'wp', 'hlm_services_register_hooks' );

/**
 * Replace stored page content with catalog-driven service layout.
 *
 * @param string $content Post content.
 * @return string
 */
function hlm_services_filter_detail_content( $content ) {
	if ( ! is_singular( 'page' ) || ! in_the_loop() || ! is_main_query() ) {
		return $content;
	}

	if ( ! hlm_kadence_is_service_detail_page() ) {
		return $content;
	}

	$entry = hlm_service_catalog_entry();
	if ( ! $entry ) {
		return $content;
	}

	return hlm_services_render_detail_body( $entry );
}
add_filter( 'the_content', 'hlm_services_filter_detail_content', 20 );

/**
 * Kadence layout overrides for service detail pages.
 *
 * @param array $layout Kadence layout config.
 * @return array
 */
function hlm_services_post_layout( $layout ) {
	if ( hlm_kadence_is_service_detail_page() ) {
		$layout['title']      = 'hide';
		$layout['sidebar']    = 'disable';
		$layout['layout']     = 'fullwidth';
		$layout['comments']   = 'hide';
		$layout['navigation'] = 'hide';
	}

	return $layout;
}
add_filter( 'kadence_post_layout', 'hlm_services_post_layout' );

/**
 * Render the service detail hero.
 */
function hlm_services_render_single_hero() {
	$entry = hlm_service_catalog_entry();

	hlm_render_page_hero(
		array(
			'id'             => 'service-detail-hero',
			'icon'           => $entry['icon'] ?? '',
			'overline'       => __( 'Services', 'hlm-kadence' ),
			'headline'       => $entry['headline'] ?? get_the_title(),
			'description'    => $entry['excerpt'] ?? get_the_excerpt(),
			'tags'           => $entry['tags'] ?? '',
			'headline_class' => 'hlm-h1 hlm-h1--wide',
		)
	);
}

/**
 * Open the service detail section shell.
 */
function hlm_services_single_open() {
	echo '<section class="hlm-single-post-wrap hlm-single-service-wrap hlm-section hlm-bg-cream">';
	echo '<div class="hlm-container hlm-single-post-container">';
}

/**
 * Open the two-column grid and main column.
 */
function hlm_services_single_main_open() {
	echo '<div class="hlm-single-post-layout hlm-single-service-layout">';
	echo '<div class="hlm-single-post-main hlm-single-service-main">';
}

/**
 * Close main column, render sidebar, close grid, then related services full-width.
 */
function hlm_services_single_finish_layout() {
	echo '</div><!-- .hlm-single-service-main -->';

	get_template_part( 'template-parts/hlm/single-service-sidebar' );

	echo '</div><!-- .hlm-single-service-layout -->';

	if ( function_exists( 'hlm_kadence_render_service_seo_links' ) ) {
		hlm_kadence_render_service_seo_links( 'body' );
	}

	hlm_services_render_single_related();

	if ( function_exists( 'hlm_kadence_render_service_seo_faqs' ) ) {
		hlm_kadence_render_service_seo_faqs();
	}

	if ( function_exists( 'hlm_kadence_render_service_seo_links' ) ) {
		hlm_kadence_render_service_seo_links( 'cta' );
	}
}

/**
 * Close the service detail section wrapper.
 */
function hlm_services_single_close() {
	echo '</div></section>';
}

/**
 * Render sibling service links as compact cards.
 */
function hlm_services_render_single_related() {
	$post = get_queried_object();
	if ( ! $post instanceof WP_Post ) {
		return;
	}

	$parent_id = hlm_services_parent_page_id();
	if ( ! $parent_id ) {
		return;
	}

	$siblings = get_posts(
		array(
			'post_type'      => 'page',
			'post_status'    => 'publish',
			'post_parent'    => $parent_id,
			'posts_per_page' => -1,
			'orderby'        => 'menu_order title',
			'order'          => 'ASC',
			'exclude'        => array( $post->ID ),
		)
	);

	if ( ! $siblings ) {
		return;
	}
	?>
	<section class="hlm-single-post-related hlm-single-service-related" aria-labelledby="hlm-single-service-related-title">
		<div class="hlm-section-header">
			<div class="hlm-overline"><?php esc_html_e( 'Explore More', 'hlm-kadence' ); ?></div>
			<h2 id="hlm-single-service-related-title" class="hlm-h2"><?php esc_html_e( 'Other Services', 'hlm-kadence' ); ?></h2>
		</div>
		<div class="hlm-single-service-related__grid">
			<?php
			$index = 0;
			foreach ( array_slice( $siblings, 0, 3 ) as $sibling ) {
				++$index;
				$entry = hlm_service_catalog_entry( $sibling );
				?>
				<article class="hlm-card hlm-service-card hlm-service-card--compact hlm-animate hlm-animate--delay-<?php echo esc_attr( min( $index, 3 ) ); ?>">
					<?php if ( ! empty( $entry['icon'] ) ) : ?>
						<div class="hlm-card__icon"><?php echo hlm_render_icon( $entry['icon'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
					<?php endif; ?>
					<h3 class="hlm-h4">
						<a href="<?php echo esc_url( get_permalink( $sibling ) ); ?>"><?php echo esc_html( get_the_title( $sibling ) ); ?></a>
					</h3>
					<p class="hlm-body"><?php echo esc_html( $entry['excerpt'] ?? wp_strip_all_tags( get_the_excerpt( $sibling ) ) ); ?></p>
					<?php if ( ! empty( $entry['tags'] ) ) : ?>
						<div class="hlm-service-card__tags">
							<?php foreach ( array_slice( array_map( 'trim', explode( ',', $entry['tags'] ) ), 0, 3 ) as $tag ) : ?>
								<span class="hlm-card__tag"><?php echo esc_html( $tag ); ?></span>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>
					<a href="<?php echo esc_url( get_permalink( $sibling ) ); ?>" class="hlm-btn hlm-btn-outline hlm-btn-arrow hlm-service-card__cta">
						<?php esc_html_e( 'Learn More', 'hlm-kadence' ); ?>
					</a>
				</article>
				<?php
			}
			?>
		</div>
	</section>
	<?php
}

/**
 * CTA after service detail content.
 */
function hlm_services_render_single_cta() {
	hlm_render_cta_band(
		array(
			'id'             => 'service-single-cta',
			'overline'       => __( 'Next Step', 'hlm-kadence' ),
			'headline'       => __( 'Ready to Get Started?', 'hlm-kadence' ),
			'description'    => __( 'Talk to our team about how this service supports enrollment goals at your U.S. university or college.', 'hlm-kadence' ),
			'primary_text'   => __( 'Request a Quote', 'hlm-kadence' ),
			'primary_url'    => home_url( '/contact-us/#form' ),
			'secondary_text' => __( 'All Services', 'hlm-kadence' ),
			'secondary_url'  => home_url( '/services/' ),
		)
	);
}

/**
 * Create or update child service detail pages.
 */
function hlm_setup_service_pages() {
	if ( get_option( 'hlm_service_pages_v1' ) ) {
		return;
	}

	$parent_id = hlm_services_parent_page_id();
	if ( ! $parent_id ) {
		return;
	}

	foreach ( hlm_service_catalog() as $slug => $service ) {
		$page_id = hlm_upsert_page(
			array(
				'title'   => $service['title'],
				'slug'    => $slug,
				'content' => $service['content'],
				'parent'  => $parent_id,
			)
		);

		if ( $page_id && function_exists( 'hlm_kadence_apply_inner_page_meta' ) ) {
			hlm_kadence_apply_inner_page_meta( $page_id );
		}
	}

	update_option( 'hlm_service_pages_v1', 1, false );
}
add_action( 'hlm_kadence_pages_ready', 'hlm_setup_service_pages' );
add_action( 'init', 'hlm_setup_service_pages', 16 );
