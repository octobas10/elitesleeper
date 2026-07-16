<?php
/**
 * Service detail sidebar - lead form + sibling service links.
 *
 * @package HLM_Kadence
 */

defined( 'ABSPATH' ) || exit;

$post      = get_queried_object();
$parent_id = function_exists( 'hlm_services_parent_page_id' ) ? hlm_services_parent_page_id() : 0;
$siblings  = array();

if ( $post instanceof WP_Post && $parent_id ) {
	$siblings = get_posts(
		array(
			'post_type'      => 'page',
			'post_status'    => 'publish',
			'post_parent'    => $parent_id,
			'posts_per_page' => -1,
			'orderby'        => 'menu_order title',
			'order'          => 'ASC',
		)
	);
}
?>

<aside class="hlm-single-post-sidebar hlm-single-service-sidebar" aria-label="<?php esc_attr_e( 'Service sidebar', 'hlm-kadence' ); ?>">
	<div class="hlm-single-post-sidebar__sticky">
		<div class="hlm-form-card hlm-single-post-sidebar__form">
			<div class="hlm-single-post-sidebar__form-header">
				<div class="hlm-overline"><?php esc_html_e( 'Get Started', 'hlm-kadence' ); ?></div>
				<h2 class="hlm-h4"><?php esc_html_e( 'Talk to Our Team', 'hlm-kadence' ); ?></h2>
				<p class="hlm-body hlm-single-post-sidebar__form-desc">
					<?php esc_html_e( 'Tell us about your enrollment goals and we will recommend the right mix of services for your institution.', 'hlm-kadence' ); ?>
				</p>
			</div>
			<?php if ( shortcode_exists( 'hlm_lead_form' ) ) : ?>
				<?php echo do_shortcode( '[hlm_lead_form]' ); ?>
			<?php else : ?>
				<p class="hlm-form-placeholder"><?php esc_html_e( 'Activate the HLM Lead Capture plugin to display the lead form.', 'hlm-kadence' ); ?></p>
			<?php endif; ?>
		</div>

		<?php if ( $siblings ) : ?>
			<div class="hlm-single-post-sidebar__topics">
				<div class="hlm-overline"><?php esc_html_e( 'All Services', 'hlm-kadence' ); ?></div>
				<ul class="hlm-single-post-sidebar__topic-list">
					<?php foreach ( $siblings as $sibling ) : ?>
						<li>
							<a
								class="hlm-single-post-sidebar__topic-link<?php echo ( $post instanceof WP_Post && (int) $sibling->ID === (int) $post->ID ) ? ' hlm-single-post-sidebar__topic-link--featured' : ''; ?>"
								href="<?php echo esc_url( get_permalink( $sibling ) ); ?>"
								<?php echo ( $post instanceof WP_Post && (int) $sibling->ID === (int) $post->ID ) ? ' aria-current="page"' : ''; ?>
							>
								<?php echo esc_html( get_the_title( $sibling ) ); ?>
							</a>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>
		<?php endif; ?>

		<a class="hlm-single-post-sidebar__back" href="<?php echo esc_url( home_url( '/services/' ) ); ?>">
			<?php esc_html_e( '← Back to Services', 'hlm-kadence' ); ?>
		</a>
	</div>
</aside>
