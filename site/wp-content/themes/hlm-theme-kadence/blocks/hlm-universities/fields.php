<?php
/**
 * ACF fields: Universities block.
 *
 * @package HLM_Theme
 */

acf_add_local_field_group(
	array(
		'key'    => 'group_hlm_universities',
		'title'  => 'Partner Universities',
		'fields' => array(
			array(
				'key'           => 'field_hlm_universities_overline',
				'label'         => 'Overline',
				'name'          => 'overline',
				'type'          => 'text',
				'default_value' => 'Partner Institutions',
			),
			array(
				'key'           => 'field_hlm_universities_headline',
				'label'         => 'Headline',
				'name'          => 'headline',
				'type'          => 'textarea',
				'rows'          => 2,
				'instructions'  => 'Limited HTML: &lt;br&gt;, &lt;em&gt;.',
				'default_value' => "Top Universities &<br><em>Programs</em>",
			),
			array(
				'key'           => 'field_hlm_universities_description',
				'label'         => 'Description',
				'name'          => 'description',
				'type'          => 'textarea',
				'rows'          => 3,
				'default_value' => 'We partner with leading institutions across the United States, Canada, and online to connect students with the right educational opportunities.',
			),
			array(
				'key'          => 'field_hlm_universities_items',
				'label'        => 'Institutions',
				'name'         => 'institutions',
				'type'         => 'repeater',
				'layout'       => 'block',
				'button_label' => 'Add Institution',
				'sub_fields'   => array(
					array(
						'key'   => 'field_hlm_universities_badge',
						'label' => 'Badge',
						'name'  => 'badge',
						'type'  => 'text',
					),
					array(
						'key'   => 'field_hlm_universities_title',
						'label' => 'Title',
						'name'  => 'title',
						'type'  => 'text',
					),
					array(
						'key'   => 'field_hlm_universities_description_item',
						'label' => 'Description',
						'name'  => 'description',
						'type'  => 'textarea',
						'rows'  => 3,
					),
					array(
						'key'          => 'field_hlm_universities_meta',
						'label'        => 'Meta Items',
						'name'         => 'meta',
						'type'         => 'repeater',
						'layout'       => 'table',
						'button_label' => 'Add Meta',
						'sub_fields'   => array(
							array(
								'key'           => 'field_hlm_universities_meta_icon',
								'label'         => 'Icon',
								'name'          => 'icon',
								'type'          => 'select',
								'choices'       => array(
									'location' => 'Location',
									'clock'    => 'Clock',
								),
								'default_value' => 'location',
							),
							array(
								'key'   => 'field_hlm_universities_meta_text',
								'label' => 'Text',
								'name'  => 'text',
								'type'  => 'text',
							),
						),
					),
				),
			),
		),
		'location' => array(
			array(
				array(
					'param'    => 'block',
					'operator' => '==',
					'value'    => 'acf/hlm-universities',
				),
			),
		),
	)
);
