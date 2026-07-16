<?php
/**
 * ACF fields: Why Us block.
 *
 * @package HLM_Theme
 */

acf_add_local_field_group(
	array(
		'key'    => 'group_hlm_why_us',
		'title'  => 'Why Us Pillars',
		'fields' => array(
			array(
				'key'   => 'field_hlm_why_us_overline',
				'label' => 'Overline',
				'name'  => 'overline',
				'type'  => 'text',
				'default_value' => 'Why Choose Us',
			),
			array(
				'key'   => 'field_hlm_why_us_headline',
				'label' => 'Headline',
				'name'  => 'headline',
				'type'  => 'text',
				'default_value' => 'The Higher Education Elite Difference',
			),
			array(
				'key'          => 'field_hlm_why_us_pillars',
				'label'        => 'Pillars',
				'name'         => 'pillars',
				'type'         => 'repeater',
				'layout'       => 'block',
				'min'          => 1,
				'max'          => 4,
				'button_label' => 'Add Pillar',
				'sub_fields'   => array(
					array(
						'key'   => 'field_hlm_pillar_icon',
						'label' => 'Icon',
						'name'  => 'icon',
						'type'  => 'text',
					),
					array(
						'key'   => 'field_hlm_pillar_title',
						'label' => 'Title',
						'name'  => 'title',
						'type'  => 'text',
					),
					array(
						'key'   => 'field_hlm_pillar_description',
						'label' => 'Description',
						'name'  => 'description',
						'type'  => 'textarea',
						'rows'  => 3,
					),
				),
			),
		),
		'location' => array(
			array(
				array(
					'param'    => 'block',
					'operator' => '==',
					'value'    => 'acf/hlm-why-us',
				),
			),
		),
	)
);
