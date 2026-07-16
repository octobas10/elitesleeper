<?php
/**
 * ACF fields: Hero block.
 *
 * @package HLM_Theme
 */

acf_add_local_field_group(
	array(
		'key'    => 'group_hlm_hero',
		'title'  => 'Hero - Lead Capture',
		'fields' => array(
			array(
				'key'           => 'field_hlm_hero_overline',
				'label'         => 'Overline',
				'name'          => 'overline',
				'type'          => 'text',
				'default_value' => 'Education Lead Generation',
			),
			array(
				'key'           => 'field_hlm_hero_headline',
				'label'         => 'Headline',
				'name'          => 'headline',
				'type'          => 'textarea',
				'rows'          => 3,
				'instructions'  => 'Limited HTML allowed: &lt;br&gt;, &lt;em&gt;.',
				'default_value' => "Connect With Your<br><em>Future Students</em>",
			),
			array(
				'key'           => 'field_hlm_hero_description',
				'label'         => 'Description',
				'name'          => 'description',
				'type'          => 'textarea',
				'rows'          => 3,
				'default_value' => 'Whether you are a student seeking the perfect program, a university looking to expand your reach, or a Berkeley partner - we bridge the gap between ambition and opportunity.',
			),
			array(
				'key'           => 'field_hlm_hero_primary_text',
				'label'         => 'Primary CTA Text',
				'name'          => 'primary_cta_text',
				'type'          => 'text',
				'default_value' => 'Get Started',
			),
			array(
				'key'           => 'field_hlm_hero_primary_url',
				'label'         => 'Primary CTA URL',
				'name'          => 'primary_cta_url',
				'type'          => 'text',
				'default_value' => '/#form',
			),
			array(
				'key'           => 'field_hlm_hero_secondary_text',
				'label'         => 'Secondary CTA Text',
				'name'          => 'secondary_cta_text',
				'type'          => 'text',
				'default_value' => 'Our Services',
			),
			array(
				'key'           => 'field_hlm_hero_secondary_url',
				'label'         => 'Secondary CTA URL',
				'name'          => 'secondary_cta_url',
				'type'          => 'text',
				'default_value' => '/services/',
			),
			array(
				'key'           => 'field_hlm_hero_trust_highlight',
				'label'         => 'Trust Highlight',
				'name'          => 'trust_highlight',
				'type'          => 'text',
				'default_value' => '10,000+',
			),
			array(
				'key'           => 'field_hlm_hero_trust_text',
				'label'         => 'Trust Text',
				'name'          => 'trust_text',
				'type'          => 'text',
				'default_value' => 'students connected with programs',
			),
			array(
				'key'           => 'field_hlm_hero_trust_avatars',
				'label'        => 'Trust Avatars',
				'name'         => 'trust_avatars',
				'type'         => 'repeater',
				'layout'       => 'table',
				'max'          => 4,
				'button_label' => 'Add Avatar',
				'sub_fields'   => array(
					array(
						'key'           => 'field_hlm_hero_trust_avatar_image',
						'label'         => 'Image',
						'name'          => 'image',
						'type'          => 'image',
						'return_format' => 'array',
						'preview_size'  => 'thumbnail',
					),
				),
			),
			array(
				'key'           => 'field_hlm_hero_show_form',
				'label'         => 'Show Lead Form',
				'name'          => 'show_lead_form',
				'type'          => 'true_false',
				'ui'            => 1,
				'default_value' => 1,
				'instructions'  => 'Show the 3-tab lead capture form in the hero column.',
			),
		),
		'location' => array(
			array(
				array(
					'param'    => 'block',
					'operator' => '==',
					'value'    => 'acf/hlm-hero',
				),
			),
		),
	)
);
