<?php $variant = $variant ?? 'home'; ?>
<?php $overline = $overline ?? ($overline ?? 'How It Works'); ?>
<?php $headline = $headline ?? ($headline ?? 'Three Simple Steps'); ?>
<?php $steps = $steps ?? ($steps ?? []); ?>
<?php $step_count = count($steps) > 0 ? count($steps) : 1; ?>
<?php $section_class = 'hlm-process hlm-section'; ?>
<?php if ($variant === 'cream'): ?><?php $section_class = ($section_class) . ' hlm-process--cream hlm-bg-cream'; ?><?php endif; ?>
<?php if ($variant === 'strip'): ?><?php $section_class = ($section_class) . ' hlm-process--strip'; ?><?php endif; ?>

<section class="<?= e((string)($section_class)) ?>">
	<div class="hlm-container">
		<div class="hlm-section-header">
			<?php if ($overline): ?><div class="hlm-overline"><?= e((string)($overline)) ?></div><?php endif; ?>
			<h2 class="hlm-h2"><?= e((string)($headline)) ?></h2>
			<?php if ($description): ?><p class="hlm-body-lg"><?= e((string)($description)) ?></p><?php endif; ?>
		</div>

		<div class="hlm-process-row hlm-process-row--<?= e((string)($step_count)) ?>">
			<?php foreach ($steps as $step): ?>
			<div class="hlm-process-row__step">
				<div class="hlm-process-card">
					<?php if ($step['number']): ?>
					<div class="hlm-process-card__head">
						<div class="hlm-process-num" aria-hidden="true"><?= e((string)($step['number'])) ?></div>
					</div>
					<?php endif; ?>
					<h4><?= e((string)($step['title'])) ?></h4>
					<p><?= e((string)($step['description'])) ?></p>
					<?php if (!empty($step['ctaText']) && !empty($step['ctaUrl'])): ?>
					<a class="hlm-process-card__cta" href="<?= e((string)($step['ctaUrl'])) ?>"><?= e((string)($step['ctaText'])) ?></a>
					<?php endif; ?>
				</div>
			</div>
			<?php endforeach; ?>
		</div>

		<?php if (!empty($footer_cta_text) && !empty($footer_cta_url)): ?>
		<div class="hlm-process__footer">
			<a class="hlm-btn hlm-btn-primary" href="<?= e((string)($footer_cta_url)) ?>"><?= e((string)($footer_cta_text)) ?></a>
		</div>
		<?php endif; ?>
	</div>
</section>

<style>
	.hlm-process-row {
		display: flex;
		flex-direction: row;
		flex-wrap: nowrap;
		align-items: stretch;
		gap: 1.25rem;
		width: 100%;
	}

	.hlm-process-row__step {
		flex: 1 1 0;
		min-width: 0;
		display: flex;
	}

	.hlm-process-row__step .hlm-process-card {
		width: 100%;
		height: auto;
		min-height: 100%;
	}

	@media (max-width: 900px) {
		.hlm-process-row {
			flex-direction: column;
			gap: 1.25rem;
		}

		.hlm-process-row__step .hlm-process-card {
			min-height: 0;
		}
	}
</style>
