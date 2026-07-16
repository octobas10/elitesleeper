<?php
/**
 * ACF fields: CTA block.
 *
 * @package HLM_Theme
 */

acf_add_local_field_group(
	array(
		'key'    => 'group_hlm_cta',
		'title'  => 'CTA Banner',
		'fields' => array(
			array(
				'key'           => 'field_hlm_cta_overline',
				'label'         => 'Overline',
				'name'          => 'overline',
				'type'          => 'text',
				'default_value' => 'Ready to Connect?',
			),
			array(
				'key'           => 'field_hlm_cta_headline',
				'label'         => 'Headline',
				'name'          => 'headline',
				'type'          => 'textarea',
				'rows'          => 2,
				'instructions'  => 'Limited HTML: &lt;br&gt;, &lt;em&gt;.',
				'default_value' => "Start Your Journey<br><em>Today</em>",
			),
			array(
				'key'           => 'field_hlm_cta_description',
				'label'         => 'Description',
				'name'          => 'description',
				'type'          => 'textarea',
				'rows'          => 3,
				'default_value' => 'Whether you are a student looking for the right program or an institution seeking qualified leads, we are here to help you succeed.',
			),
			array(
				'key'           => 'field_hlm_cta_primary_text',
				'label'         => 'Primary Button Text',
				'name'          => 'primary_text',
				'type'          => 'text',
				'default_value' => 'Get Started',
			),
			array(
				'key'           => 'field_hlm_cta_primary_url',
				'label'         => 'Primary Button URL',
				'name'          => 'primary_url',
				'type'          => 'text',
				'default_value' => '#form',
			),
			array(
				'key'           => 'field_hlm_cta_secondary_text',
				'label'         => 'Secondary Button Text',
				'name'          => 'secondary_text',
				'type'          => 'text',
				'default_value' => 'Learn More About Us',
			),
			array(
				'key'           => 'field_hlm_cta_secondary_url',
				'label'         => 'Secondary Button URL',
				'name'          => 'secondary_url',
				'type'          => 'text',
				'default_value' => '/about-us/',
			),
		),
		'location' => array(
			array(
				array(
					'param'    => 'block',
					'operator' => '==',
					'value'    => 'acf/hlm-cta',
				),
			),
		),
	)
);
