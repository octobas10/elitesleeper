<?php $items = $items ?? ($items ?? []); ?>
<?php $overline = $overline ?? ($overline ?? 'Partner Institutions'); ?>
<?php $headline = $headline ?? ($headline ?? 'Top Universities & Programs'); ?>
<?php $description = $description ?? ($description ?? 'We partner with leading institutions across the United States, Canada, and online to connect students with the right educational opportunities.'); ?>
<section class="hlm-unis hlm-section">
	<div class="hlm-container">
		<div class="hlm-section-header">
			<?php if ($overline): ?><div class="hlm-overline"><?= e((string)($overline)) ?></div><?php endif; ?>
			<h2 class="hlm-h2"><?= e((string)($headline)) ?></h2>
			<?php if ($description): ?><p class="hlm-body-lg"><?= e((string)($description)) ?></p><?php endif; ?>
		</div>
		<div class="hlm-unis-grid">
			<?php foreach ($items as $item): ?>
			<div class="hlm-uni-card">
				<?php if ($item['imageUrl']): ?>
				<div class="hlm-uni-card__media">
					<img src="<?= e((string)($item['imageUrl'])) ?>" alt="<?= e((string)($item['title'])) ?>" loading="lazy" decoding="async" />
				</div>
				<?php endif; ?>
				<?php if ($item['badge']): ?><div class="hlm-uni-badge"><?= e((string)($item['badge'])) ?></div><?php endif; ?>
				<h3><?= e((string)($item['title'])) ?></h3>
				<p><?= e((string)($item['description'])) ?></p>
				<?php if ($item['meta']): ?>
				<div class="hlm-uni-meta">
					<?php foreach ($item['meta'] as $m): ?>
					<span><?= e((string)($m)) ?></span>
					<?php endforeach; ?>
				</div>
				<?php endif; ?>
			</div>
			<?php endforeach; ?>
		</div>
		<div class="hlm-section__actions">
			<a class="hlm-btn hlm-btn-outline hlm-btn-arrow" href="/affiliations/">View All Universities</a>
		</div>
	</div>
</section>
