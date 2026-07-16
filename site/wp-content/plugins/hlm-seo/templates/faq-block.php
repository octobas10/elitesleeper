<?php
/**
 * FAQ accordion block template.
 *
 * @package HLM_SEO
 *
 * @var array $args Template args.
 */

defined( 'ABSPATH' ) || exit;

$faqs = $args['faqs'] ?? array();
if ( empty( $faqs ) ) {
	return;
}
?>
<section class="hlm-seo-faq hlm-section hlm-section--sm hlm-bg-cream" aria-labelledby="hlm-seo-faq-title">
	<div class="hlm-container">
		<div class="hlm-section-header hlm-seo-faq__header">
			<div class="hlm-overline"><?php esc_html_e( 'Questions & Answers', 'hlm-seo' ); ?></div>
			<h2 id="hlm-seo-faq-title" class="hlm-h2"><?php esc_html_e( 'Frequently Asked Questions', 'hlm-seo' ); ?></h2>
		</div>
		<div class="hlm-seo-faq__list">
			<?php foreach ( $faqs as $faq ) : ?>
				<?php if ( empty( $faq['question'] ) || empty( $faq['answer'] ) ) : ?>
					<?php continue; ?>
				<?php endif; ?>
				<details class="hlm-seo-faq__item">
					<summary class="hlm-seo-faq__question"><?php echo esc_html( $faq['question'] ); ?></summary>
					<div class="hlm-seo-faq__answer">
						<p><?php echo esc_html( $faq['answer'] ); ?></p>
					</div>
				</details>
			<?php endforeach; ?>
		</div>
	</div>
</section>
