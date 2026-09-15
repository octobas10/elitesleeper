<?php $image_alt = $image_alt ?? ''; ?>
<section class="hlm-page-hero">
	<?php if (!empty($image_url)): ?>
	<div class="hlm-page-hero__media hlm-media-wrap--overlay" aria-hidden="true">
		<div class="hlm-page-hero__media-placeholder hlm-page-hero__media-placeholder--image">
			<img class="hlm-media-image" src="<?= e((string) $image_url) ?>" alt="<?= e((string) $image_alt) ?>" loading="eager" decoding="async" />
		</div>
	</div>
	<?php endif; ?>
	<div class="hlm-page-hero__bg" aria-hidden="true"></div>
	<div class="hlm-page-hero__grid" aria-hidden="true"></div>
	<div class="hlm-container hlm-page-hero__inner">
		<?php if (!empty($overline)): ?><div class="hlm-overline"><?= e((string) $overline) ?></div><?php endif; ?>
		<h1 class="hlm-h1"><?= e((string) ($headline ?? '')) ?></h1>
		<?php if (!empty($description)): ?><p class="hlm-body-lg"><?= e((string) $description) ?></p><?php endif; ?>
		<?php if (!empty($primary_cta_text) && !empty($primary_cta_url)): ?>
		<div class="hlm-page-hero__cta"><a class="hlm-btn hlm-btn-gold hlm-btn-arrow" href="<?= e((string) $primary_cta_url) ?>"><?= e((string) $primary_cta_text) ?></a></div>
		<?php endif; ?>
	</div>
</section>
