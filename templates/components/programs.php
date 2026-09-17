<?php $programs = $programs ?? ($programs ?? []); ?>
<?php $anchor = $anchor ?? ($anchor ?? 'programs'); ?>
<section id="<?= e((string)($anchor)) ?>" class="hlm-programs hlm-section">
	<div class="hlm-container">
		<div class="hlm-section-header">
			<div class="hlm-overline"><?= e((string)($overline ?? 'Popular Programs')) ?></div>
			<h2 class="hlm-h2"><?= e((string)($headline ?? 'Programs Students Are Searching For')) ?></h2>
			<p class="hlm-body-lg">
				<?= e((string)($description ?? 'From nursing to cybersecurity, business to criminal justice - find the program that matches your career goals.')) ?>
			</p>
		</div>
		<div class="hlm-programs-grid">
			<?php foreach ($programs as $name): ?>
			<div class="hlm-program-tag">
				<span><?= e((string)($name)) ?></span>
			</div>
			<?php endforeach; ?>
		</div>
		<div class="hlm-section__actions">
			<a class="hlm-btn hlm-btn-outline hlm-btn-arrow" href="/contact-us/#form">View All Programs</a>
		</div>
	</div>
</section>
