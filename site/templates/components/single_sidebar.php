<?php
$variant = $variant ?? 'post';
$topics = is_array($topics ?? null) ? $topics : [];
if ($variant === 'service') {
    $topics_title = $topics_title ?? 'All Services';
    $form_title = $form_title ?? 'Talk to Our Team';
    $form_description = $form_description ?? 'Tell us about your enrollment goals and we will recommend the right mix of services for your institution.';
    $back_href = $back_href ?? '/services/';
    $back_label = $back_label ?? '← Back to Services';
    $cta_href = '/contact-us/#form';
    $cta_label = 'Schedule a Consultation';
    $aside_class = 'hlm-single-post-sidebar hlm-single-service-sidebar';
    $aside_label = 'Service sidebar';
} else {
    $topics_title = $topics_title ?? 'In This Topic';
    $form_title = $form_title ?? 'Connect With an Advisor';
    $form_description = $form_description ?? 'Tell us about your goals and we will help you find the right program or partnership.';
    $back_href = $back_href ?? '/blog/';
    $back_label = $back_label ?? '← Back to Blog';
    $cta_href = '/contact-us/#form';
    $cta_label = 'Get Started';
    $aside_class = 'hlm-single-post-sidebar';
    $aside_label = 'Article sidebar';
}
?>
<aside class="<?= e($aside_class) ?>" aria-label="<?= e($aside_label) ?>">
	<div class="hlm-single-post-sidebar__sticky">
		<div class="hlm-single-post-sidebar__form">
			<div class="hlm-single-post-sidebar__form-header">
				<div class="hlm-overline">Get Started</div>
				<h2 class="hlm-h4"><?= e((string) $form_title) ?></h2>
				<p class="hlm-body hlm-single-post-sidebar__form-desc"><?= e((string) $form_description) ?></p>
			</div>
			<a class="hlm-btn hlm-btn-primary hlm-btn--full" href="<?= e($cta_href) ?>"><?= e($cta_label) ?></a>
			<p class="hlm-single-post-sidebar__form-note">
				<a href="tel:+17186552573"><?= e((string) ($contact['phoneDisplay'] ?? '')) ?></a>
				<span aria-hidden="true"> · </span>
				<a href="mailto:<?= e((string) ($contact['email'] ?? '')) ?>">Email us</a>
			</p>
		</div>

		<?php if ($topics): ?>
		<div class="hlm-single-post-sidebar__topics">
			<div class="hlm-overline"><?= e((string) $topics_title) ?></div>
			<ul class="hlm-single-post-sidebar__topic-list">
				<?php foreach ($topics as $item): ?>
				<?php $featured = !empty($item['featured']); ?>
				<li>
					<a class="hlm-single-post-sidebar__topic-link<?= $featured ? ' hlm-single-post-sidebar__topic-link--featured' : '' ?>" href="<?= e((string) ($item['href'] ?? '#')) ?>" <?= $featured ? 'aria-current="page"' : '' ?>>
						<?= e((string) ($item['label'] ?? '')) ?>
					</a>
				</li>
				<?php endforeach; ?>
			</ul>
		</div>
		<?php endif; ?>

		<a class="hlm-single-post-sidebar__back" href="<?= e((string) $back_href) ?>"><?= e((string) $back_label) ?></a>
	</div>
</aside>
