<?php
if (!empty($institution_data)) {
    $block_scripts = '<script>window.HLM_INSTITUTION_DATA = ' . json_encode($institution_data, JSON_UNESCAPED_SLASHES) . ';</script>';
}
$image_url = $image_url ?? ($media['hero_home_poster'] ?? '');
$headline = $headline ?? 'Connecting Universities With Motivated USA College Bound Students';
$description = $description ?? 'Higher Learning Marketers is a U.S.A focused education lead generation platform,  helping universities fill nursing, business, technical, engineering, medical and other career programs while guiding students to accredited institutions that fit their educational goals.';
$image_alt = $image_alt ?? 'Students collaborating on a university campus';
$trust_highlight = $trust_highlight ?? '100,000+';
$trust_text = $trust_text ?? 'students connected with programs';
partial('components/hero_with_form', compact('image_url', 'headline', 'description', 'image_alt', 'trust_highlight', 'trust_text', 'institutions', 'state_options', 'grad_years', 'tcpa_text', 'turnstile_site_key'));
if (!empty($seo_intro)):
?>
<section class="hlm-seo-intro hlm-seo-intro--compact hlm-section hlm-bg-cream" aria-label="Overview">
	<div class="hlm-container"><div class="hlm-seo-intro__card"><div class="hlm-seo-intro__body hlm-body-lg"><?= $seo_intro ?></div></div></div>
</section>
<?php
endif;
$items = $institutions_list ?? [];
partial('components/universities', compact('items'));
partial('components/programs', ['programs' => $programs ?? []]);
$steps = $process_steps ?? [];
$footer_cta_text = 'Get Started Now';
$footer_cta_url = '#form';
$overline = 'How It Works';
$headline = 'Three Simple Steps';
$description = 'Whether you are a student or institution, getting started takes just minutes.';
partial('components/process', compact('steps', 'footer_cta_text', 'footer_cta_url', 'overline', 'headline', 'description'));
partial('components/testimonials', ['testimonials' => $testimonials ?? []]);
partial('components/blog_preview', ['posts' => $blog_posts ?? []]);
partial('components/faq_section', ['faqs' => $faqs ?? []]);
$variant = 'soft';
$primary_text = 'Join Now';
$primary_url = '#form';
$secondary_text = 'See All Programs';
$secondary_url = '/services/';
$description = 'Whether you are a student looking for the right program or an institution seeking qualified leads, we are here to help you succeed.';
$overline = 'Ready to Connect?';
$headline = 'Start Your Journey Today';
partial('components/cta_banner', compact('variant', 'primary_text', 'primary_url', 'secondary_text', 'secondary_url', 'description', 'overline', 'headline'));
