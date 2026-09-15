<?php
$faqs = is_array($faqs ?? null) ? $faqs : [];
if ($faqs):
    $title = $title ?? 'Frequently Asked Questions';
    $overline = $overline ?? 'FAQ';
?>
<section class="hlm-seo-faq hlm-section" aria-labelledby="hlm-faq-title">
	<div class="hlm-container">
		<div class="hlm-seo-faq__header hlm-text-center">
			<?php if (!empty($overline)): ?><div class="hlm-overline"><?= e((string) $overline) ?></div><?php endif; ?>
			<h2 id="hlm-faq-title" class="hlm-h2"><?= e((string) $title) ?></h2>
		</div>
		<div class="hlm-seo-faq__list">
			<?php foreach ($faqs as $faq): ?>
			<details class="hlm-seo-faq__item">
				<summary class="hlm-seo-faq__question"><?= e((string) ($faq['question'] ?? '')) ?></summary>
				<div class="hlm-seo-faq__answer"><p><?= e((string) ($faq['answer'] ?? '')) ?></p></div>
			</details>
			<?php endforeach; ?>
		</div>
	</div>
</section>
<?php endif; ?>
