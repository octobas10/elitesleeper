<?php $overline = $overline ?? ($overline ?? 'Education Lead Generation'); ?>
<?php $image_alt = $image_alt ?? ($image_alt ?? 'University campus'); ?>
<?php $primary_cta_text = $primary_cta_text ?? ($primary_cta_text ?? 'Get Started'); ?>
<?php $primary_cta_url = $primary_cta_url ?? ($primary_cta_url ?? '#form'); ?>
<?php $secondary_cta_text = $secondary_cta_text ?? ($secondary_cta_text ?? 'University Services'); ?>
<?php $secondary_cta_url = $secondary_cta_url ?? ($secondary_cta_url ?? '/services/'); ?>
<?php $trust_highlight = $trust_highlight ?? ($trust_highlight ?? '100,000+'); ?>
<?php $trust_text = $trust_text ?? ($trust_text ?? 'students connected with programs'); ?>
<?php $variant = $variant ?? 'light'; ?>
<?php $hero_class = ($variant === 'landing') ? 'hlm-hero hlm-hero--landing hlm-hero--has-media hlm-hero--has-overlay' : 'hlm-hero hlm-hero--light hlm-hero--has-media hlm-hero--has-overlay'; ?>
<section id="get-started" class="<?= e((string)($hero_class)) ?>">
	<div class="hlm-hero-media">
		<img class="hlm-media-image" src="<?= e((string)($image_url)) ?>" alt="<?= e((string)($image_alt)) ?>" loading="eager" />
		<div class="hlm-media-scrim hlm-media-scrim--medium" aria-hidden="true"></div>
	</div>
	<div class="hlm-hero-bg"></div>
	<div class="hlm-hero-grid"></div>
	<div class="hlm-hero-inner">
		<div class="hlm-hero-content hlm-animate is-visible">
			<?php if ($overline): ?><div class="hlm-overline"><?= e((string)($overline)) ?></div><?php endif; ?>
			<h1 class="hlm-h1"><?= e((string)($headline)) ?></h1>
			<?php if ($description): ?><p class="hlm-hero-desc"><?= e((string)($description)) ?></p><?php endif; ?>
			<div class="hlm-hero-btns">
				<a class="hlm-btn hlm-btn-primary hlm-btn-arrow" href="<?= e((string)($primary_cta_url)) ?>"><?= e((string)($primary_cta_text)) ?></a>
				<a class="hlm-btn hlm-btn-outline" href="<?= e((string)($secondary_cta_url)) ?>"><?= e((string)($secondary_cta_text)) ?></a>
			</div>
			<div class="hlm-hero-trust">
				<p class="hlm-hero-trust-text">
					<strong><?= e((string)($trust_highlight)) ?></strong>
					<?= e((string)($trust_text)) ?>
				</p>
			</div>
		</div>
		<div id="form" class="hlm-form-card hlm-animate is-visible hlm-animate-delay-2">
			<?php partial('components/lead_form', get_defined_vars()); ?>
		</div>
	</div>
</section>
