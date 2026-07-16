<?php
/**
 * Single post sidebar - lead form + topic links.
 *
 * @package HLM_Kadence
 */

defined( 'ABSPATH' ) || exit;

$post = get_queried_object();
$cluster_items = array();

if ( $post instanceof WP_Post && class_exists( 'HLM_SEO\Internal_Links' ) ) {
	$cluster_items = \HLM_SEO\Internal_Links::get_cluster_link_items( $post );
}
?>

<aside class="hlm-single-post-sidebar" aria-label="<?php esc_attr_e( 'Article sidebar', 'hlm-kadence' ); ?>">
	<div class="hlm-single-post-sidebar__sticky">
		<div class="hlm-form-card hlm-single-post-sidebar__form">
			<div class="hlm-single-post-sidebar__form-header">
				<div class="hlm-overline"><?php esc_html_e( 'Get Started', 'hlm-kadence' ); ?></div>
				<h2 class="hlm-h4"><?php esc_html_e( 'Connect With an Advisor', 'hlm-kadence' ); ?></h2>
				<p class="hlm-body hlm-single-post-sidebar__form-desc">
					<?php esc_html_e( 'Tell us about your goals and we will help you find the right program or partnership.', 'hlm-kadence' ); ?>
				</p>
			</div>
			<?php if ( shortcode_exists( 'hlm_lead_form' ) ) : ?>
				<?php echo do_shortcode( '[hlm_lead_form]' ); ?>
			<?php else : ?>
				<p class="hlm-form-placeholder"><?php esc_html_e( 'Activate the HLM Lead Capture plugin to display the lead form.', 'hlm-kadence' ); ?></p>
			<?php endif; ?>
		</div>

		<?php if ( $cluster_items ) : ?>
			<div class="hlm-single-post-sidebar__topics">
				<div class="hlm-overline"><?php esc_html_e( 'In This Topic', 'hlm-kadence' ); ?></div>
				<ul class="hlm-single-post-sidebar__topic-list">
					<?php foreach ( $cluster_items as $item ) : ?>
						<li>
							<a
								class="hlm-single-post-sidebar__topic-link<?php echo ! empty( $item['featured'] ) ? ' hlm-single-post-sidebar__topic-link--featured' : ''; ?>"
								href="<?php echo esc_url( $item['url'] ); ?>"
							>
								<?php echo esc_html( $item['label'] ); ?>
							</a>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>
		<?php endif; ?>

		<a class="hlm-single-post-sidebar__back" href="<?php echo esc_url( home_url( '/blog/' ) ); ?>">
			<?php esc_html_e( '← Back to Blog', 'hlm-kadence' ); ?>
		</a>
	</div>
</aside>
