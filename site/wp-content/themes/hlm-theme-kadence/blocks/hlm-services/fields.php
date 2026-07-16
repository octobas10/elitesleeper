<?php
/**
 * ACF fields: Services block.
 *
 * @package HLM_Theme
 */

acf_add_local_field_group(
	array(
		'key'    => 'group_hlm_services',
		'title'  => 'Services Grid',
		'fields' => array(
			array(
				'key'   => 'field_hlm_services_overline',
				'label' => 'Overline',
				'name'  => 'overline',
				'type'  => 'text',
				'default_value' => 'What We Offer',
			),
			array(
				'key'   => 'field_hlm_services_headline',
				'label' => 'Headline',
				'name'  => 'headline',
				'type'  => 'text',
				'default_value' => 'Comprehensive Education Services',
			),
			array(
				'key'   => 'field_hlm_services_description',
				'label' => 'Description',
				'name'  => 'description',
				'type'  => 'textarea',
				'rows'  => 3,
			),
			array(
				'key'          => 'field_hlm_services_items',
				'label'        => 'Services',
				'name'         => 'services',
				'type'         => 'repeater',
				'layout'       => 'block',
				'min'          => 1,
				'button_label' => 'Add Service',
				'sub_fields'   => array(
					array(
						'key'   => 'field_hlm_service_icon',
						'label' => 'Icon (emoji or class)',
						'name'  => 'icon',
						'type'  => 'text',
					),
					array(
						'key'   => 'field_hlm_service_title',
						'label' => 'Title',
						'name'  => 'title',
						'type'  => 'text',
					),
					array(
						'key'   => 'field_hlm_service_description',
						'label' => 'Description',
						'name'  => 'description',
						'type'  => 'textarea',
						'rows'  => 3,
					),
					array(
						'key'   => 'field_hlm_service_tags',
						'label' => 'Tags (comma-separated)',
						'name'  => 'tags',
						'type'  => 'text',
					),
					array(
						'key'   => 'field_hlm_service_link',
						'label' => 'Link URL',
						'name'  => 'link',
						'type'  => 'url',
					),
				),
			),
			array(
				'key'           => 'field_hlm_services_columns',
				'label'         => 'Grid Columns',
				'name'          => 'columns',
				'type'          => 'select',
				'choices'       => array( '2' => '2', '3' => '3' ),
				'default_value' => '3',
			),
		),
		'location' => array(
			array(
				array(
					'param'    => 'block',
					'operator' => '==',
					'value'    => 'acf/hlm-services',
				),
			),
		),
	)
);
