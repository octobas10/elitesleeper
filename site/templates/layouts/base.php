<!doctype html>
<html lang="en" data-theme="light">
<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<link rel="icon" href="<?= e(asset('favicon.ico')) ?>" sizes="32x32" />
	<link rel="icon" type="image/svg+xml" href="<?= e(asset('favicon.svg')) ?>" />
	<link rel="icon" type="image/png" sizes="192x192" href="<?= e(asset('favicon/site-icon-192.png')) ?>" />
	<link rel="apple-touch-icon" sizes="512x512" href="<?= e(asset('favicon/site-icon-512.png')) ?>" />
	<meta name="theme-color" content="#001520" />
	<link rel="preconnect" href="https://fonts.googleapis.com" />
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
	<link
		href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700&family=Inter:wght@400;500;600;700&family=Playfair+Display:ital,wght@0,400;0,500;1,400;1,500&display=swap"
		rel="stylesheet"
	/>
	<?php $_title = $page_title ?? $title ?? $site['name']; ?>
	<?php $_desc = $page_description ?? $description ?? $site['description']; ?>
	<?php $display_title = str_contains((string) $_title, (string) $site['name']) ? $_title : $_title . ' | ' . $site['name']; ?>
	<title><?= e((string)($display_title)) ?></title>
	<meta name="description" content="<?= e((string)($_desc)) ?>" />
	<?php if (!empty($noindex)): ?>
	<meta name="robots" content="noindex, nofollow" />
	<?php endif; ?>
	<meta property="og:title" content="<?= e((string)($_title)) ?>" />
	<meta property="og:description" content="<?= e((string)($_desc)) ?>" />
	<meta property="og:type" content="<?= e((string)($og_type ?? 'website')) ?>" />
	<meta property="og:url" content="<?= e((string)($site['url'] ?? '')) ?><?= e((string)($path ?? '/')) ?>" />
	<?php if (!empty($og_image)): ?>
	<meta property="og:image" content="<?= e((string)($og_image)) ?>" />
	<?php else: ?>
	<meta property="og:image" content="<?= e((string)($site['url'] ?? '')) ?><?= e(asset('favicon/site-icon-512.png')) ?>" />
	<?php endif; ?>
	<meta name="twitter:card" content="summary_large_image" />
	<link rel="stylesheet" href="<?= e(asset('css/hlm-tokens.css')) ?>" />
	<link rel="stylesheet" href="<?= e(asset('css/hlm-blocks.css')) ?>" />
	<link rel="stylesheet" href="<?= e(asset('css/hlm-blocks-theme.css')) ?>" />
	<link rel="stylesheet" href="<?= e(asset('css/hlm-motion.css')) ?>" />
	<link rel="stylesheet" href="<?= e(asset('css/hlm-theme-ui.css')) ?>" />
	<link rel="stylesheet" href="<?= e(asset('css/hlm-kadence.css')) ?>" />
	<link rel="stylesheet" href="<?= e(asset('css/hlm-header.css')) ?>" />
	<link rel="stylesheet" href="<?= e(asset('css/hlm-page-polish.css')) ?>" />
	<link rel="stylesheet" href="<?= e(asset('css/hlm-blog.css')) ?>" />
	<link rel="stylesheet" href="<?= e(asset('css/hlm-surfaces.css')) ?>" />
	<link rel="stylesheet" href="<?= e(asset('css/hlm-lead-form.css')) ?>" />
	<link rel="stylesheet" href="<?= e(asset('css/hlm-services.css')) ?>" />
	<style>
		html { scroll-behavior: smooth; }
		body {
			margin: 0;
			font-family: var(--hlm-font-body);
			background: var(--hlm-color-bg);
			color: var(--hlm-color-text);
		}
		.hlm-breadcrumbs { padding: 1rem 0 0; }
		.hlm-breadcrumbs ol {
			display: flex;
			flex-wrap: wrap;
			gap: 0.35rem 0.5rem;
			list-style: none;
			margin: 0;
			padding: 0 var(--hlm-container-pad, 1.5rem);
			max-width: var(--hlm-container-max, 1200px);
			margin-inline: auto;
			font-size: 0.875rem;
		}
		.hlm-breadcrumbs li:not(:last-child)::after {
			content: '/';
			margin-left: 0.5rem;
			opacity: 0.5;
		}
		.hlm-related-services {
			display: grid;
			gap: 0.75rem;
			list-style: none;
			padding: 0;
			margin: 1rem 0 0;
		}
		.hlm-article__body h2 { margin-top: 2rem; }
		.hlm-faq__list { display: grid; gap: 0.75rem; margin-top: 1.5rem; }
		.hlm-faq__item {
			border: 1px solid var(--hlm-color-border, #e5e7eb);
			border-radius: 8px;
			padding: 1rem 1.25rem;
			background: #fff;
		}
		.hlm-faq__item summary { cursor: pointer; font-weight: 600; }
		body.home.transparent-header #masthead.hlm-site-header--transparent,
		body.hlm-landing-page.transparent-header #masthead.hlm-site-header--transparent {
			position: absolute !important;
			left: 0 !important;
			right: 0 !important;
		}
		body.home.transparent-header #masthead.hlm-site-header--transparent.hlm-header-scrolled,
		body.hlm-landing-page.transparent-header #masthead.hlm-site-header--transparent.hlm-header-scrolled {
			position: sticky !important;
		}
		body.transparent-header .hlm-hero--has-media .hlm-hero-inner {
			padding-top: calc(var(--hlm-header-height) + var(--hlm-hero-content-offset));
		}
		.hlm-hp {
			position: absolute;
			left: -9999px;
			opacity: 0;
			height: 0;
			width: 0;
		}
		.hlm-form-status { margin-top: 1rem; }
		.hlm-form-status.is-error { color: var(--hlm-error); }
		.hlm-form-status.is-success { color: var(--hlm-success); }
		.hlm-404__actions,
		.hlm-thank-you__actions {
			display: flex;
			flex-wrap: wrap;
			gap: 0.75rem;
			justify-content: center;
		}
		.hlm-404__outline,
		.hlm-thank-you__outline {
			color: var(--hlm-white);
			border-color: rgba(255, 255, 255, 0.45);
		}
		.hlm-404__outline:hover,
		.hlm-thank-you__outline:hover {
			color: var(--hlm-gold-400);
			border-color: var(--hlm-gold-400);
			background: transparent;
		}
		.hlm-404__links,
		.hlm-thank-you__links {
			list-style: none;
			margin: 0 auto;
			padding: 0;
			display: grid;
			grid-template-columns: repeat(auto-fit, minmax(10rem, 1fr));
			gap: 0.75rem;
			max-width: 48rem;
		}
		.hlm-404__link,
		.hlm-thank-you__link {
			display: block;
			padding: 1rem 1.25rem;
			text-align: center;
			text-decoration: none;
			font-weight: 600;
			color: var(--hlm-primary);
			background: var(--hlm-surface-raised);
			border: 1px solid var(--hlm-card-border);
			border-radius: 12px;
			transition: border-color 0.2s ease, color 0.2s ease, transform 0.2s ease;
		}
		.hlm-404__link:hover,
		.hlm-thank-you__link:hover {
			color: var(--hlm-text-accent);
			border-color: var(--hlm-gold-400);
			transform: translateY(-2px);
		}
	</style>
	<?= $head ?? '' ?>
</head>
<?php $body_classes = 'hlm-theme'; ?>
<?php if (!empty($transparent_header)): ?><?php $body_classes .= ' transparent-header'; ?><?php endif; ?>
<?php if (!empty($body_class)): ?><?php $body_classes .= ' ' . $body_class; ?><?php endif; ?>
<body class="<?= e(trim((string)($body_classes))) ?>">
	<?php partial('components/header', get_defined_vars()); ?>
	<main id="main-content">
		<?= $content ?? '' ?>
	</main>
	<?php partial('components/footer', get_defined_vars()); ?>
	<script src="<?= e(asset('js/nav.js')) ?>" defer></script>
	<script src="<?= e(asset('js/site.js')) ?>" defer></script>
	<?= $scripts ?? '' ?>
	<script src="<?= e(asset('js/lead-form.js')) ?>" defer></script>
</body>
</html>
