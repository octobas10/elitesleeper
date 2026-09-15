<?php
$is_university = (($form_type ?? '') === 'university');
$headline = $is_university ? 'Thanks - your partnership inquiry is in' : 'Thanks - we received your inquiry';
$support_copy = $is_university ? 'A partnership specialist will review your request and follow up within one business day.' : 'An advisor will review your details and follow up within one business day with clear next steps.';
$block_scripts = '<script>(function(){var params=new URLSearchParams(window.location.search);var type=params.get("type")||"unknown";window.dataLayer=window.dataLayer||[];window.dataLayer.push({event:"hlm_lead_thank_you",form_type:type});})();</script>';
?>
<section class="hlm-page-hero hlm-thank-you">
	<div class="hlm-page-hero__bg" aria-hidden="true"></div>
	<div class="hlm-page-hero__grid" aria-hidden="true"></div>
	<div class="hlm-container hlm-page-hero__inner"><div class="hlm-overline">Confirmation</div><h1 class="hlm-h1"><?= e($headline) ?></h1><p class="hlm-body-lg"><?= e($support_copy) ?></p><div class="hlm-page-hero__cta hlm-thank-you__actions"><a class="hlm-btn hlm-btn-gold" href="/">Back to Home</a><a class="hlm-btn hlm-btn-outline hlm-thank-you__outline" href="/services/">View Services</a></div></div>
</section>
<section class="hlm-section hlm-bg-cream"><div class="hlm-container"><div class="hlm-section-header"><div class="hlm-overline">While You Wait</div><h2 class="hlm-h2">Helpful next steps</h2><p class="hlm-body-lg">Explore our work, or reach us directly if you need to update your submission.</p></div><ul class="hlm-thank-you__links" role="list"><li><a class="hlm-thank-you__link" href="/about-us/">About Us</a></li><li><a class="hlm-thank-you__link" href="/blog/">Read the Blog</a></li><li><a class="hlm-thank-you__link" href="/affiliations/">Affiliations</a></li><li><a class="hlm-thank-you__link" href="<?= e((string) ($contact['phoneHref'] ?? 'tel:+10000000000')) ?>">Call <?= e((string) ($contact['phoneDisplay'] ?? '')) ?></a></li></ul></div></section>
