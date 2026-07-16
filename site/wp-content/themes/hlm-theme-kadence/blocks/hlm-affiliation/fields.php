<?php
/**
 * ACF fields: Affiliation block.
 *
 * @package HLM_Theme
 */

acf_add_local_field_group(
	array(
		'key'    => 'group_hlm_affiliation',
		'title'  => 'Affiliation',
		'fields' => array(
			array(
				'key'   => 'field_hlm_affiliation_overline',
				'label' => 'Overline',
				'name'  => 'overline',
				'type'  => 'text',
				'default_value' => 'Our Partners',
			),
			array(
				'key'   => 'field_hlm_affiliation_headline',
				'label' => 'Headline',
				'name'  => 'headline',
				'type'  => 'text',
				'default_value' => 'Your Guidance Office Partnership',
			),
			array(
				'key'   => 'field_hlm_affiliation_description',
				'label' => 'Description',
				'name'  => 'description',
				'type'  => 'wysiwyg',
				'tabs'  => 'visual',
				'toolbar' => 'basic',
			),
			array(
				'key'           => 'field_hlm_affiliation_logo',
				'label'         => 'Partner Logo',
				'name'          => 'logo',
				'type'          => 'image',
				'return_format' => 'array',
			),
			array(
				'key'          => 'field_hlm_affiliation_benefits',
				'label'        => 'Collaboration Benefits',
				'name'         => 'benefits',
				'type'         => 'repeater',
				'layout'       => 'block',
				'min'          => 1,
				'max'          => 6,
				'button_label' => 'Add Benefit',
				'sub_fields'   => array(
					array(
						'key'   => 'field_hlm_benefit_icon',
						'label' => 'Icon',
						'name'  => 'icon',
						'type'  => 'text',
					),
					array(
						'key'   => 'field_hlm_benefit_title',
						'label' => 'Title',
						'name'  => 'title',
						'type'  => 'text',
					),
					array(
						'key'   => 'field_hlm_benefit_description',
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
					'value'    => 'acf/hlm-affiliation',
				),
			),
		),
	)
);
