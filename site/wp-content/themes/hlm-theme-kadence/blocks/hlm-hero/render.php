<?php
/**
 * Hero block render template.
 *
 * @package HLM_Kadence
 *
 * @var array    $attributes Block attributes.
 * @var string   $content    Inner blocks content.
 * @var WP_Block $block      Block instance.
 */

$variant            = hlm_block_attr( $attributes, 'variant', 'light' );
$overline           = hlm_block_attr( $attributes, 'overline', 'Education Lead Generation' );
$headline           = hlm_block_attr( $attributes, 'headline', 'Connect With Your Future Students' );
$description        = hlm_block_attr( $attributes, 'description', '' );
$primary_cta_text   = hlm_block_attr( $attributes, 'primaryCtaText', 'Get Started' );
$primary_cta_url    = hlm_block_attr( $attributes, 'primaryCtaUrl', '#form' );
$secondary_cta_text = hlm_block_attr( $attributes, 'secondaryCtaText', 'Our Services' );
$secondary_cta_url  = hlm_block_attr( $attributes, 'secondaryCtaUrl', '/services/' );
$trust_highlight    = hlm_block_attr( $attributes, 'trustHighlight', '10,000+' );
$trust_text         = hlm_block_attr( $attributes, 'trustText', 'students connected with programs' );
$avatar_urls        = hlm_block_attr( $attributes, 'avatarUrls', array() );
$show_lead_form     = hlm_block_attr( $attributes, 'showLeadForm', true );

$media_args = hlm_media_args_from_block( $attributes );
$has_media_bg = false;

if ( in_array( $media_args['type'], array( 'image', 'video' ), true ) ) {
	if ( 'image' === $media_args['type'] && hlm_media_image_url( $media_args ) ) {
		$has_media_bg = true;
	} elseif ( 'video' === $media_args['type'] && hlm_resolve_media_url( $media_args['video_url'] ) ) {
		$has_media_bg = true;
	}
}

$has_content = $overline || $headline || $description || $primary_cta_text || $secondary_cta_text || $trust_highlight || $trust_text;

$section_class = 'hlm-hero';
if ( 'landing' === $variant ) {
	$section_class .= ' hlm-hero--landing';
} else {
	$section_class .= ' hlm-hero--light';
}

if ( $has_media_bg ) {
	$section_class .= ' hlm-hero--has-media';
	if ( ! empty( $media_args['show_overlay'] ) ) {
		$section_class .= ' hlm-hero--has-overlay';
	}
}

$inner_class = 'hlm-hero-inner';
if ( ! $show_lead_form ) {
	$inner_class .= ' hlm-hero-inner--content-only';
} elseif ( ! $has_content ) {
	$inner_class .= ' hlm-hero-inner--form-only';
}
?>

<section id="get-started" class="<?php echo esc_attr( $section_class ); ?>">
	<?php
	hlm_render_section_media(
		array_merge(
			$media_args,
			array(
				'wrapper_class' => 'hlm-hero-media',
				'inner_class'   => 'hlm-hero-media__placeholder',
			)
		)
	);
	?>
	<div class="hlm-hero-bg"></div>
	<div class="hlm-hero-grid"></div>
	<div class="<?php echo esc_attr( $inner_class ); ?>">
		<?php if ( $has_content ) : ?>
		<div class="hlm-hero-content hlm-animate">
			<?php if ( $overline ) : ?>
				<div class="hlm-overline"><?php echo esc_html( $overline ); ?></div>
			<?php endif; ?>
			<?php if ( $headline ) : ?>
				<?php hlm_block_headline( $headline, 'hlm-h1', 'h1' ); ?>
			<?php endif; ?>
			<?php if ( $description ) : ?>
				<p class="hlm-hero-desc"><?php echo esc_html( $description ); ?></p>
			<?php endif; ?>
			<?php if ( $primary_cta_text || $secondary_cta_text ) : ?>
			<div class="hlm-hero-btns">
				<?php if ( $primary_cta_text ) : ?>
				<a href="<?php echo esc_url( $primary_cta_url ); ?>" class="hlm-btn hlm-btn-primary hlm-btn-arrow"><?php echo esc_html( $primary_cta_text ); ?></a>
				<?php endif; ?>
				<?php if ( $secondary_cta_text ) : ?>
				<a href="<?php echo esc_url( $secondary_cta_url ); ?>" class="hlm-btn hlm-btn-outline"><?php echo esc_html( $secondary_cta_text ); ?></a>
				<?php endif; ?>
			</div>
			<?php endif; ?>
			<?php if ( $trust_highlight || $trust_text ) : ?>
			<div class="hlm-hero-trust">
				<?php if ( ! empty( $avatar_urls ) ) : ?>
					<div class="hlm-hero-avatars">
						<?php foreach ( $avatar_urls as $index => $avatar_url ) : ?>
							<?php if ( $avatar_url ) : ?>
								<img src="<?php echo esc_url( $avatar_url ); ?>" alt="" width="40" height="40" loading="lazy">
							<?php endif; ?>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
				<p class="hlm-hero-trust-text">
					<?php if ( $trust_highlight ) : ?>
						<strong><?php echo esc_html( $trust_highlight ); ?></strong>
					<?php endif; ?>
					<?php echo esc_html( $trust_text ); ?>
				</p>
			</div>
			<?php endif; ?>
		</div>
		<?php endif; ?>

		<?php if ( $show_lead_form ) : ?>
			<div id="form" class="hlm-form-card hlm-animate hlm-animate-delay-2">
				<?php if ( shortcode_exists( 'hlm_lead_form' ) ) : ?>
					<?php echo do_shortcode( '[hlm_lead_form]' ); ?>
				<?php else : ?>
					<p class="hlm-form-placeholder"><?php esc_html_e( 'Activate the HLM Lead Capture plugin to display the lead form.', 'hlm-kadence' ); ?></p>
				<?php endif; ?>
			</div>
		<?php endif; ?>
	</div>
</section>
