<?php
$overline = 'Who We Are';
$headline = $headline ?? 'Education Marketing Company for U.S. Institutions';
$description = $description ?? 'For over a decade, Higher Learning Marketers has connected motivated students with accredited universities and colleges through consent-based, TCPA-compliant lead generation.';
$image_url = $image_url ?? ($media['page_about'] ?? '');
$image_alt = $image_alt ?? 'HLM team collaborating on education marketing strategy';
partial('components/page_hero', compact('overline', 'headline', 'description', 'image_url', 'image_alt'));
if (!empty($seo_intro)):
?>
<section class="hlm-seo-intro hlm-seo-intro--compact hlm-section hlm-bg-cream" aria-label="Overview">
	<div class="hlm-container"><div class="hlm-seo-intro__card"><div class="hlm-seo-intro__body hlm-body-lg"><?= $seo_intro ?></div></div></div>
</section>
<?php endif; ?>

<section class="hlm-partner-showcase hlm-partner-showcase--story hlm-section">
	<div class="hlm-container">
		<div class="hlm-partner-showcase__grid hlm-partner-showcase__grid--image-left">
			<div class="hlm-partner-showcase__visual">
				<img class="hlm-partner-showcase__image" src="<?= e((string) ($media['partner_story'] ?? '')) ?>" alt="Higher Learning Marketers team strategy session" loading="lazy" />
				<div class="hlm-partner-showcase__badge">100,000+ Students Connected</div>
			</div>
			<div class="hlm-partner-showcase__content">
				<div class="hlm-overline">Who We Are</div>
				<h2 class="hlm-h2">A Decade of Proven Results</h2>
				<div class="hlm-partner-showcase__copy hlm-body-lg">
					<p>Higher Learning Marketers was founded with a simple mission to eliminate the friction between students searching for the right educational path and the institutions ready to welcome them.</p>
					<p>What began as a small lead generation service has grown into a comprehensive education marketing platform. We now operate across the entire U.S.A partnering with many colleges and universities, and have sourced over 100,000 qualified college bound students for our partner institutions.</p>
					<p>Our team combines deep expertise in higher education marketing, data science, and student engagement to deliver results that matter - not just clicks, but enrollments.</p>
				</div>
			</div>
		</div>
		<?php if (!empty($about_stats) && is_array($about_stats)): ?>
		<div class="hlm-partner-showcase__stats hlm-partner-showcase__stats--row hlm-partner-showcase__stats--full">
			<?php foreach ($about_stats as $s): ?>
			<div class="hlm-partner-showcase__stat">
				<div class="hlm-partner-showcase__stat-number"><?= e((string) ($s['number'] ?? '')) ?></div>
				<div class="hlm-partner-showcase__stat-label"><?= e((string) ($s['label'] ?? '')) ?></div>
			</div>
			<?php endforeach; ?>
		</div>
		<?php endif; ?>
	</div>
</section>

<?php if (!empty($about_values) && is_array($about_values)): ?>
<section class="hlm-why-us hlm-why-us--values hlm-section">
	<div class="hlm-container">
		<div class="hlm-section-header"><div class="hlm-overline">Who We Are</div><h2 class="hlm-h2">Our Core Values</h2><p class="hlm-body-lg">Every decision we make is guided by principles that put students and partners first.</p></div>
		<div class="hlm-why-us__values-grid">
			<?php foreach ($about_values as $p): ?>
			<div class="hlm-value-card"><h3 class="hlm-h3"><?= e((string) ($p['title'] ?? '')) ?></h3><p class="hlm-body"><?= e((string) ($p['description'] ?? '')) ?></p></div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
<?php endif; ?>

<?php if (!empty($timeline) && is_array($timeline)): ?>
<section class="hlm-timeline hlm-timeline--light hlm-section">
	<div class="hlm-container">
		<div class="hlm-section-header"><div class="hlm-overline">Our Journey</div><h2 class="hlm-h2">Key Milestones Through the Years</h2></div>
		<div class="hlm-timeline__inner">
			<div class="hlm-timeline__line" aria-hidden="true"></div>
			<?php foreach ($timeline as $i => $m): ?>
			<div class="hlm-timeline__item">
				<div class="hlm-timeline__dot" aria-hidden="true"></div>
				<?php if (($i % 2) === 1): ?>
				<div class="hlm-timeline__content"><h3 class="hlm-h4"><?= e((string) ($m['title'] ?? '')) ?></h3><p class="hlm-body"><?= e((string) ($m['description'] ?? '')) ?></p></div>
				<div class="hlm-timeline__year"><?= e((string) ($m['year'] ?? '')) ?></div>
				<?php else: ?>
				<div class="hlm-timeline__year"><?= e((string) ($m['year'] ?? '')) ?></div>
				<div class="hlm-timeline__content"><h3 class="hlm-h4"><?= e((string) ($m['title'] ?? '')) ?></h3><p class="hlm-body"><?= e((string) ($m['description'] ?? '')) ?></p></div>
				<?php endif; ?>
			</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
<?php endif; ?>

<?php
partial('components/faq_section', ['faqs' => $faqs ?? []]);
$variant = 'soft';
$overline = 'Work With Us';
$headline = 'Ready to Partner With HLM?';
$description = 'Whether you represent a university, a community organization, or a student seeking guidance - we are here to help.';
$primary_text = 'Contact Us';
$primary_url = '/contact-us/#form';
$secondary_text = 'Get Started';
$secondary_url = '/contact-us/#form';
partial('components/cta_banner', compact('variant', 'overline', 'headline', 'description', 'primary_text', 'primary_url', 'secondary_text', 'secondary_url'));
