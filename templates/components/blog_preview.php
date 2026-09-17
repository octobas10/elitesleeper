<?php
$posts = is_array($posts ?? null) ? $posts : (is_array($blog_posts ?? null) ? $blog_posts : []);
$posts = array_slice($posts, 0, 3);
?>
<section class="hlm-blog hlm-section">
	<div class="hlm-container">
		<div class="hlm-section-header">
			<div class="hlm-overline"><?= e((string) ($overline ?? 'From the Blog')) ?></div>
			<h2 class="hlm-h2"><?= e((string) ($headline ?? 'Insights for Students & Institutions')) ?></h2>
		</div>
		<div class="hlm-grid-3">
			<?php foreach ($posts as $post): ?>
			<article class="hlm-blog-card">
				<div class="hlm-blog-card__body">
					<time class="hlm-blog-card__date" datetime="<?= e((string) ($post['date'] ?? '')) ?>"><?= e((string) ($post['date'] ?? '')) ?></time>
					<h3 class="hlm-h4"><a href="/blog/<?= e((string) ($post['slug'] ?? '')) ?>/"><?= e((string) ($post['title'] ?? '')) ?></a></h3>
					<p class="hlm-blog-card__excerpt"><?= e((string) ($post['excerpt'] ?? '')) ?></p>
					<a class="hlm-blog-card__link" href="/blog/<?= e((string) ($post['slug'] ?? '')) ?>/">Read article</a>
				</div>
			</article>
			<?php endforeach; ?>
		</div>
		<div class="hlm-section__actions"><a class="hlm-btn hlm-btn-outline hlm-btn-arrow" href="/blog/">View All Articles</a></div>
	</div>
</section>
