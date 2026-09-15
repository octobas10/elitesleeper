<?php partial('components/page_hero', ['overline' => 'Legal', 'headline' => 'Privacy Policy', 'description' => 'How Higher Learning Marketers collects, uses, and protects your personal information.']); ?>
<section class="hlm-policy hlm-section">
	<div class="hlm-container hlm-policy__inner">
		<p class="hlm-body-lg">Higher Learning Marketers is committed to transparent, consent-based data practices in U.S. education marketing. Last updated: June 17, 2026.</p>
		<?php foreach (($privacy_sections ?? []) as $section): ?><article class="hlm-policy__section"><h2 class="hlm-h2"><?= e((string) ($section['title'] ?? '')) ?></h2><p><?= e((string) ($section['content'] ?? '')) ?></p></article><?php endforeach; ?>
		<p class="hlm-policy__contact">Questions or opt-out requests: <a href="mailto:<?= e((string) ($contact['email'] ?? '')) ?>"><?= e((string) ($contact['email'] ?? '')) ?></a>, <a href="<?= e((string) ($contact['phoneHref'] ?? 'tel:+10000000000')) ?>"><?= e((string) ($contact['phoneDisplay'] ?? '')) ?></a>, <?= e((string) ($contact['addressDisplay'] ?? '')) ?>.</p>
	</div>
</section>
