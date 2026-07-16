<?php
/**
 * ACF fields: Overview block.
 *
 * @package HLM_Theme
 */

acf_add_local_field_group(
	array(
		'key'    => 'group_hlm_overview',
		'title'  => 'Company Overview',
		'fields' => array(
			array(
				'key'   => 'field_hlm_overview_overline',
				'label' => 'Overline',
				'name'  => 'overline',
				'type'  => 'text',
				'default_value' => 'About Us',
			),
			array(
				'key'   => 'field_hlm_overview_headline',
				'label' => 'Headline',
				'name'  => 'headline',
				'type'  => 'text',
				'default_value' => 'Empowering Students to Reach Their Full Potential',
			),
			array(
				'key'   => 'field_hlm_overview_content',
				'label' => 'Content',
				'name'  => 'content',
				'type'  => 'wysiwyg',
				'tabs'  => 'visual',
				'toolbar' => 'basic',
			),
			array(
				'key'           => 'field_hlm_overview_image',
				'label'         => 'Image',
				'name'          => 'image',
				'type'          => 'image',
				'return_format' => 'array',
			),
		),
		'location' => array(
			array(
				array(
					'param'    => 'block',
					'operator' => '==',
					'value'    => 'acf/hlm-overview',
				),
			),
		),
	)
);
