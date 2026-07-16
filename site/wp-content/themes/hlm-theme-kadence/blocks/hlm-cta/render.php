<?php
/**
 * CTA block render template.
 *
 * @package HLM_Kadence
 *
 * @var array    $attributes Block attributes.
 * @var string   $content    Inner blocks content.
 * @var WP_Block $block      Block instance.
 */

$overline       = hlm_block_attr( $attributes, 'overline', 'Ready to Connect?' );
$headline       = hlm_block_attr( $attributes, 'headline', 'Start Your Journey Today' );
$description    = hlm_block_attr( $attributes, 'description', '' );
$primary_text   = hlm_block_attr( $attributes, 'primaryText', 'Get Started' );
$primary_url    = hlm_block_attr( $attributes, 'primaryUrl', '#form' );
$secondary_text = hlm_block_attr( $attributes, 'secondaryText', 'Learn More About Us' );
$secondary_url  = hlm_block_attr( $attributes, 'secondaryUrl', '/about-us/' );
$variant        = hlm_block_attr( $attributes, 'variant', 'brand' );
$block_id       = hlm_block_id( 'hlm-cta', $block );

hlm_render_cta_band(
	array(
		'id'             => $block_id,
		'overline'       => $overline,
		'headline'       => $headline,
		'description'    => $description,
		'primary_text'   => $primary_text,
		'primary_url'    => $primary_url,
		'secondary_text' => $secondary_text,
		'secondary_url'  => $secondary_url,
		'variant'        => $variant,
	)
);
