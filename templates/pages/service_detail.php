<?php
$service = is_array($service ?? null) ? $service : [];
$overline = 'Services';
$headline = (string) ($service['headline'] ?? ($service['title'] ?? 'Service'));
$description = (string) ($service['excerpt'] ?? '');
$image_url = $image_url ?? ($media['page_services'] ?? '');
$primary_cta_text = 'Request a Consultation';
$primary_cta_url = '/contact-us/#form';
partial('components/page_hero', compact('overline', 'headline', 'description', 'image_url', 'primary_cta_text', 'primary_cta_url'));
?>
<section class="hlm-section hlm-bg-cream">
	<div class="hlm-container">
		<?php if (!empty($seo_intro)): ?><div class="hlm-single-service-seo-intro"><?= $seo_intro ?></div><?php endif; ?>
		<?php if (!empty($service['intro'])): ?><p class="hlm-body-lg"><?= e((string) $service['intro']) ?></p><?php endif; ?>
		<?php if (!empty($service['highlights']) && is_array($service['highlights'])): ?>
		<ul class="hlm-related-services">
			<?php foreach ($service['highlights'] as $highlight): ?>
			<li><?= e((string) $highlight) ?></li>
			<?php endforeach; ?>
		</ul>
		<?php endif; ?>
	</div>
</section>
<?php
partial('components/faq_section', ['faqs' => $faqs ?? []]);
$variant = 'soft';
$overline = 'Get Started';
$headline = 'Start Your ' . ((string) ($service['title'] ?? 'Service')) . ' Campaign';
$description = 'Talk to our team about enrollment goals, program mix, and expected inquiry volume.';
$primary_text = 'Request a Consultation';
$primary_url = '/contact-us/#form';
$secondary_text = 'View All Services';
$secondary_url = '/services/';
partial('components/cta_banner', compact('variant', 'overline', 'headline', 'description', 'primary_text', 'primary_url', 'secondary_text', 'secondary_url'));
