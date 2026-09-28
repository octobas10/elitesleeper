<?php
partial('components/page_hero', [
	'overline' => 'Legal',
	'headline' => 'Privacy Policy',
	'description' => 'How Higher Learning Marketers collects, uses, shares, and protects your personal information.',
]);
?>
<section class="hlm-policy hlm-section">
	<div class="hlm-container hlm-policy__inner">
		<?php if (!empty($privacy_intro)): ?>
		<div class="hlm-policy__section hlm-body-lg">
			<?= nl2br(e((string) $privacy_intro)) ?>
		</div>
		<?php else: ?>
		<p class="hlm-body-lg">Effective date: September 22, 2026. Last updated: September 22, 2026.</p>
		<?php endif; ?>
		<?php foreach (($privacy_sections ?? []) as $section): ?>
		<article class="hlm-policy__section">
			<h2 class="hlm-h2"><?= e((string) ($section['title'] ?? '')) ?></h2>
			<div class="hlm-body"><?= nl2br(e((string) ($section['content'] ?? ''))) ?></div>
		</article>
		<?php endforeach; ?>
		<p class="hlm-policy__contact">Questions or opt-out requests: <?= e((string) ($contact['email'] ?? '')) ?>, <?= e((string) ($contact['phoneDisplay'] ?? '')) ?>, <?= e((string) ($contact['addressDisplay'] ?? '')) ?>.</p>
	</div>
</section>
