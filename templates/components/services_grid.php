<?php $service_cards = $service_cards ?? []; ?>
<?php $overline = $overline ?? 'Core Services'; ?>
<?php $headline = $headline ?? 'Built for U.S. Higher Education'; ?>
<?php $description = $description ?? 'Every service is designed for enrollment teams competing for motivated students in the United States, with verified leads, program-level targeting, and full-funnel support.'; ?>
<section class="hlm-services hlm-section hlm-bg-cream">
	<div class="hlm-container">
		<div class="hlm-services__header">
			<?php if ($overline): ?><p class="hlm-overline"><?= e((string)($overline)) ?></p><?php endif; ?>
			<h2 class="hlm-h2"><?= e((string)($headline)) ?></h2>
			<?php if ($description): ?><p class="hlm-body-lg"><?= e((string)($description)) ?></p><?php endif; ?>
		</div>
		<div class="hlm-grid-3">
			<?php foreach ($service_cards as $service): ?>
			<article class="hlm-card hlm-service-card">
				<h3 class="hlm-h3"><?= e((string)($service['title'] ?? '')) ?></h3>
				<p class="hlm-body"><?= e((string)($service['description'] ?? '')) ?></p>
				<?php if (!empty($service['tags'])): ?>
				<div class="hlm-service-card__tags">
					<?php foreach (explode(',', (string) ($service['tags'] ?? '')) as $tag): ?>
					<span class="hlm-card__tag"><?= e(trim((string)($tag))) ?></span>
					<?php endforeach; ?>
				</div>
				<?php endif; ?>
				<?php if (!empty($service['link'])): ?>
				<a href="<?= e((string)($service['link'])) ?>" class="hlm-btn hlm-btn-outline hlm-service-card__cta">Learn More</a>
				<?php endif; ?>
			</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>
<?php
$service_cards = is_array($service_cards ?? null) ? $service_cards : [];
$overline = $overline ?? 'Core Services';
$headline = $headline ?? 'Built for U.S. Higher Education';
$description = $description ?? 'Every service is designed for enrollment teams competing for motivated students in the United States, with verified leads, program-level targeting, and full-funnel support.';
?>
<section class="hlm-services hlm-section hlm-bg-cream">
	<div class="hlm-container">
		<div class="hlm-services__header">
			<?php if (!empty($overline)): ?><p class="hlm-overline"><?= e((string) $overline) ?></p><?php endif; ?>
			<h2 class="hlm-h2"><?= e((string) $headline) ?></h2>
			<?php if (!empty($description)): ?><p class="hlm-body-lg"><?= e((string) $description) ?></p><?php endif; ?>
		</div>
		<div class="hlm-grid-3">
			<?php foreach ($service_cards as $service): ?>
			<article class="hlm-card hlm-service-card">
				<h3 class="hlm-h3"><?= e((string) ($service['title'] ?? '')) ?></h3>
				<p class="hlm-body"><?= e((string) ($service['description'] ?? '')) ?></p>
				<?php if (!empty($service['tags'])): ?>
				<div class="hlm-service-card__tags">
					<?php foreach (explode(',', (string) $service['tags']) as $tag): ?>
					<span class="hlm-card__tag"><?= e(trim($tag)) ?></span>
					<?php endforeach; ?>
				</div>
				<?php endif; ?>
				<?php if (!empty($service['link'])): ?><a href="<?= e((string) $service['link']) ?>" class="hlm-btn hlm-btn-outline hlm-service-card__cta">Learn More</a><?php endif; ?>
			</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>
