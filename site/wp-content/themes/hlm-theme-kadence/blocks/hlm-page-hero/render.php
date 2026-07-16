<?php
/**
 * Page hero block render template.
 *
 * @package HLM_Kadence
 *
 * @var array    $attributes Block attributes.
 * @var string   $content    Inner blocks content.
 * @var WP_Block $block      Block instance.
 */

$overline    = hlm_block_attr( $attributes, 'overline', 'Our Network' );
$headline    = hlm_block_attr( $attributes, 'headline', 'Strategic Partnerships' );
$description = hlm_block_attr( $attributes, 'description', '' );
$primary_cta_text = hlm_block_attr( $attributes, 'primaryCtaText', '' );
$primary_cta_url  = hlm_block_attr( $attributes, 'primaryCtaUrl', '/contact-us/#form' );
$block_id    = hlm_block_id( 'hlm-page-hero', $block );

hlm_render_page_hero(
	array(
		'id'                      => $block_id,
		'overline'                => $overline,
		'headline'                => $headline,
		'description'             => $description,
		'primary_cta_text'        => $primary_cta_text,
		'primary_cta_url'         => $primary_cta_url,
		'media_type'              => hlm_block_attr( $attributes, 'mediaType', 'image' ),
		'media_image_url'         => hlm_block_attr( $attributes, 'mediaImageUrl', '' ),
		'media_image_id'          => (int) hlm_block_attr( $attributes, 'mediaImageId', 0 ),
		'media_image_alt'         => hlm_block_attr( $attributes, 'mediaImageAlt', '' ),
		'media_video_url'         => hlm_block_attr( $attributes, 'mediaVideoUrl', '' ),
		'media_poster_url'        => hlm_block_attr( $attributes, 'mediaPosterUrl', '' ),
		'media_placeholder_label' => hlm_block_attr( $attributes, 'mediaPlaceholderLabel', HLM_HERO_VIDEO_PLACEHOLDER_LABEL ),
		'media_show_overlay'      => (bool) hlm_block_attr( $attributes, 'mediaShowOverlay', true ),
		'media_overlay_strength'  => hlm_media_overlay_strength( hlm_block_attr( $attributes, 'mediaOverlayStrength', 'medium' ) ),
	)
);
