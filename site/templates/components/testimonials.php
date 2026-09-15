<?php $testimonials = is_array($testimonials ?? null) ? $testimonials : []; ?>
<section id="testimonials" class="hlm-testimonials hlm-section">
	<div class="hlm-section-header">
		<div class="hlm-overline"><?= e((string) ($overline ?? 'Success Stories')) ?></div>
		<h2 class="hlm-h2"><?= e((string) ($headline ?? 'What Our Partners Say')) ?></h2>
		<p class="hlm-body-lg"><?= e((string) ($description ?? 'Institutions and students trust us to deliver results.')) ?></p>
	</div>
	<div class="hlm-testimonials-grid">
		<?php foreach ($testimonials as $t): ?>
		<div class="hlm-testimonial-card">
			<p class="hlm-testimonial-quote"><?= e((string) ($t['quote'] ?? '')) ?></p>
			<div class="hlm-testimonial-author">
				<?php if (!empty($t['photoUrl'])): ?>
				<img src="<?= e((string) $t['photoUrl']) ?>" alt="<?= e((string) ($t['author'] ?? '')) ?>" width="48" height="48" loading="lazy" />
				<?php endif; ?>
				<div>
					<h5><?= e((string) ($t['author'] ?? '')) ?></h5>
					<?php if (!empty($t['meta'])): ?><span><?= e((string) $t['meta']) ?></span><?php endif; ?>
				</div>
			</div>
		</div>
		<?php endforeach; ?>
	</div>
</section>
