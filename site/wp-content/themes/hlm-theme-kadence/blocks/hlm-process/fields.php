<?php
/**
 * ACF fields: Process block.
 *
 * @package HLM_Theme
 */

acf_add_local_field_group(
	array(
		'key'    => 'group_hlm_process',
		'title'  => 'Process Steps',
		'fields' => array(
			array(
				'key'           => 'field_hlm_process_overline',
				'label'         => 'Overline',
				'name'          => 'overline',
				'type'          => 'text',
				'default_value' => 'How It Works',
			),
			array(
				'key'           => 'field_hlm_process_headline',
				'label'         => 'Headline',
				'name'          => 'headline',
				'type'          => 'textarea',
				'rows'          => 2,
				'instructions'  => 'Limited HTML: &lt;br&gt;, &lt;em&gt;.',
				'default_value' => "Three Simple<br><em>Steps</em>",
			),
			array(
				'key'           => 'field_hlm_process_description',
				'label'         => 'Description',
				'name'          => 'description',
				'type'          => 'textarea',
				'rows'          => 2,
				'default_value' => 'Whether you are a student or institution, getting started takes just minutes.',
			),
			array(
				'key'           => 'field_hlm_process_variant',
				'label'         => 'Style Variant',
				'name'          => 'variant',
				'type'          => 'select',
				'choices'       => array(
					'home'   => 'Home (dark cards)',
					'strip'  => 'Dark Strip (services page)',
					'cream'  => 'Cream background',
					'default'=> 'Default (dark cards)',
				),
				'default_value' => 'home',
			),
			array(
				'key'          => 'field_hlm_process_steps',
				'label'        => 'Steps',
				'name'         => 'steps',
				'type'         => 'repeater',
				'layout'       => 'block',
				'button_label' => 'Add Step',
				'sub_fields'   => array(
					array(
						'key'   => 'field_hlm_process_step_number',
						'label' => 'Step Number',
						'name'  => 'number',
						'type'  => 'text',
					),
					array(
						'key'           => 'field_hlm_process_step_icon',
						'label'         => 'Icon',
						'name'          => 'icon',
						'type'          => 'select',
						'choices'       => array(
							'document' => 'Document',
							'info'     => 'Info',
							'check'    => 'Check',
						),
						'default_value' => 'document',
					),
					array(
						'key'   => 'field_hlm_process_step_title',
						'label' => 'Title',
						'name'  => 'title',
						'type'  => 'text',
					),
					array(
						'key'   => 'field_hlm_process_step_description',
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
					'value'    => 'acf/hlm-process',
				),
			),
		),
	)
);
