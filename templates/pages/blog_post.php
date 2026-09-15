<?php
$post = is_array($post ?? null) ? $post : [];
partial('components/page_hero', ['overline' => $cluster_label ?? 'Blog', 'headline' => (string) ($post['title'] ?? 'Article'), 'description' => 'Published ' . (string) ($post['date'] ?? '')]);
?>
<section class="hlm-single-post-wrap hlm-section hlm-bg-cream">
	<div class="hlm-container hlm-single-post-container">
		<div class="hlm-single-post-layout">
			<div class="hlm-single-post-main"><article class="entry content-bg single-entry"><div class="entry-content-wrap"><div class="hlm-single-post-meta"><nav class="hlm-breadcrumbs" aria-label="Breadcrumb"><ol><li><a href="/">Home</a></li><li><a href="/blog/">Blog</a></li><li><span><?= e((string) ($post['title'] ?? '')) ?></span></li></ol></nav></div><?php if (!empty($featured_image)): ?><figure class="hlm-single-post-featured"><img src="<?= e(asset((string) $featured_image)) ?>" alt="<?= e((string) ($featured_image_alt ?? ($post['title'] ?? ''))) ?>" width="1200" height="630" loading="eager" decoding="async" /></figure><?php endif; ?><div class="entry-content single-content"><?= (string) ($post['contentHtml'] ?? '') ?></div></div></article></div>
			<?php partial('components/single_sidebar', ['topics' => $topics ?? [], 'variant' => 'post']); ?>
		</div>
		<?php if (!empty($related_posts) && is_array($related_posts)): ?><section class="hlm-single-post-related"><div class="hlm-section-header"><div class="hlm-overline">Keep Reading</div><h2 class="hlm-h3">Related Articles</h2></div><div class="hlm-single-post-related__grid"><?php foreach ($related_posts as $item): ?><article class="hlm-blog-card"><div class="hlm-blog-card__body"><time class="hlm-blog-card__date" datetime="<?= e((string) ($item['date'] ?? '')) ?>"><?= e((string) ($item['date'] ?? '')) ?></time><h3 class="hlm-h4"><a href="/blog/<?= e((string) ($item['slug'] ?? '')) ?>/"><?= e((string) ($item['title'] ?? '')) ?></a></h3><p class="hlm-blog-card__excerpt"><?= e((string) ($item['excerpt'] ?? '')) ?></p><a class="hlm-blog-card__link" href="/blog/<?= e((string) ($item['slug'] ?? '')) ?>/">Read More</a></div></article><?php endforeach; ?></div></section><?php endif; ?>
	</div>
</section>
<?php partial('components/cta_banner', ['overline' => 'Ready to Connect?', 'headline' => 'Start Your Journey Today', 'description' => 'Whether you are a student looking for the right program or an institution seeking qualified leads, we are here to help you succeed.', 'primary_text' => $cta_primary_text ?? 'Join Now', 'primary_url' => $cta_primary_url ?? '/contact-us/#form', 'secondary_text' => 'See All Programs', 'secondary_url' => '/#programs']); ?>
