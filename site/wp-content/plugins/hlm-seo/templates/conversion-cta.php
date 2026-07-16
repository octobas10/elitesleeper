<?php
/**
 * Conversion page sticky CTA template.
 *
 * @package HLM_SEO
 *
 * @var array $args Template args.
 */

defined( 'ABSPATH' ) || exit;

$config = $args['config'] ?? array();
?>
<div class="hlm-seo-sticky-cta" role="complementary" aria-label="<?php esc_attr_e( 'Contact call to action', 'hlm-seo' ); ?>">
	<p class="hlm-seo-sticky-cta__text"><?php esc_html_e( 'Questions? Our team responds within one business day.', 'hlm-seo' ); ?></p>
	<a class="hlm-seo-sticky-cta__btn" href="#form"><?php esc_html_e( 'Start Your Inquiry', 'hlm-seo' ); ?></a>
</div>
