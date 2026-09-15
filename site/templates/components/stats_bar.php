<?php $variant = $variant ?? 'bar'; ?>
<?php if ($variant === 'network'): ?>
<section class="hlm-stats hlm-stats--network hlm-section">
	<div class="hlm-container">
		<div class="hlm-section-header hlm-animate">
			<?php if ($overline): ?><div class="hlm-overline"><?= e((string)($overline)) ?></div><?php endif; ?>
			<?php if ($headline): ?><h2 class="hlm-h2"><?= e((string)($headline)) ?></h2><?php endif; ?>
			<?php if ($description): ?><p class="hlm-body-lg"><?= e((string)($description)) ?></p><?php endif; ?>
		</div>
		<div class="hlm-stats-grid hlm-stats-grid--network">
			<?php foreach ($stats as $stat): ?>
			<div class="hlm-stat-item">
				<div class="hlm-stat-number"><?= e((string)($stat['prefix'] ?? '')) ?><?= e((string)($stat['number'])) ?><?= e((string)($stat['suffix'] ?? '')) ?></div>
				<div class="hlm-stat-label"><?= e((string)($stat['label'])) ?></div>
			</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
<?php else: ?>
<section class="hlm-stats">
	<div class="hlm-stats-grid">
		<?php foreach ($stats as $stat): ?>
		<div class="hlm-stat-item">
			<div class="hlm-stat-number"><?= e((string)($stat['prefix'] ?? '')) ?><?= e((string)($stat['number'])) ?><?= e((string)($stat['suffix'] ?? '')) ?></div>
			<div class="hlm-stat-label"><?= e((string)($stat['label'])) ?></div>
		</div>
		<?php endforeach; ?>
	</div>
</section>
<?php endif; ?>
