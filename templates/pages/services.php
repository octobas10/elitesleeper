<?php
partial('components/page_hero', [
    'overline' => 'What We Offer',
    'headline' => $headline ?? 'Education Lead Generation Services',
    'description' => $description ?? 'TCPA-compliant, consent-based student inquiries for universities and colleges, from nursing and business programs to adult-learner enrollment campaigns.',
    'image_url' => $image_url ?? ($media['page_services'] ?? ''),
    'image_alt' => $image_alt ?? 'Enrollment analytics dashboard for higher education campaigns',
    'primary_cta_text' => 'Schedule a Consultation',
    'primary_cta_url' => '/contact-us/#form',
]);
if (!empty($stats)) {
    partial('components/stats_bar', ['stats' => $stats]);
}
if (!empty($seo_intro)):
?>
<section class="hlm-seo-intro hlm-seo-intro--compact hlm-section hlm-bg-cream" aria-label="Overview"><div class="hlm-container"><div class="hlm-seo-intro__card"><div class="hlm-seo-intro__body hlm-body-lg"><?= $seo_intro ?></div></div></div></section>
<?php endif; ?>
<?php partial('components/services_grid', ['service_cards' => $service_cards ?? []]); ?>
<?php partial('components/process', ['steps' => $process_steps ?? [], 'variant' => 'cream', 'overline' => 'How We Work', 'headline' => 'Our 4-Step Process', 'description' => 'A transparent process designed to deliver measurable results from day one.', 'footer_cta_text' => 'Schedule a Consultation', 'footer_cta_url' => '/contact-us/#form']); ?>
<?php if (!empty($network_stats)): ?><?php partial('components/stats_bar', ['stats' => $network_stats, 'variant' => 'network', 'overline' => 'Proven Impact', 'headline' => 'Results That Speak', 'description' => 'Real outcomes from real partnerships across our network.']); ?><?php endif; ?>
<?php partial('components/faq_section', ['faqs' => $faqs ?? []]); ?>
<?php partial('components/cta_banner', ['variant' => 'soft', 'overline' => 'Get Started', 'headline' => 'Start Your Campaign Today', 'description' => 'Talk to our team about which services fit your enrollment goals. No obligation, just honest advice.', 'primary_text' => 'Begin a Project', 'primary_url' => '/contact-us/#form', 'secondary_text' => 'View Results', 'secondary_url' => '/affiliations/']); ?>
