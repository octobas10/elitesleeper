<?php
/**
 * ACF fields: Programs block.
 *
 * @package HLM_Theme
 */

acf_add_local_field_group(
	array(
		'key'    => 'group_hlm_programs',
		'title'  => 'Popular Programs',
		'fields' => array(
			array(
				'key'           => 'field_hlm_programs_overline',
				'label'         => 'Overline',
				'name'          => 'overline',
				'type'          => 'text',
				'default_value' => 'Popular Programs',
			),
			array(
				'key'           => 'field_hlm_programs_headline',
				'label'         => 'Headline',
				'name'          => 'headline',
				'type'          => 'textarea',
				'rows'          => 2,
				'instructions'  => 'Limited HTML: &lt;br&gt;, &lt;em&gt;.',
				'default_value' => "Programs Students<br><em>Are Searching For</em>",
			),
			array(
				'key'           => 'field_hlm_programs_description',
				'label'         => 'Description',
				'name'          => 'description',
				'type'          => 'textarea',
				'rows'          => 3,
				'default_value' => 'From nursing to cybersecurity, business to criminal justice - find the program that matches your career goals.',
			),
			array(
				'key'          => 'field_hlm_programs_items',
				'label'        => 'Programs',
				'name'         => 'programs',
				'type'         => 'repeater',
				'layout'       => 'table',
				'button_label' => 'Add Program',
				'sub_fields'   => array(
					array(
						'key'           => 'field_hlm_programs_icon',
						'label'         => 'Icon',
						'name'          => 'icon',
						'type'          => 'select',
						'choices'       => array(
							'activity'  => 'Activity',
							'monitor'   => 'Monitor',
							'graduation'=> 'Graduation',
							'document'  => 'Document',
						),
						'default_value' => 'activity',
					),
					array(
						'key'   => 'field_hlm_programs_name',
						'label' => 'Program Name',
						'name'  => 'name',
						'type'  => 'text',
					),
				),
			),
		),
		'location' => array(
			array(
				array(
					'param'    => 'block',
					'operator' => '==',
					'value'    => 'acf/hlm-programs',
				),
			),
		),
	)
);
