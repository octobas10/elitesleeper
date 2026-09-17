<?php
$overline = $overline ?? 'Ready to Connect?';
$headline = $headline ?? 'Start Your Journey Today';
$primary_text = $primary_text ?? 'Get Started';
$primary_url = $primary_url ?? '#form';
$secondary_text = $secondary_text ?? 'Learn More About Us';
$secondary_url = $secondary_url ?? '/about-us/';
$variant = $variant ?? 'soft';
$primary_btn_class = $variant === 'soft'
    ? 'hlm-btn hlm-btn-gold hlm-btn-arrow'
    : 'hlm-btn hlm-btn-primary hlm-btn-arrow';
?>
<section class="hlm-cta hlm-cta--<?= e((string) $variant) ?> hlm-section">
	<div class="hlm-container">
		<div class="hlm-cta-inner hlm-animate">
			<?php if (!empty($overline)): ?><div class="hlm-overline"><?= e((string) $overline) ?></div><?php endif; ?>
			<?php if (!empty($headline)): ?><h2 class="hlm-h2"><?= e((string) $headline) ?></h2><?php endif; ?>
			<?php if (!empty($description)): ?><p class="hlm-body-lg"><?= e((string) $description) ?></p><?php endif; ?>
			<div class="hlm-cta-btns">
				<a class="<?= e($primary_btn_class) ?>" href="<?= e((string) $primary_url) ?>"><?= e((string) $primary_text) ?></a>
				<?php if (!empty($secondary_text) && !empty($secondary_url)): ?>
				<a class="hlm-btn hlm-btn-outline" href="<?= e((string) $secondary_url) ?>"><?= e((string) $secondary_text) ?></a>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>
